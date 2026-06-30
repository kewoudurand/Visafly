<?php

namespace App\Observers;

use App\Models\Lesson;
use App\Services\NotificationService;

class LessonObserver
{
    /**
     * Déclenché quand une leçon est créée
     */
    public function created(Lesson $lesson)
    {
        $cours = $lesson->cours;

        // PROTECTION : On vérifie que le cours existe et possède un instructeur
        if ($cours && $cours->instructor instanceof \App\Models\User) {
            NotificationService::lessonCreated(
                $cours->instructor,
                $lesson->titre,
                $cours->titre,
                $cours->id
            );
        }
    }
}