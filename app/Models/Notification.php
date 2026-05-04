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
}