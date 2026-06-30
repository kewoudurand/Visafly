<?php

namespace App\Observers;

use App\Models\Course; // Adapter le nom du modèle
use App\Services\NotificationService;

class CourseObserver
{
    /**
     * Déclenché quand un cours est créé
     */
    public function created(Course $cours)
    {
        // Notifier l'instructeur
        NotificationService::courseCreated(
            $cours->instructor, // Adapter selon votre modèle
            $cours->titre,       // Adapter selon vos colonnes
            $cours->id
        );
    }
}