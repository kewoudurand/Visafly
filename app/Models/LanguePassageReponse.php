<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LanguePassageReponse extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'langue_passage_reponses';
 
    protected $fillable = [
        'passage_id', 'question_id', 'reponse_id', 'correcte',
    ];
 
    protected $casts = ['correcte' => 'boolean'];
 
    public function passage()
    {
        return $this->belongsTo(LanguePassage::class, 'passage_id');
    }
 
    public function question()
    {
        return $this->belongsTo(LangueQuestion::class, 'question_id');
    }
 
    public function reponse()
    {
        return $this->belongsTo(LangueReponse::class, 'reponse_id');
    }
}