<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TcfPassageReponse extends Model
{
    protected $table = 'tcf_passage_reponses';
    protected $fillable = ['passage_id','question_id','reponse_id','est_correcte'];
    protected $casts = ['est_correcte' => 'boolean'];
}