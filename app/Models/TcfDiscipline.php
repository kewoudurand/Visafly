<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// ═══════════════════════════════════════════════════
//  app/Models/TcfDiscipline.php
// ═══════════════════════════════════════════════════
class TcfDiscipline extends Model
{
    protected $table = 'tcf_disciplines';
    protected $fillable = ['serie_id','nom','code','icone','duree_minutes','nb_questions','type_questions','actif'];

    public function serie() { return $this->belongsTo(TcfSerie::class, 'serie_id'); }
    public function questions(): HasMany { return $this->hasMany(TcfQuestion::class, 'discipline_id')->orderBy('numero'); }
    public function passages(): HasMany { return $this->hasMany(TcfPassage::class, 'discipline_id'); }
}