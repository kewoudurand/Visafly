<?php
// app/Models/CoursProgres.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursProgres extends Model
{
    protected $table = 'cours_progres';

    protected $fillable = [
        'user_id', 'lesson_id', 'cours_id',
        'statut', 'score', 'points_gagnes',
        'commence_at', 'termine_at',
    ];

    protected $casts = [
        'commence_at' => 'datetime',
        'termine_at'  => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lecon_id');
    }

    public function cours(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'cours_id');
    }
}