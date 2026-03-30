<?php
// ─────────────────────────────────────────────────────────────
//  app/Models/PlanAbonnement.php
// ─────────────────────────────────────────────────────────────
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAbonnement extends Model
{
    protected $table = 'plans_abonnements';

    protected $fillable = [
        'nom','code','couleur','icone','description',
        'prix','devise','duree_jours','points',
        'populaire','actif','ordre',
    ];

    protected $casts = [
        'points'   => 'array',
        'populaire'=> 'boolean',
        'actif'    => 'boolean',
    ];

    public function abonnements()
    {
        return $this->hasMany(\App\Models\TcfAbonnement::class, 'plan_id');
    }

    public function prixFormate(): string
    {
        return number_format($this->prix, 0, ',', ' ') . ' ' . $this->devise;
    }
}

