<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TcfReponse extends Model
{
    protected $table = 'tcf_reponses';
    protected $fillable = ['question_id','lettre','texte','est_correcte'];
    protected $casts = ['est_correcte' => 'boolean'];

    public function question() { return $this->belongsTo(TcfQuestion::class); }
}