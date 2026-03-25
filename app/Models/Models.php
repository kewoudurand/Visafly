<?php
// ═══════════════════════════════════════════════════
//  app/Models/TcfSerie.php
// ═══════════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TcfSerie extends Model
{
    protected $table = 'tcf_series';
    protected $fillable = ['nom', 'code', 'type', 'gratuit', 'ordre', 'actif'];
    protected $casts = ['gratuit' => 'boolean', 'actif' => 'boolean'];

    public function disciplines(): HasMany
    {
        return $this->hasMany(TcfDiscipline::class, 'serie_id');
    }

    // Nombre de passages gratuits utilisés par l'user courant
    public static function passagesGratuits(int $userId): int
    {
        return TcfPassage::whereHas('discipline.serie', fn($q) => $q->where('gratuit', true))
            ->where('user_id', $userId)
            ->distinct('discipline_id')
            ->count('discipline_id');
    }
}


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


// ═══════════════════════════════════════════════════
//  app/Models/TcfQuestion.php
// ═══════════════════════════════════════════════════
class TcfQuestion extends Model
{
    protected $table = 'tcf_questions';
    protected $fillable = ['discipline_id','numero','consigne','type_support','fichier_support','enonce'];

    public function discipline() { return $this->belongsTo(TcfDiscipline::class); }
    public function reponses(): HasMany { return $this->hasMany(TcfReponse::class, 'question_id'); }
}


// ═══════════════════════════════════════════════════
//  app/Models/TcfReponse.php
// ═══════════════════════════════════════════════════
class TcfReponse extends Model
{
    protected $table = 'tcf_reponses';
    protected $fillable = ['question_id','lettre','texte','est_correcte'];
    protected $casts = ['est_correcte' => 'boolean'];

    public function question() { return $this->belongsTo(TcfQuestion::class); }
}


// ═══════════════════════════════════════════════════
//  app/Models/TcfPassage.php
// ═══════════════════════════════════════════════════
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


// ═══════════════════════════════════════════════════
//  app/Models/TcfPassageReponse.php
// ═══════════════════════════════════════════════════
class TcfPassageReponse extends Model
{
    protected $table = 'tcf_passage_reponses';
    protected $fillable = ['passage_id','question_id','reponse_id','est_correcte'];
    protected $casts = ['est_correcte' => 'boolean'];
}


// ═══════════════════════════════════════════════════
//  app/Models/TcfAbonnement.php
// ═══════════════════════════════════════════════════
class TcfAbonnement extends Model
{
    protected $table = 'tcf_abonnements';
    protected $fillable = ['user_id','forfait','montant','devise','debut_at','fin_at','actif','reference_paiement'];
    protected $casts = ['debut_at' => 'datetime', 'fin_at' => 'datetime', 'actif' => 'boolean'];

    public function user() { return $this->belongsTo(User::class); }

    public static function userActif(int $userId): bool
    {
        return static::where('user_id', $userId)
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->exists();
    }
}
