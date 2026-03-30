<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Langue;
use App\Models\LangueDiscipline;
use App\Models\LangueSerie;
use App\Models\LangueQuestion;
use App\Models\LangueReponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

// ─────────────────────────────────────────────────────────────
//  FICHIER : app/Http/Controllers/Admin/LangueController.php
// ─────────────────────────────────────────────────────────────

class LangueController extends Controller
{
    // ── Vérification accès ──
    private function check(): void
    {
        abort_unless(auth()->user()->can('create test'), 403,
            'Vous n\'avez pas la permission de gérer les langues.');
    }

    // ════════════════════════════════════════
    //  INDEX — Les 4 examens avec stats
    // ════════════════════════════════════════
    public function index()
    {
        $this->check();
 
        $langues = Langue::with([
            'disciplines.series' => fn($q) => $q->withCount('questions'),
        ])->orderBy('ordre')->get();
 
        return view('admin.langues.index', compact('langues'));
    }

    // ════════════════════════════════════════
    //  SHOW — Détail d'un examen
    // ════════════════════════════════════════
    public function show(Langue $langue)
    {
        $this->check();

        $langue->load(['disciplines' => function ($q) {
            $q->orderBy('ordre')->with(['series' => function ($q2) {
                $q2->withCount('questions')->orderBy('ordre');
            }]);
        }]);

        return view('admin.langues.show', compact('langue'));
    }

    // ════════════════════════════════════════
    //  SÉRIE — Créer
    // ════════════════════════════════════════
    public function createSerie(LangueDiscipline $discipline)
    {
        $this->check();
        $discipline->load('langue');
        return view('admin.langues.series.create', compact('discipline'));
    }

    public function storeSerie(Request $request, LangueDiscipline $discipline)
    {
        $this->check();

        $data = $request->validate([
            'titre'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'niveau'        => 'required|in:1,2,3',
            'duree_minutes' => 'required|integer|min:5|max:300',
            'gratuite'      => 'nullable|boolean',
        ]);

        $serie = LangueSerie::create([
            'discipline_id' => $discipline->id,
            'titre'         => $data['titre'],
            'description'   => $data['description'] ?? null,
            'niveau'        => $data['niveau'],
            'duree_minutes' => $data['duree_minutes'],
            'gratuite'      => $request->boolean('gratuite'),
            'active'        => true,
            'ordre'         => LangueSerie::where('discipline_id', $discipline->id)
                                          ->max('ordre') + 1,
        ]);

        return redirect()
            ->route('admin.series.show', $serie)
            ->with('success', "Série « {$serie->titre} » créée avec succès.");
    }

    // ════════════════════════════════════════
    //  SÉRIE — Afficher (liste des questions)
    // ════════════════════════════════════════
    public function showSerie(LangueSerie $serie)
    {
        $this->check();
        $serie->load(['discipline.langue', 'questions.reponses']);
        return view('admin.langues.series.show', compact('serie'));
    }

    // ════════════════════════════════════════
    //  SÉRIE — Modifier
    // ════════════════════════════════════════
    public function editSerie(LangueSerie $serie)
    {
        $this->check();
        $langues = Langue::orderBy('ordre')->get();
        $serie->load('discipline.langue');
        return view('admin.langues.series.edit', compact('serie', 'langues'));
    }

    public function updateSerie(Request $request, LangueSerie $serie)
    {
        $this->check();

        $data = $request->validate([
            'titre'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:1000',
            'niveau'        => 'required|in:1,2,3',
            'duree_minutes' => 'required|integer|min:5|max:300',
            'gratuite'      => 'nullable|boolean',
            'active'        => 'nullable|boolean',
        ]);

        $serie->update([
            'titre'         => $data['titre'],
            'description'   => $data['description'] ?? null,
            'niveau'        => $data['niveau'],
            'duree_minutes' => $data['duree_minutes'],
            'gratuite'      => $request->boolean('gratuite'),
            'active'        => $request->boolean('active', true),
        ]);

        return back()->with('success', 'Série mise à jour avec succès.');
    }

    // ════════════════════════════════════════
    //  SÉRIE — Supprimer
    // ════════════════════════════════════════
    public function destroySerie(LangueSerie $serie)
    {
        $this->check();

        $nom        = $serie->titre;
        $langueId   = $serie->discipline->langue_id;

        // Supprimer les fichiers médias liés
        foreach ($serie->questions as $q) {
            if ($q->image) Storage::disk('public')->delete($q->image);
            if ($q->audio) Storage::disk('public')->delete($q->audio);
        }

        $serie->delete();

        return redirect()
            ->route('admin.langues.show', $langueId)
            ->with('success', "Série « {$nom} » et ses questions supprimées.");
    }

    // ════════════════════════════════════════
    //  QUESTION — Créer
    // ════════════════════════════════════════
    public function createQuestion(LangueSerie $serie)
    {
        $this->check();
        $serie->load('discipline.langue');
        return view('admin.langues.questions.create', compact('serie'));
    }

