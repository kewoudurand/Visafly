<?php
// app/Http/Controllers/Admin/AdminTestController.php

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
use Illuminate\Support\Facades\Storage;

class AdminTestController extends Controller
{
    private function checkAccess(): void
    {
        abort_unless(
            Auth::user()->hasAnyRole(['super-admin', 'admin', 'instructeur']),
            403,
            'Accès non autorisé.'
        );
    }

    public function index(Request $request)
    {
        $this->checkAccess();

        $query = LangueSerie::with(['discipline.langue'])->withCount('questions');

        if ($request->filled('langue')) {
            $query->whereHas('discipline.langue', fn($q) => $q->where('code', $request->langue));
        }
        if ($request->filled('discipline')) {
            $query->where('discipline_id', $request->discipline);
        }

        $series = $query->latest()->paginate(15)->withQueryString();
        $langues = Langue::orderBy('ordre')->get();
        $disciplines = $request->filled('langue')
            ? LangueDiscipline::whereHas('langue', fn($q) => $q->where('code', $request->langue))->get()
            : collect();

        return view('admin.tests.index', compact('series', 'langues', 'disciplines'));
    }

    public function create()
    {
        $this->checkAccess();
        $langues = Langue::with('disciplines')->orderBy('ordre')->get();
        return view('admin.tests.create', compact('langues'));
    }

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

    public function edit(LangueSerie $serie)
    {
        $this->checkAccess();
        $serie->load(['discipline.langue', 'questions.reponses']);
        $langues = Langue::with('disciplines')->orderBy('ordre')->get();
        return view('admin.tests.edit', compact('serie', 'langues'));
    }

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

    public function destroy(LangueSerie $serie)
    {
        $this->checkAccess();

        DB::transaction(function () use ($serie) {
            foreach ($serie->questions as $question) {
                if ($question->image_path) Storage::disk('public')->delete($question->image_path);
                if ($question->audio_path) Storage::disk('public')->delete($question->audio_path);
                $question->reponses()->delete();
                $question->delete();
            }
            $serie->delete();
        });

        return redirect()->route('admin.tests.index')->with('success', 'Test supprimé avec succès.');
    }

    // ══════════════════════════════════════
    //  QUESTIONS — Ajouter (QCM ou rédaction, avec média selon discipline)
    // ══════════════════════════════════════
    public function storeQuestion(Request $request, LangueSerie $serie)
    {
        $this->checkAccess();

        $discipline = $serie->discipline;

        $rules = [
            'enonce'         => 'required|string|max:1000',
            'contexte'       => 'nullable|string|max:2000',
            'explication'    => 'nullable|string|max:1000',
            'points'         => 'nullable|integer|min:1|max:10',
            'duree_secondes' => 'nullable|integer|min:10|max:1800',
        ];

        if ($discipline->has_image) {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096';
        }
        if ($discipline->has_audio) {
            $rules['audio'] = 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480';
        }

        if ($discipline->reponse_libre) {
            // Discipline de rédaction (EE, PE, Writing, Schreiben) — pas de QCM
            $rules['mots_min'] = 'nullable|integer|min:10|max:2000';
        } else {
            // Discipline QCM (CE, CO, EO, PO...)
            $rules['reponses']   = 'required|array|min:2|max:6';
            $rules['reponses.*'] = 'required|string|max:500';
            $rules['correcte']   = 'required|integer';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $request, $serie, $discipline) {
            $imagePath = null;
            $audioPath = null;

            if ($discipline->has_image && $request->hasFile('image')) {
                $imagePath = $request->file('image')->store('questions/images', 'public');
            }
            if ($discipline->has_audio && $request->hasFile('audio')) {
                $audioPath = $request->file('audio')->store('questions/audio', 'public');
            }

            $question = LangueQuestion::create([
                'serie_id'       => $serie->id,
                'enonce'         => $validated['enonce'],
                'type_question'  => $discipline->reponse_libre ? 'redaction' : 'qcm',
                'contexte'       => $validated['contexte'] ?? null,
                'image_path'     => $imagePath,
                'audio_path'     => $audioPath,
                'mots_min'       => $validated['mots_min'] ?? null,
                'points'         => $validated['points'] ?? 1,
                'duree_secondes' => $validated['duree_secondes'] ?? 60,
                'explication'    => $validated['explication'] ?? null,
                'ordre'          => $serie->questions()->count(),
            ]);

            if (!$discipline->reponse_libre) {
                foreach ($validated['reponses'] as $index => $texte) {
                    LangueReponse::create([
                        'question_id' => $question->id,
                        'texte'       => $texte,
                        'correcte'    => (int) $validated['correcte'] === $index,
                        'ordre'       => $index,
                    ]);
                }
            }

            $serie->update(['nombre_questions' => $serie->questions()->count()]);
        });

