<?php
// app/Models/LessonProgression.php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class LessonProgression extends Model
{
    protected $fillable = [
        'user_id', 'lesson_id', 'cours_id', 'statut',
        'score', 'bonnes_reponses', 'total_questions',
        'tentatives', 'points_gagnes', 'reponses_etudiant',
        'commencee_le', 'terminee_le',
    ];
 
    protected $casts = [
        'reponses_etudiant' => 'array',
        'commencee_le'      => 'datetime',
        'terminee_le'       => 'datetime',
    ];
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
 
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'cours_id');
    }
 
    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }
 
    /** Label score lisible "8/10 (80%)" */
    public function scoreLabel(): string
    {
        return "{$this->bonnes_reponses}/{$this->total_questions} ({$this->score}%)";
    }
}