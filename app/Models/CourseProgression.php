<?php
// app/Models/CourseProgression.php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class CourseProgression extends Model
{
    protected $fillable = [
        'user_id', 'lesson_id','cours_id', 'lecons_terminees', 'total_lecons',
        'pourcentage', 'points_total', 'termine', 'terminee_le',
    ];
 
    protected $casts = [
        'termine'     => 'boolean',
        'terminee_le' => 'datetime',
    ];
 
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
 
    public function cours(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'cours_id');
    }
 
    /** Recalcule et sauvegarde le pourcentage */
    public function recalculer(): void
    {
        if ($this->total_lecons > 0) {
            $this->pourcentage = intval(($this->lecons_terminees / $this->total_lecons) * 100);
        }
        $this->termine     = ($this->pourcentage >= 100);
        $this->terminee_le = $this->termine ? now() : null;
        $this->save();
    }
 
    /**
     * Crée ou met à jour la progression de cours après la fin d'une leçon.
     * À appeler depuis LessonController::soumettre()
     */
    public static function mettreAJour(int $userId, int $coursId, int $pointsGagnes): void
    {
        $totalLecons = Lesson::where('cours_id', $coursId)->where('publiee', true)->count();
        $leconsTerminees = LessonProgression::where('user_id', $userId)
            ->where('cours_id', $coursId)
            ->where('statut', 'terminee')
            ->count();
 
        $prog = static::firstOrCreate(
            ['user_id' => $userId, 'cours_id' => $coursId],
            ['total_lecons' => $totalLecons]
        );
 
        $prog->total_lecons     = $totalLecons;
        $prog->lecons_terminees = $leconsTerminees;
        $prog->points_total     += $pointsGagnes;
        $prog->recalculer();
    }
}