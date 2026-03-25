<?php

namespace App\Http\Controllers\Tcf;

use App\Http\Controllers\Controller;
use App\Models\TcfSerie;
use App\Models\TcfDiscipline;
use App\Models\TcfAbonnement;
use App\Models\TcfPassage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TcfController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    // ── ÉCRAN 1 : Liste des séries ──
    public function index()
    {
        $user = Auth::user();
        $series = TcfSerie::where('type', 'TCF')
            ->where('actif', true)
            ->orderBy('ordre')
            ->get();

        $aAbonnement = TcfAbonnement::userActif($user->id);

        // Compte les épreuves gratuites déjà utilisées
        $passagesGratuits = TcfPassage::where('user_id', $user->id)
            ->whereHas('discipline.serie', fn($q) => $q->where('gratuit', true))
            ->where('statut', 'termine')
            ->count();

        return view('tcf.index', compact('series', 'aAbonnement', 'passagesGratuits'));
    }

    // ── ÉCRAN 2 : Disciplines d'une série ──
    public function disciplines(TcfSerie $serie)
    {
        $user = Auth::user();

        // Vérifier accès
        if (!$serie->gratuit && !TcfAbonnement::userActif($user->id)) {
            return redirect()->route('tcf.index')
                ->with('error', 'Abonnement requis pour accéder à cette série.');
        }

        $disciplines = $serie->disciplines()->where('actif', true)->get();

        return view('tcf.disciplines', compact('serie', 'disciplines'));
    }

    // ── DÉMARRER une épreuve ──
    public function demarrer(Request $request, TcfSerie $serie,TcfDiscipline $discipline)
    {
        $user = Auth::user();
        $serie = $discipline->serie;
        // Sécurité : vérifier cohérence
        if ($discipline->serie_id !== $serie->id) {
            abort(404);
        }


        // Vérif abonnement ou gratuit
        if (!$serie->gratuit && !TcfAbonnement::userActif($user->id)) {
            return redirect()->route('tcf.index')
                ->with('error', 'Abonnement requis.');
        }

        // Max 2 épreuves gratuites
        if ($serie->gratuit && !TcfAbonnement::userActif($user->id)) {
            $nbGratuits = TcfPassage::where('user_id', $user->id)
                ->whereHas('discipline.serie', fn($q) => $q->where('gratuit', true))
                ->where('statut', 'termine')
                ->count();
            if ($nbGratuits >= 2) {
                return redirect()->route('tcf.index')
                    ->with('error', 'Limite de 2 épreuves gratuites atteinte. Abonnez-vous pour continuer.');
            }
        }

        // Créer le passage
        $passage = TcfPassage::create([
            'user_id'       => $user->id,
            'discipline_id' => $discipline->id,
            'debut_at'      => now(),
            'statut'        => 'en_cours',
        ]);

        return redirect()->route('tcf.epreuve.show', [
            'serie'      => $serie->code,
            'discipline' => $discipline->code,
            'passage'  => $passage->id,
            'question' => 1,
        ]);
    }

    // ── Page abonnement ──
    public function abonnement()
    {
        $forfaits = [
            [
                'nom'      => 'Mensuel',
                'prix'     => 5000,
                'devise'   => 'XAF',
                'duree'    => '1 mois',
                'avantages'=> ['Accès illimité', 'Toutes les séries', 'TCF + TEF'],
            ],
            [
                'nom'      => 'Trimestriel',
                'prix'     => 12000,
                'devise'   => 'XAF',
                'duree'    => '3 mois',
                'populaire'=> true,
                'avantages'=> ['Accès illimité', 'Toutes les séries', 'TCF + TEF', '-20% de réduction'],
            ],
            [
                'nom'      => 'Annuel',
                'prix'     => 40000,
                'devise'   => 'XAF',
                'duree'    => '12 mois',
                'avantages'=> ['Accès illimité', 'Toutes les séries', 'TCF + TEF', '-33% de réduction'],
            ],
        ];

        return view('tcf.abonnement', compact('forfaits'));
    }
}

