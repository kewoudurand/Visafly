<?php

namespace App\Http\Controllers;

use App\Models\Langue;
use App\Models\LangueDiscipline;
use App\Models\LangueSerie;
use App\Models\LangueQuestion;
use App\Models\LanguePassage;
use App\Models\LanguePassageReponse;
use App\Models\LangueAbonnement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LangueEpreuveController extends Controller
{
    // ── Page accueil — liste des 4 examens ──
    public function index()
    {
        // ✅ corrigé — eager-load des disciplines, sinon pluck('disciplines.*.id') est toujours vide
        $langues = Langue::where('actif', true)
            ->with('disciplines')
            ->orderBy('ordre')
            ->get();

        $disciplines = $langues->pluck('disciplines')->flatten();
        $series = LangueSerie::where('active', true)->orderBy('ordre')->get();

        return view('langues.disciplines', [
            'langues'     => $langues,
            'series'      => $series,
            'serie'       => $series->first(),
            'langue'      => $langues->first(),
            'disciplines' => $disciplines,
        ]);
    }

    // ── Liste des séries d'une langue ──
    public function series(string $code)
    {
        $langue = Langue::where('code', $code)->where('actif', true)->firstOrFail();

        $series = LangueSerie::whereIn(
            'discipline_id',
            $langue->disciplines()->pluck('id')
        )->where('active', true)->orderBy('ordre')->get();

        // ✅ corrigé — vérifie l'abonnement pour CETTE langue précise, pas un abonnement générique
        $aAbonnement = $this->verifierAbonnement($langue->code);

        return view('langues.series', compact('langue', 'series', 'aAbonnement'));
    }

    // ── Choix discipline + modal ──
    public function disciplines(string $code, LangueSerie $serie)
    {
        $langue = Langue::where('code', $code)->firstOrFail();

        if (!$serie->gratuite) {
            if (!Auth::check()) {
                return redirect()->route('login')
                    ->with('error', 'Connectez-vous pour accéder à cette série.');
            }

            // ✅ corrigé — vérification par langue, pas globale
            if (!$this->verifierAbonnement($langue->code)) {
                return redirect()->route('abonnement.index')
                    ->with('error', "Cette série nécessite un abonnement pour {$langue->nom}.");
            }
        }

        $disciplines = $langue->disciplines()
            ->where('actif', true)->orderBy('ordre')->get();

        return view('langues.disciplines', compact('langue', 'serie', 'disciplines'));
    }

    // ── Interface épreuve ──
    public function epreuve(string $code, LangueSerie $serie, LangueDiscipline $discipline)
    {
        $langue = Langue::where('code', $code)->firstOrFail();

        // Sécurité serveur — même vérification que dans disciplines(), au cas où
        // l'utilisateur accède directement à l'URL de l'épreuve sans passer par le modal.
        if (!$serie->gratuite) {
            abort_unless(Auth::check(), 403, 'Connexion requise.');
            abort_unless(
                $this->verifierAbonnement($langue->code),
                403,
                "Abonnement requis pour {$langue->nom}."
            );
        }

        // ✅ questions chargées avec réponses (QCM) — les rédactions auront une collection vide
        $questions = LangueQuestion::where('serie_id', $serie->id)
            ->with(['reponses' => fn($q) => $q->orderBy('ordre')])
            ->orderBy('ordre')->get();

        abort_if($questions->isEmpty(), 404, 'Aucune question dans cette série.');

        $passage = LanguePassage::create([
            'user_id'         => Auth::id(),
            'serie_id'        => $serie->id,
            'discipline_id'   => $discipline->id,
            'statut'          => 'en_cours',
            'total_questions' => $questions->count(),
            'debut_at'        => now(),
        ]);

        Session::put("passage_id_{$serie->id}_{$discipline->id}", $passage->id);

        return view('langues.epreuve',
            compact('langue', 'serie', 'discipline', 'questions', 'passage'));
    }

    // ── Soumettre et calculer ──
    public function soumettre(Request $request, string $code, LangueSerie $serie, LangueDiscipline $discipline)
    {
        $langue = Langue::where('code', $code)->firstOrFail();

        $validated = $request->validate([
            'reponses'               => 'nullable|array',
            'reponses.*'             => 'nullable|integer',
            'reponses_libres'        => 'nullable|array',
            'reponses_libres.*'      => 'nullable|string|max:10000',
        ]);

        $reponses       = $validated['reponses'] ?? [];
        $reponsesLibres = $validated['reponses_libres'] ?? [];

        $questions = LangueQuestion::where('serie_id', $serie->id)
            ->with('reponses')->orderBy('ordre')->get();

        $passageId = Session::get("passage_id_{$serie->id}_{$discipline->id}");
        $passage   = $passageId ? LanguePassage::find($passageId) : null;

        $debut         = $passage?->debut_at ?? now();
        $dureeSecondes = (int) $debut->diffInSeconds(now());

        $bonnes           = 0;
        $mauvaises        = 0;
        $nonRepondues     = 0;
        $enAttenteCorrect = 0; // ✅ nouveau — rédactions non encore corrigées manuellement
        $pointsTotal      = 0;
        $pointsObt        = 0;
        $corrections      = [];

        foreach ($questions as $q) {
            // ✅ Une question sans réponses QCM associées = rédaction libre
            $estRedaction = $q->reponses->isEmpty();

            if ($estRedaction) {
                $texteRedige = trim($reponsesLibres[$q->id] ?? '');
                $aRepondu    = $texteRedige !== '';

                if (!$aRepondu) {
                    $nonRepondues++;
                } else {
                    $enAttenteCorrect++;
                    // Les points de rédaction ne comptent pas dans le score auto —
                    // ils seront ajoutés manuellement lors de la correction admin.
                }

                $corrections[] = [
                    'question'      => $q,
                    'estRedaction'  => true,
                    'texteRedige'   => $texteRedige,
                    'motsMin'       => $q->mots_min,
                ];

                if ($passage) {
                    LanguePassageReponse::updateOrCreate(
                        ['passage_id' => $passage->id, 'question_id' => $q->id],
                        [
                            'reponse_id'    => null,
                            'reponse_texte' => $aRepondu ? $texteRedige : null,
                            'correcte'      => null, // en attente de correction manuelle
                        ]
                    );
                }

                continue;
            }

            // ── Question QCM classique ──
            $pointsTotal += $q->points;
            $reponduId   = $reponses[$q->id] ?? null;
            $bonneRep    = $q->reponses->firstWhere('correcte', true);
            $estCorrecte = $reponduId && $bonneRep && $reponduId == $bonneRep->id;

            if (!$reponduId)        $nonRepondues++;
            elseif ($estCorrecte) { $bonnes++; $pointsObt += $q->points; }
            else                    $mauvaises++;

            $corrections[] = [
                'question'     => $q,
                'estRedaction' => false,
                'reponses'     => $q->reponses,
                'repondue'     => $reponduId,
                'correct'      => $estCorrecte,
            ];

            if ($passage) {
                LanguePassageReponse::updateOrCreate(
                    ['passage_id' => $passage->id, 'question_id' => $q->id],
                    ['reponse_id' => $reponduId, 'reponse_texte' => null, 'correcte' => $estCorrecte]
                );
            }
        }

        // ✅ Score calculé uniquement sur les questions QCM (les rédactions sont exclues
        // du calcul automatique tant qu'elles n'ont pas été notées manuellement)
        $totalQcm = $questions->filter(fn($q) => $q->reponses->isNotEmpty())->count();
        $score    = $totalQcm > 0 ? (int) round(($bonnes / $totalQcm) * 100) : 0;

        $total      = $questions->count();
        $tempsPasse = (int) round($dureeSecondes / 60);

        if ($passage) {
            $passage->update([
                // Si des rédactions sont en attente de correction, le passage reste
                // "en_correction" plutôt que "termine", pour distinguer les deux états.
                'statut'             => $enAttenteCorrect > 0 ? 'en_correction' : 'termine',
                'score'              => $score,
                'bonnes_reponses'    => $bonnes,
                'mauvaises_reponses' => $mauvaises,
                'non_repondues'      => $nonRepondues,
                'total_questions'    => $total,
                'points_obtenus'     => $pointsObt,
                'points_total'       => $pointsTotal,
                'fin_at'             => now(),
                'duree_secondes'     => $dureeSecondes,
            ]);
        }

        Session::forget("passage_id_{$serie->id}_{$discipline->id}");

        return view('langues.resultat', compact(
            'langue', 'serie', 'discipline',
            'score', 'bonnes', 'mauvaises', 'nonRepondues', 'enAttenteCorrect',
            'total', 'corrections', 'tempsPasse', 'passage'
        ));
    }

    // ── Vérifier abonnement actif POUR UNE LANGUE PRÉCISE ──
    private function verifierAbonnement(string $codeLangue): bool
    {
        if (!Auth::check()) return false;

        // ✅ corrigé — colonne 'statut' (pas 'actif'), et filtre sur la langue précise
        // (cohérent avec le modèle "1 abonnement = 1 examen" mis en place)
        return LangueAbonnement::where('user_id', Auth::id())
            ->whereHas('langue', fn($q) => $q->where('code', $codeLangue))
            ->where('statut', 'actif')
            ->where('fin_at', '>=', now())
            ->exists();
    }
}