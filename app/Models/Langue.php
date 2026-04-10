<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\Course;

class Langue extends Model
{
    protected $table = 'langues';

    protected $fillable = [
        'code', 'nom', 'organisme', 'description',
        'logo', 'couleur', 'actif', 'ordre',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    // ── Relations ──

    public function disciplines(): HasMany
    {
        return $this->hasMany(LangueDiscipline::class, 'langue_id')->orderBy('ordre');
    }

    public function series(): HasManyThrough
    {
        return $this->hasManyThrough(
            LangueSerie::class,
            LangueDiscipline::class,
            'langue_id',      // FK sur langue_disciplines
            'discipline_id',  // FK sur langue_series
        );
    }

        public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // ── Helpers ──

    public function couleurText(): string
    {
        // Retourne une couleur de texte contrastée selon la couleur de fond
        return in_array($this->code, ['tcf', 'tef']) ? '#1B3A6B' : '#fff';
    }
}