<?php
// ═══════════════════════════════════════════════════
//  app/Http/Controllers/Admin/AnalyticsController.php
// ═══════════════════════════════════════════════════
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TcfPassage;
use App\Models\TcfAbonnement;
use App\Models\Consultation;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('view analytics'), 403);

        $now = now();

        // ══ UTILISATEURS ══
        $users = [
            'total'        => User::count(),
            'ce_mois'      => User::whereMonth('created_at', $now->month)
                                  ->whereYear('created_at', $now->year)->count(),
            'mois_dernier' => User::whereMonth('created_at', $now->copy()->subMonth()->month)
                                  ->whereYear('created_at', $now->copy()->subMonth()->year)->count(),
            // Nouveaux par mois (6 derniers mois)
            'par_mois'     => User::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
                                  ->where('created_at', '>=', $now->copy()->subMonths(6))
                                  ->groupBy('mois')->orderBy('mois')->get(),
            // Par rôle
            'par_role'     => DB::table('model_has_roles')
                                 ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                                 ->selectRaw('roles.name as role, COUNT(*) as total')
                                 ->groupBy('roles.name')->get(),
        ];

        // ══ ÉPREUVES TCF ══
        $tests = [
            'total'         => TcfPassage::count(),
            'terminees'     => TcfPassage::where('statut', 'termine')->count(),
            'en_cours'      => TcfPassage::where('statut', 'en_cours')->count(),
            'score_moyen'   => (int) round(TcfPassage::where('statut', 'termine')->avg('score') ?? 0),
            'ce_mois'       => TcfPassage::whereMonth('created_at', $now->month)
                                         ->whereYear('created_at', $now->year)->count(),
            // Scores par tranche
            'par_score'     => [
                '0-20'  => TcfPassage::where('statut','termine')->whereBetween('score',[0,20])->count(),
                '21-40' => TcfPassage::where('statut','termine')->whereBetween('score',[21,40])->count(),
                '41-60' => TcfPassage::where('statut','termine')->whereBetween('score',[41,60])->count(),
                '61-80' => TcfPassage::where('statut','termine')->whereBetween('score',[61,80])->count(),
                '81-100'=> TcfPassage::where('statut','termine')->whereBetween('score',[81,100])->count(),
            ],
            // Par mois
            'par_mois'      => TcfPassage::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
                                         ->where('created_at', '>=', $now->copy()->subMonths(6))
                                         ->groupBy('mois')->orderBy('mois')->get(),
        ];

        // ══ CONSULTATIONS ══
        $consultations = [
            'total'       => Consultation::count(),
            'en_attente'  => Consultation::where('status', 'en_attente')->count(),
            'approuvees'  => Consultation::where('status', 'approuvee')->count(),
            'declinee'    => Consultation::where('status', 'declinee')->count(),
            'terminee'    => Consultation::where('status', 'terminee')->count(),
            'ce_mois'     => Consultation::whereMonth('created_at', $now->month)
                                         ->whereYear('created_at', $now->year)->count(),
            // Par type
            // 'par_type'    => Consultation::selectRaw('type, COUNT(*) as total')
            //                              ->groupBy('type')->get(),
            // Par mois
            'par_mois'    => Consultation::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
                                         ->where('created_at', '>=', $now->copy()->subMonths(6))
                                         ->groupBy('mois')->orderBy('mois')->get(),
        ];

        // ══ REVENUS ABONNEMENTS ══
        $revenus = [
            'total'        => TcfAbonnement::sum('montant'),
            'ce_mois'      => TcfAbonnement::whereMonth('created_at', $now->month)
                                            ->whereYear('created_at', $now->year)->sum('montant'),
            'actifs'       => TcfAbonnement::where('actif', true)
                                           ->where('fin_at', '>=', $now)->count(),
            'par_forfait'  => TcfAbonnement::selectRaw('forfait, COUNT(*) as total, SUM(montant) as revenus')
                                           ->groupBy('forfait')->get(),
            'par_mois'     => TcfAbonnement::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, SUM(montant) as total")
                                           ->where('created_at', '>=', $now->copy()->subMonths(6))
                                           ->groupBy('mois')->orderBy('mois')->get(),
        ];

        return view('admin.analytics.index',
            compact('users', 'tests', 'consultations', 'revenus'));
    }
}