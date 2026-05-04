<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    /**
     * 💳 Retrait initié
     */
    public static function withdrawalInitiated(User $user, $amount, $method)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_initiated',
            'title' => '⏳ Demande de Retrait Soumise',
            'message' => 'Votre demande de retrait de ' . number_format($amount, 0) . ' F a été soumise. L\'admin va l\'examiner dans les 24-48h.',
            'icon' => 'info',
            'data' => [
                'amount' => $amount,
                'method' => $method,
            ],
            'action_url' => route('affiliate.withdraw.history'),
            'action_label' => 'Voir le détail',
        ]);
    }

    /**
     * ✅ Retrait approuvé
     */
    public static function withdrawalApproved(User $user, $amount, $method)
    {
        $methodLabel = self::getMethodLabel($method);

        return Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_approved',
            'title' => '✅ Retrait Approuvé!',
            'message' => 'Votre retrait de ' . number_format($amount, 0) . ' F via ' . $methodLabel . ' a été approuvé!',
            'icon' => 'check',
            'data' => [
                'amount' => $amount,
                'method' => $method,
            ],
            'action_url' => route('affiliate.withdraw.history'),
            'action_label' => 'Voir l\'historique',
        ]);
    }

    /**
     * ❌ Retrait rejeté
     */
    public static function withdrawalRejected(User $user, $amount, $reason)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'withdrawal_rejected',
            'title' => '❌ Retrait Rejeté',
            'message' => 'Votre retrait de ' . number_format($amount, 0) . ' F a été rejeté. Raison: ' . $reason,
            'icon' => 'error',
            'data' => [
                'amount' => $amount,
                'reason' => $reason,
            ],
            'action_url' => route('affiliate.withdraw.show-form'),
            'action_label' => 'Soumettre une nouvelle demande',
        ]);
    }

    /**
     * 👥 Affiliation complétée
     */
    public static function affiliationCompleted(User $referrer, User $referred, $commission)
    {
        return Notification::create([
            'user_id' => $referrer->id,
            'type' => 'affiliation_completed',
            'title' => '👥 Parrainage Validé!',
            'message' => $referred->first_name . ' a complété une action. Vous avez gagné ' . number_format($commission, 0) . ' F!',
            'icon' => 'people',
            'data' => [
                'referred_id' => $referred->id,
                'referred_name' => $referred->first_name,
                'commission' => $commission,
            ],
            'action_url' => route('affiliate.dashboard'),
            'action_label' => 'Voir mon Dashboard',
        ]);
    }

    /**
     * 💰 Commission gagnée
     */
    public static function commissionEarned(User $referrer, User $referred, $commission)
    {
        return Notification::create([
            'user_id' => $referrer->id,
            'type' => 'commission_earned',
            'title' => '💰 Commission Gagnée!',
            'message' => 'Nouvelle commission de ' . number_format($commission, 0) . ' F de ' . $referred->first_name . '!',
            'icon' => 'star',
            'data' => [
                'referred_id' => $referred->id,
                'commission' => $commission,
            ],
            'action_url' => route('affiliate.dashboard'),
            'action_label' => 'Voir mes gains',
        ]);
    }

    /**
     * 📚 Nouveau cours créé
     */
    public static function courseCreated(User $instructor, $courseTitle, $courseId)
    {
        // Notifier l'instructeur
        return Notification::create([
            'user_id' => $instructor->id,
            'type' => 'course_created',
            'title' => '📚 Nouveau Cours Créé',
            'message' => 'Votre cours "' . $courseTitle . '" a été créé avec succès!',
            'icon' => 'star',
            'data' => [
                'course_id' => $courseId,
                'course_title' => $courseTitle,
            ],
            'action_url' => route('instructeur.courses.edit', $courseId),
            'action_label' => 'Gérer le cours',
        ]);
    }

    /**
     * 📖 Nouvelle leçon créée
     */
    public static function lessonCreated(User $instructor, $lessonTitle, $courseTitle, $courseId)
    {
        return Notification::create([
            'user_id' => $instructor->id,
            'type' => 'lesson_created',
            'title' => '📖 Nouvelle Leçon Créée',
            'message' => 'La leçon "' . $lessonTitle . '" du cours "' . $courseTitle . '" a été créée!',
            'icon' => 'star',
            'data' => [
                'course_id' => $courseId,
                'lesson_title' => $lessonTitle,
            ],
            'action_url' => route('instructeur.courses.lessons', $courseId),
            'action_label' => 'Voir les leçons',
        ]);
    }

    /**
     * 🎉 Nouvel étudiant inscrit via code parrainage
     */
    public static function newStudentViaReferral(User $referrer, User $newStudent)
    {
        return Notification::create([
            'user_id' => $referrer->id,
            'type' => 'new_student',
            'title' => '🎉 Nouvel Étudiant!',
            'message' => $newStudent->first_name . ' s\'est inscrit avec votre code de parrainage!',
            'icon' => 'people',
            'data' => [
                'student_id' => $newStudent->id,
                'student_name' => $newStudent->first_name,
            ],
            'action_url' => route('affiliate.dashboard'),
            'action_label' => 'Voir mes affiliés',
        ]);
    }

    /**
     * 📣 Notification système
     */
    public static function system(User $user, $title, $message, $actionUrl = null, $actionLabel = null)
    {
        return Notification::create([
            'user_id' => $user->id,
            'type' => 'system',
            'title' => $title,
            'message' => $message,
            'icon' => 'info',
            'action_url' => $actionUrl,
            'action_label' => $actionLabel ?? 'En savoir plus',
        ]);
    }

    /**
     * Helper pour les labels de moyen de paiement
     */
    private static function getMethodLabel($method)
    {
        return match($method) {
            'orange_money' => '🟠 Orange Money',
            'mtn' => '🔴 MTN Mobile Money',
            'bank_transfer' => '🏦 Virement Bancaire',
            default => '❓ Autre',
        };
    }
}