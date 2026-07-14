<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Langue;
use App\Models\LangueDiscipline;
use App\Models\LangueSerie;
use App\Models\LangueQuestion;
use App\Models\LangueReponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminTestController extends Controller
{
    private function checkAccess(): void
    {
        abort_unless(
            Auth::user()->hasAnyRole(['super-admin', 'admin', 'instructor']),
            403,
            'Accès non autorisé.'
        );
    }

    // ══════════════════════════════════════
    //  INDEX — Liste des tests, filtrable par langue
    // ══════════════════════════════════════
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = LangueSerie::with(['discipline.langue'])
            ->withCount('questions');

        if ($request->filled('langue')) {
            $query->whereHas('discipline.langue', fn($q) => $q->where('code', $request->langue));
        }

        if ($request->filled('discipline')) {
            $query->where('discipline_id', $request->discipline);
        }

        $series = $query->latest()->paginate(15)->withQueryString();

        $langues = Langue::orderBy('ordre')->get();

        // Disciplines de la langue sélectionnée (pour le filtre secondaire)
        $disciplines = $request->filled('langue')
            ? LangueDiscipline::whereHas('langue', fn($q) => $q->where('code', $request->langue))->get()
            : collect();

        return view('admin.tests.index', compact('series', 'langues', 'disciplines'));
    }

    // ══════════════════════════════════════
    //  CREATE — Formulaire de création d'un test
    // ══════════════════════════════════════
    public function create()
    {
        $this->checkAccess();

        $langues = Langue::with('disciplines')->orderBy('ordre')->get();

        return view('admin.tests.create', compact('langues'));
    }

    // ══════════════════════════════════════
    //  STORE — Enregistre le nouveau test (série)
    // ══════════════════════════════════════
    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'discipline_id'  => 'required|exists:langue_disciplines,id',
            'titre'          => 'required|string|max:150',
            'description'    => 'nullable|string|max:1000',
            'niveau'         => 'required|integer|min:1|max:5',
            'duree_minutes'  => 'required|integer|min:5|max:240',
            'gratuite'       => 'nullable|boolean',
            'active'         => 'nullable|boolean',
            'ordre'          => 'nullable|integer|min:0',
        ]);

        $serie = LangueSerie::create([
            'discipline_id'  => $validated['discipline_id'],
            'titre'          => $validated['titre'],
            'description'    => $validated['description'] ?? null,
            'niveau'         => $validated['niveau'],
            'duree_minutes'  => $validated['duree_minutes'],
            'gratuite'       => $request->boolean('gratuite'),
            'active'         => $request->boolean('active', true),
            'ordre'          => $validated['ordre'] ?? 0,
            'nombre_questions' => 0,
        ]);

        return redirect()->route('admin.tests.edit', $serie)
            ->with('success', "Test « {$serie->titre} » créé. Ajoutez maintenant vos questions.");
    }

    // ══════════════════════════════════════
    //  EDIT — Formulaire d'édition + gestion des questions
    // ══════════════════════════════════════
    public function edit(LangueSerie $serie)
    {
        $this->checkAccess();

        $serie->load(['discipline.langue', 'questions.reponses']);

        $langues = Langue::with('disciplines')->orderBy('ordre')->get();

        return view('admin.tests.edit', compact('serie', 'langues'));
    }

    // ══════════════════════════════════════
    //  UPDATE — Met à jour les métadonnées du test
    // ══════════════════════════════════════
    public function update(Request $request, LangueSerie $serie)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'discipline_id'  => 'required|exists:langue_disciplines,id',
            'titre'          => 'required|string|max:150',
            'description'    => 'nullable|string|max:1000',
            'niveau'         => 'required|integer|min:1|max:5',
            'duree_minutes'  => 'required|integer|min:5|max:240',
            'gratuite'       => 'nullable|boolean',
            'active'         => 'nullable|boolean',
            'ordre'          => 'nullable|integer|min:0',
        ]);

        $serie->update([
            'discipline_id'  => $validated['discipline_id'],
            'titre'          => $validated['titre'],
            'description'    => $validated['description'] ?? null,
            'niveau'         => $validated['niveau'],
            'duree_minutes'  => $validated['duree_minutes'],
            'gratuite'       => $request->boolean('gratuite'),
            'active'         => $request->boolean('active'),
            'ordre'          => $validated['ordre'] ?? 0,
        ]);

        return back()->with('success', 'Test mis à jour avec succès.');
    }

    // ══════════════════════════════════════
    //  DESTROY — Supprime un test et ses questions
    // ══════════════════════════════════════
    public function destroy(LangueSerie $serie)
    {
        $this->checkAccess();

        DB::transaction(function () use ($serie) {
            foreach ($serie->questions as $question) {
                $question->reponses()->delete();
                $question->delete();
            }
            $serie->delete();
        });

        return redirect()->route('admin.tests.index')
            ->with('success', 'Test supprimé avec succès.');
    }

    // ══════════════════════════════════════
    //  QUESTIONS — Ajouter une question QCM (4 réponses)
    // ══════════════════════════════════════
    public function storeQuestion(Request $request, LangueSerie $serie)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'enonce'         => 'required|string|max:1000',
            'contexte'       => 'nullable|string|max:2000',
            'explication'    => 'nullable|string|max:1000',
            'points'         => 'nullable|integer|min:1|max:10',
            'duree_secondes' => 'nullable|integer|min:10|max:600',
            'reponses'       => 'required|array|min:2|max:6',
            'reponses.*'     => 'required|string|max:500',
            'correcte'       => 'required|integer',
        ]);

        DB::transaction(function () use ($validated, $serie) {
            $question = LangueQuestion::create([
                'serie_id'       => $serie->id,
                'enonce'         => $validated['enonce'],
                'type_question'  => 'qcm',
                'contexte'       => $validated['contexte'] ?? null,
                'points'         => $validated['points'] ?? 1,
                'duree_secondes' => $validated['duree_secondes'] ?? 60,
                'explication'    => $validated['explication'] ?? null,
                'ordre'          => $serie->questions()->count(),
            ]);

            foreach ($validated['reponses'] as $index => $texte) {
                LangueReponse::create([
                    'question_id' => $question->id,
                    'texte'       => $texte,
                    'correcte'    => (int) $validated['correcte'] === $index,
                    'ordre'       => $index,
                ]);
            }

            $serie->update(['nombre_questions' => $serie->questions()->count()]);
        });

        return back()->with('success', 'Question ajoutée avec succès.');
    }

    // ══════════════════════════════════════
    //  QUESTIONS — Modifier une question existante
    // ══════════════════════════════════════
    public function updateQuestion(Request $request, LangueQuestion $question)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'enonce'         => 'required|string|max:1000',
            'contexte'       => 'nullable|string|max:2000',
            'explication'    => 'nullable|string|max:1000',
            'points'         => 'nullable|integer|min:1|max:10',
            'duree_secondes' => 'nullable|integer|min:10|max:600',
            'reponses'       => 'required|array|min:2|max:6',
            'reponses.*.id'  => 'nullable|exists:langue_reponses,id',
            'reponses.*.texte' => 'required|string|max:500',
            'correcte'       => 'required|integer',
        ]);

        DB::transaction(function () use ($validated, $question) {
            $question->update([
                'enonce'         => $validated['enonce'],
                'contexte'       => $validated['contexte'] ?? null,
                'explication'    => $validated['explication'] ?? null,
                'points'         => $validated['points'] ?? 1,
                'duree_secondes' => $validated['duree_secondes'] ?? 60,
            ]);

            foreach ($validated['reponses'] as $index => $rep) {
                LangueReponse::updateOrCreate(
                    ['id' => $rep['id'] ?? null, 'question_id' => $question->id],
                    [
                        'question_id' => $question->id,
                        'texte'       => $rep['texte'],
                        'correcte'    => (int) $validated['correcte'] === $index,
                        'ordre'       => $index,
                    ]
                );
            }
        });

        return back()->with('success', 'Question mise à jour.');
    }

    // ══════════════════════════════════════
    //  QUESTIONS — Supprimer une question
    // ══════════════════════════════════════
    public function destroyQuestion(LangueQuestion $question)
    {
        $this->checkAccess();

        $serie = $question->serie;
        $question->reponses()->delete();
        $question->delete();

        $serie->update(['nombre_questions' => $serie->questions()->count()]);

        return back()->with('success', 'Question supprimée.');
    }
}