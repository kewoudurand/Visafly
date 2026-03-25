<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TcfPassage extends Model
{
    protected $table = 'tcf_passages';
    protected $fillable = ['user_id','discipline_id','debut_at','fin_at','score','nb_correctes','temps_utilise','statut'];
    protected $casts = ['debut_at' => 'datetime', 'fin_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function discipline() { return $this->belongsTo(TcfDiscipline::class); }
    public function passageReponses() { return $this->hasMany(TcfPassageReponse::class, 'passage_id'); }

    public function questionIds(): array
    {
        return $this->passageReponses->pluck('question_id')->toArray();
    }
}