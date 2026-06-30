<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'icon',
        'data',
        'action_url',
        'action_label',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'json',
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ Marquer comme lue
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
        return $this;
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRecent($query)
    {
        return $query->latest('created_at');
    }

    // Obtenir l'icône Bootstrap
    public function getIconClass()
    {
        return match($this->icon) {
            'check' => 'bi-check-circle-fill text-success',
            'warning' => 'bi-exclamation-circle-fill text-warning',
            'error' => 'bi-x-circle-fill text-danger',
            'info' => 'bi-info-circle-fill text-info',
            'star' => 'bi-star-fill text-warning',
            'people' => 'bi-people-fill text-primary',
            default => 'bi-bell-fill text-primary',
        };
    }

    // Obtenir la couleur par type
    public function getTypeColor()
    {
        return match($this->type) {
            'withdrawal_initiated' => '#FFA726',      // Orange
            'withdrawal_approved' => '#4CAF50',       // Vert
            'withdrawal_rejected' => '#f44336',       // Rouge
            'affiliation_completed' => '#4CAF50',     // Vert
            'commission_earned' => '#4CAF50',         // Vert
            'course_created' => '#2196F3',            // Bleu
            'lesson_created' => '#2196F3',            // Bleu
            'new_student' => '#9C27B0',               // Violet
            'system' => '#1B3A6B',                    // Bleu marine
            default => '#999',
        };
    }

    // Obtenir l'emoji
    public function getEmoji()
    {
        return match($this->type) {
            'withdrawal_initiated' => '⏳',
            'withdrawal_approved' => '✅',
            'withdrawal_rejected' => '❌',
            'affiliation_completed' => '👥',
            'commission_earned' => '💰',
            'course_created' => '📚',
            'lesson_created' => '📖',
            'new_student' => '🎉',
            'system' => 'ℹ️',
            default => '📢',
        };
    }

    /**
     * Crée et enregistre une notification liée à une consultation.
     *
     * @param Consultation $consultation L'objet de la consultation concernée
     * @param string $type Le type d'événement (ex: rdv_programme, rdv_annule)
     * @param string $title Le titre visible par l’utilisateur
     * @param string $message Le corps du message de notification
     * @param array|null $data Métadonnées optionnelles (ex: ['screen' => 'rdv'])
     * @param string|null $actionUrl Lien d'action optionnel (ex: lien Google Meet)
     * @param string|null $actionLabel Libellé du bouton d'action
     * @return self
     */
    public static function consultation(
        Consultation $consultation,
        string       $type,
        string       $title,
        string       $message,
        ?array       $data = null,
        ?string      $actionUrl = null,
        ?string      $actionLabel = null
    ): self {
        return static::create([
            'consultation_id' => $consultation->id,
            'user_id'         => $consultation->user_id, // L'étudiant/client destinataire
            'type'            => $type,
            'title'           => $title,
            'message'         => $message,
            'data'            => $data, // Assure-toi que 'data' est casté en 'array' dans ton modèle
            'action_url'      => $actionUrl,
            'action_label'    => $actionLabel,
            'lu_le'           => null, // Non lue par défaut
        ]);
    }
}