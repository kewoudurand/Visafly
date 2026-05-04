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
        $cours = $lesson->cours; // Adapter selon votre relation
        
        // Notifier l'instructeur
        NotificationService::lessonCreated(
            $cours->instructeur,
            $lesson->titre,
            $cours->titre,
            $cours->id
        );
    }
}