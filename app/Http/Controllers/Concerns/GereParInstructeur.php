<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

/**
 * Trait GereParInstructeur
 *
 * Regroupe toute la logique métier (validation, slug, upload audio,
 * construction du tableau de données) partagée entre :
 *   - Admin\LessonController   (voit tout, pas de filtre propriétaire)
 *   - Admin\CourseController
 *   - Instructeur\LessonController   (filtré sur instructeur_id = auth->id)
 *   - Instructeur\CourseController
 *
 * Les controllers incluent ce trait et appellent les méthodes préfixées
 * par "construire" ou "reglesValidation".
 */
trait GereParInstructeur
{
    // ══════════════════════════════════════════════════════════════
    // Règles de validation communes
    // ══════════════════════════════════════════════════════════════

    protected function reglesLecon(): array
    {
        return [
            'titre'                   => 'required|string|max:255',
            'type'                    => 'required|in:vocabulaire,dialogue,grammaire,audio,lecture',
            'contenu'                 => 'nullable|string',
            'gratuite'                => 'boolean',
            'publiee'                 => 'boolean',
            'ordre'                   => 'nullable|integer|min:0',
            'points_recompense'       => 'nullable|integer|min:0|max:100',
            'duree_estimee_minutes'   => 'nullable|integer|min:1',
            'fichier_audio'           => 'nullable|file|mimes:mp3,wav,ogg,m4a|max:51200',
            'transcription_audio'     => 'nullable|string',
            'mots'                    => 'nullable|array',
            'mots.*.de'               => 'required_with:mots|string|max:200',
            'mots.*.fr'               => 'required_with:mots|string|max:200',
            'mots.*.phonetique'       => 'nullable|string|max:200',
            'mots.*.exemple'          => 'nullable|string|max:500',
            'exercices'               => 'nullable|array',
            'exercices.*.question'    => 'required_with:exercices|string|max:500',
            'exercices.*.type'        => 'required_with:exercices|in:qcm,texte_libre',
            'exercices.*.choix'       => 'nullable|array',
            'exercices.*.choix.*'     => 'nullable|string|max:200',
            'exercices.*.reponse'     => 'required_with:exercices|string|max:300',
            'exercices.*.explication' => 'nullable|string|max:600',
        ];
    }

    protected function reglesCours(): array
    {
        return [
            'titre'                  => 'required|string|max:255',
            'sous_titre'             => 'nullable|string|max:255',
            'description'            => 'nullable|string',
            'niveau'                 => 'required|in:A1,A2,B1,B2,C1,C2',
            'couleur'                => 'nullable|string|max:20',
            'icone'                  => 'nullable|string|max:60',
            'duree_estimee_minutes'  => 'nullable|integer|min:1',
            'gratuit'                => 'boolean',
            'publie'                 => 'boolean',
            'ordre'                  => 'nullable|integer|min:0',
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // Construction des données leçon à partir d'une requête validée
    // ══════════════════════════════════════════════════════════════

    /**
     * Retourne le tableau prêt pour Lesson::create() / $lesson->update().
     *
     * @param  Request  $request       Requête validée
     * @param  array    $validated     Résultat de $request->validate()
     * @param  Course   $cours
     * @param  Lesson|null $lecon      null = création, instance = mise à jour
     * @param  int      $instructeurId ID de l'instructeur à enregistrer
     */
    protected function construireDataLecon(
        Request $request,
        array   $validated,
        Course  $cours,
        ?Lesson $lecon,
        int     $instructeurId
    ): array {
        // Slug unique (uniquement à la création)
        $slug = $lecon?->slug ?? $this->genererSlug($cours, $validated['titre']);

        // Upload audio
        $audioPath = $lecon?->fichier_audio;
        if ($request->hasFile('fichier_audio')) {
            if ($audioPath) Storage::disk('public')->delete($audioPath);
            $audioPath = $request->file('fichier_audio')
                ->store('lessons/audio/' . $cours->id, 'public');
        }

        // Nettoyage des choix vides dans les exercices
        $exercices = collect($validated['exercices'] ?? [])->map(function ($ex) {
            $ex['choix'] = array_values(array_filter($ex['choix'] ?? []));
            return $ex;
        })->toArray();

        return [
            'cours_id'              => $cours->id,
            'instructor_id'        => $instructeurId,
            'titre'                 => $validated['titre'],
            'slug'                  => $slug,
            'type'                  => $validated['type'],
            'contenu'               => $validated['contenu'] ?? null,
            'mots'                  => $validated['mots'] ?? [],
            'exercices'             => $exercices,
            'fichier_audio'         => $audioPath,
            'transcription_audio'   => $validated['transcription_audio'] ?? null,
            'gratuite'              => $request->boolean('gratuite'),
            'publiee'               => $request->boolean('publiee', true),
            'ordre'                 => $validated['ordre']
                                        ?? ($lecon?->ordre
                                            ?? Lesson::where('cours_id', $cours->id)->max('ordre') + 1),
            'points_recompense'     => $validated['points_recompense'] ?? 10,
            'duree_estimee_minutes' => $validated['duree_estimee_minutes'] ?? null,
        ];
    }

    /**
     * Retourne le tableau prêt pour Course::create() / $cours->update().
     */
    protected function construireDataCours(
        Request $request,
        array   $validated,
        int     $instructeurId,
        ?Course $cours = null
    ): array {
        $slug = $cours?->slug ?? $this->genererSlugCours($validated['titre'], $validated['niveau']);

        return [
            'instructor_id'        => $instructeurId,
            'titre'                 => $validated['titre'],
            'slug'                  => $slug,
            'sous_titre'            => $validated['sous_titre'] ?? null,
            'description'           => $validated['description'] ?? null,
            'niveau'                => $validated['niveau'],
            'couleur'               => $validated['couleur'] ?? '#1B3A6B',
            'icone'                 => $validated['icone'] ?? 'bi-book',
            'duree_estimee_minutes' => $validated['duree_estimee_minutes'] ?? null,
            'gratuit'               => $request->boolean('gratuit'),
            'publie'                => $request->boolean('publie', true),
            'ordre'                 => $validated['ordre'] ?? (Course::max('ordre') + 1),
        ];
    }

    // ══════════════════════════════════════════════════════════════
    // Génération de slug unique
    // ══════════════════════════════════════════════════════════════

    private function genererSlug(Course $cours, string $titre): string
    {
        $base = Str::slug(($cours->niveau ?? '') . '-' . $titre);
        $slug = $base;
        $i    = 1;
        while (Lesson::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }

    private function genererSlugCours(string $titre, string $niveau): string
    {
        $base = Str::slug('allemand-' . strtolower($niveau) . '-' . $titre);
        $slug = $base;
        $i    = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}