        return back()->with('success', 'Question ajoutée avec succès.');
    }

    // ══════════════════════════════════════
    //  QUESTIONS — Modifier
    // ══════════════════════════════════════
    public function updateQuestion(Request $request, LangueQuestion $question)
    {
        $this->checkAccess();

        $discipline = $question->serie->discipline;

        $rules = [
            'enonce'         => 'required|string|max:1000',
            'contexte'       => 'nullable|string|max:2000',
            'explication'    => 'nullable|string|max:1000',
            'points'         => 'nullable|integer|min:1|max:10',
            'duree_secondes' => 'nullable|integer|min:10|max:1800',
        ];

        if ($discipline->has_image) {
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096';
            $rules['supprimer_image'] = 'nullable|boolean';
        }
        if ($discipline->has_audio) {
            $rules['audio'] = 'nullable|file|mimes:mp3,wav,ogg,m4a|max:20480';
            $rules['supprimer_audio'] = 'nullable|boolean';
        }

        if ($discipline->reponse_libre) {
            $rules['mots_min'] = 'nullable|integer|min:10|max:2000';
        } else {
            $rules['reponses']            = 'required|array|min:2|max:6';
            $rules['reponses.*.id']       = 'nullable|exists:langue_reponses,id';
            $rules['reponses.*.texte']    = 'required|string|max:500';
            $rules['correcte']            = 'required|integer';
        }

        $validated = $request->validate($rules);

        DB::transaction(function () use ($validated, $request, $question, $discipline) {
            $imagePath = $question->image_path;
            $audioPath = $question->audio_path;

            if ($discipline->has_image) {
                if ($request->boolean('supprimer_image') && $imagePath) {
                    Storage::disk('public')->delete($imagePath);
                    $imagePath = null;
                }
                if ($request->hasFile('image')) {
                    if ($imagePath) Storage::disk('public')->delete($imagePath);
                    $imagePath = $request->file('image')->store('questions/images', 'public');
                }
            }

            if ($discipline->has_audio) {
                if ($request->boolean('supprimer_audio') && $audioPath) {
                    Storage::disk('public')->delete($audioPath);
                    $audioPath = null;
                }
                if ($request->hasFile('audio')) {
                    if ($audioPath) Storage::disk('public')->delete($audioPath);
                    $audioPath = $request->file('audio')->store('questions/audio', 'public');
                }
            }

            $question->update([
                'enonce'         => $validated['enonce'],
                'contexte'       => $validated['contexte'] ?? null,
                'image_path'     => $imagePath,
                'audio_path'     => $audioPath,
                'mots_min'       => $validated['mots_min'] ?? null,
                'points'         => $validated['points'] ?? 1,
                'duree_secondes' => $validated['duree_secondes'] ?? 60,
                'explication'    => $validated['explication'] ?? null,
            ]);

            if (!$discipline->reponse_libre) {
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
            }
        });

        return back()->with('success', 'Question mise à jour.');
    }

    public function destroyQuestion(LangueQuestion $question)
    {
        $this->checkAccess();

        if ($question->image_path) Storage::disk('public')->delete($question->image_path);
        if ($question->audio_path) Storage::disk('public')->delete($question->audio_path);

        $serie = $question->serie;
        $question->reponses()->delete();
        $question->delete();
        $serie->update(['nombre_questions' => $serie->questions()->count()]);

        return back()->with('success', 'Question supprimée.');
    }
}