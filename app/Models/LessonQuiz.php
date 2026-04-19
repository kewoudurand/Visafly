<?php
// ═══════════════════════════════════════════════════════════════
//  app/Models/LessonQuiz.php
// ═══════════════════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonQuiz extends Model
{
    protected $table = 'lesson_quizzes';

    protected $fillable = [
        'lesson_id', 'question', 'options', 'bonne_reponse', 'explication', 'ordre',
    ];

    protected $casts = ['options' => 'array'];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}