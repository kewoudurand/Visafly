<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TcfQuestion extends Model
{
    protected $table = 'tcf_questions';
    protected $fillable = ['discipline_id','numero','consigne','type_support','fichier_support','enonce'];

    public function discipline() { return $this->belongsTo(TcfDiscipline::class); }
    public function reponses(): HasMany { return $this->hasMany(TcfReponse::class, 'question_id'); }
}