    public function storeQuestion(Request $request, LangueSerie $serie)
    {
        $this->check();

        $request->validate([
            'enonce'         => 'required|string',
            'type_question'  => 'required|in:qcm,vrai_faux,texte_libre,audio',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'audio'          => 'nullable|mimes:mp3,mp4,wav,ogg,m4a|max:20480',
            'contexte'       => 'nullable|string',
            'points'         => 'required|integer|min:1|max:10',
            'duree_secondes' => 'required|integer|min:10|max:600',
            'explication'    => 'nullable|string|max:1000',
            'reponses'       => 'nullable|array|min:2',
            'reponses.*.texte' => 'required_with:reponses|string',
            'correcte'       => 'nullable|integer',
        ]);

        // ── Upload image ──
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')
                ->store('langues/images', 'public');
        }

        // ── Upload audio ──
        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')
                ->store('langues/audio', 'public');
        }

        $question = LangueQuestion::create([
            'serie_id'       => $serie->id,
            'enonce'         => $request->enonce,
            'type_question'  => $request->type_question,
            'image'          => $imagePath,
            'audio'          => $audioPath,
            'contexte'       => $request->contexte,
            'points'         => $request->points,
            'duree_secondes' => $request->duree_secondes,
            'explication'    => $request->explication,
            'ordre'          => LangueQuestion::where('serie_id', $serie->id)
                                              ->max('ordre') + 1,
        ]);

        // ── Réponses QCM / Vrai-Faux ──
        if ($request->filled('reponses')
            && in_array($request->type_question, ['qcm', 'vrai_faux'])
        ) {
            foreach ($request->reponses as $idx => $rep) {
                LangueReponse::create([
                    'question_id' => $question->id,
                    'texte'       => $rep['texte'],
                    'correcte'    => ($request->correcte == $idx),
                    'ordre'       => $idx,
                ]);
            }
        }

        // Mettre à jour le compteur
        $serie->update([
            'nombre_questions' => $serie->questions()->count(),
        ]);

        return redirect()
            ->route('admin.series.show', $serie)
            ->with('success', 'Question ajoutée avec succès.');
    }

    // ════════════════════════════════════════
    //  QUESTION — Modifier
    // ════════════════════════════════════════
    public function editQuestion(LangueQuestion $question)
    {
        $this->check();
        $question->load(['serie.discipline.langue', 'reponses']);
        $serie = $question->serie;
        return view('admin.langues.questions.create', compact('question', 'serie'));
    }

    public function updateQuestion(Request $request, LangueQuestion $question)
    {
        $this->check();

        $request->validate([
            'enonce'         => 'required|string',
            'type_question'  => 'required|in:qcm,vrai_faux,texte_libre,audio',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'audio'          => 'nullable|mimes:mp3,mp4,wav,ogg,m4a|max:20480',
            'contexte'       => 'nullable|string',
            'points'         => 'required|integer|min:1|max:10',
            'duree_secondes' => 'required|integer|min:10|max:600',
            'explication'    => 'nullable|string|max:1000',
        ]);

        $data = [
            'enonce'         => $request->enonce,
            'type_question'  => $request->type_question,
            'contexte'       => $request->contexte,
            'points'         => $request->points,
            'duree_secondes' => $request->duree_secondes,
            'explication'    => $request->explication,
        ];

        // ── Nouvelle image ──
        if ($request->hasFile('image')) {
            if ($question->image) Storage::disk('public')->delete($question->image);
            $data['image'] = $request->file('image')->store('langues/images', 'public');
        }
        if ($request->has('delete_image') && $request->delete_image) {
            if ($question->image) Storage::disk('public')->delete($question->image);
            $data['image'] = null;
        }

        // ── Nouvel audio ──
        if ($request->hasFile('audio')) {
            if ($question->audio) Storage::disk('public')->delete($question->audio);
            $data['audio'] = $request->file('audio')->store('langues/audio', 'public');
        }
        if ($request->has('delete_audio') && $request->delete_audio) {
            if ($question->audio) Storage::disk('public')->delete($question->audio);
            $data['audio'] = null;
        }

        $question->update($data);

        // ── Recréer les réponses si QCM ──
        if ($request->filled('reponses')
            && in_array($request->type_question, ['qcm', 'vrai_faux'])
        ) {
            $question->reponses()->delete();
            foreach ($request->reponses as $idx => $rep) {
                LangueReponse::create([
                    'question_id' => $question->id,
                    'texte'       => $rep['texte'],
                    'correcte'    => ($request->correcte == $idx),
                    'ordre'       => $idx,
                ]);
            }
        }

        return back()->with('success', 'Question mise à jour avec succès.');
    }

    // ════════════════════════════════════════
    //  QUESTION — Supprimer
    // ════════════════════════════════════════
    public function destroyQuestion(LangueQuestion $question)
    {
        $this->check();

        $serie = $question->serie;

        if ($question->image) Storage::disk('public')->delete($question->image);
        if ($question->audio) Storage::disk('public')->delete($question->audio);

        $question->delete();

        // Mettre à jour le compteur
        $serie->update([
            'nombre_questions' => $serie->questions()->count(),
        ]);

        return back()->with('success', 'Question supprimée.');
    }
}