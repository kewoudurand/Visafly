<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Langue;
use App\Models\LanguePassage;
use App\Models\LangueAbonnement;
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
            'total'    => User::count(),
            'ce_mois'  => User::whereMonth('created_at', $now->month)
                             ->whereYear('created_at', $now->year)->count(),
            'par_role' => DB::table('model_has_roles')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->selectRaw('roles.name as role, COUNT(*) as total')
                ->groupBy('roles.name')->get(),
            'par_mois' => User::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
                ->where('created_at', '>=', $now->copy()->subMonths(6))
                ->groupBy('mois')->orderBy('mois')->get(),
        ];

        // ══ TESTS LANGUE ══
        $tests = [
            'total'     => LanguePassage::count(),
            'terminees' => LanguePassage::where('statut', 'termine')->count(),
            'en_cours'  => LanguePassage::where('statut', 'en_cours')->count(),
            'score_moyen' => (int) round(
                LanguePassage::where('statut', 'termine')->avg('score') ?? 0
            ),
            'ce_mois'   => LanguePassage::whereMonth('created_at', now()->month)
                                        ->whereYear('created_at', now()->year)->count(),
            'par_score' => [
                '0-20'  => LanguePassage::where('statut','termine')->whereBetween('score',[0,20])->count(),
                '21-40' => LanguePassage::where('statut','termine')->whereBetween('score',[21,40])->count(),
                '41-60' => LanguePassage::where('statut','termine')->whereBetween('score',[41,60])->count(),
                '61-80' => LanguePassage::where('statut','termine')->whereBetween('score',[61,80])->count(),
                '81-100'=> LanguePassage::where('statut','termine')->whereBetween('score',[81,100])->count(),
            ],
            'par_mois'  => LanguePassage::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
                ->where('created_at', '>=', now()->subMonths(6))
                ->groupBy('mois')->orderBy('mois')->get(),
            
            'par_langue' => LanguePassage::selectRaw('langues.id, langues.code, langues.nom, langues.couleur, COUNT(*) as total')
                ->leftJoin('langues', 'langue_passages.langue_id', '=', 'langues.id')
                ->groupBy('langues.id', 'langues.code', 'langues.nom', 'langues.couleur')
                ->havingRaw('COUNT(*) > 0')
                ->get(),
            
            'derniers_passages' => LanguePassage::with([
                'user:id,first_name,last_name,avatar,email',
                'serie:id,titre',
                'discipline:id,nom,code',
                'langue:id,code,nom,couleur'
            ])
                ->where('statut', 'termine')
                ->latest('created_at')
                ->limit(10)
                ->get(),
        ];

        // ══ CONSULTATIONS ══
        $consultations = [
            'total'      => Consultation::count(),
            'en_attente' => Consultation::where('status', 'en_attente')->count(),
            'approuvees' => Consultation::where('status', 'approuvee')->count(),
            'declinee'   => Consultation::where('status', 'declinee')->count(),
            'terminee'   => Consultation::where('status', 'terminee')->count(),
            'par_mois'   => Consultation::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
                ->where('created_at', '>=', $now->copy()->subMonths(6))
                ->groupBy('mois')->orderBy('mois')->get(),
        ];

        // ══ REVENUS ══
        $revenus = [
            'total'       => (float) LangueAbonnement::where('statut_paiement', 'confirme')
                                             ->sum('montant') ?? 0,
            'ce_mois'     => (float) LangueAbonnement::whereMonth('created_at', $now->month)
                                             ->whereYear('created_at', $now->year)
                                             ->where('statut_paiement', 'confirme')
                                             ->sum('montant') ?? 0,
            'actifs'      => LangueAbonnement::where('actif', true)
                                             ->where('fin_at', '>=', $now)
                                             ->count(),
            'par_plan'    => LangueAbonnement::selectRaw('plans_abonnements.id, plans_abonnements.nom, plans_abonnements.code, COUNT(*) as total, COALESCE(SUM(langue_abonnements.montant), 0) as revenus')
                                             ->leftJoin('plans_abonnements', 'langue_abonnements.plan_id', '=', 'plans_abonnements.id')
                                             ->where('langue_abonnements.statut_paiement', 'confirme')
                                             ->groupBy('plans_abonnements.id', 'plans_abonnements.nom', 'plans_abonnements.code')
                                             ->get(),
            'par_mois'    => LangueAbonnement::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COALESCE(SUM(montant), 0) as total")
                                             ->where('created_at', '>=', $now->copy()->subMonths(6))
                                             ->where('statut_paiement', 'confirme')
                                             ->groupBy('mois')
                                             ->orderBy('mois')
                                             ->get(),
        ];

        return view('admin.analytics.index',
            compact('users', 'tests', 'consultations', 'revenus'));
    }

    // ════════════════════════════════════════
    //  Analytics par langue
    // ════════════════════════════════════════
    public function langues()
    {
        abort_unless(auth()->user()->can('view analytics'), 403);

        $langues = Langue::orderBy('ordre')->get();

        // Stats globales
        $stats = [
            'total_passages' => LanguePassage::count(),
            'termines'       => LanguePassage::where('statut', 'termine')->count(),
            'score_moyen'    => (int) round(LanguePassage::where('statut', 'termine')->avg('score') ?? 0),
            'ce_mois'        => LanguePassage::whereMonth('created_at', now()->month)
                                             ->whereYear('created_at', now()->year)->count(),
            'scores' => [
                '80_100' => LanguePassage::where('statut', 'termine')->whereBetween('score', [80, 100])->count(),
                '60_79'  => LanguePassage::where('statut', 'termine')->whereBetween('score', [60, 79])->count(),
                '40_59'  => LanguePassage::where('statut', 'termine')->whereBetween('score', [40, 59])->count(),
                '0_39'   => LanguePassage::where('statut', 'termine')->whereBetween('score', [0, 39])->count(),
            ],
        ];

        // Passages par langue
        $passagesParLangue = [];
        foreach ($langues as $langue) {
            $passagesParLangue[$langue->code] = LanguePassage::where('langue_id', $langue->id)->count();
        }

        // Passages par mois
        $parMois = LanguePassage::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')
            ->orderBy('mois')
            ->get();

        // Passages avec filters
        $query = LanguePassage::with([
            'user:id,first_name,last_name,email',
            'serie:id,titre',
            'discipline:id,nom,code',
            'langue:id,code,nom,couleur'
        ]);

        // Appliquer les filtres
        if (request('langue')) {
            $query->whereHas('langue', fn($q) => $q->where('code', request('langue')));
        }
        if (request('statut')) {
            $query->where('statut', request('statut'));
        }
        if (request('score')) {
            match(request('score')) {
                'high' => $query->where('score', '>=', 60),
                'mid'  => $query->whereBetween('score', [40, 59]),
                'low'  => $query->where('score', '<', 40),
                default => null,
            };
        }

        $passages = $query->latest('created_at')->paginate(20);

        // Top étudiants
        $topEtudiants = LanguePassage::selectRaw('user_id, COUNT(*) as nb_passages, ROUND(AVG(score), 0) as score_moyen')
            ->where('statut', 'termine')
            ->groupBy('user_id')
            ->orderByDesc('score_moyen')
            ->limit(5)
            ->with('user:id,first_name,last_name')
            ->get();

        return view('admin.analytics.langues',
            compact('stats', 'langues', 'passages', 'parMois', 'passagesParLangue', 'topEtudiants'));
    }

    // ════════════════════════════════════════
    //  Analyse d'un utilisateur spécifique
    // ════════════════════════════════════════
    public function userDetail(User $user)
    {
        abort_unless(auth()->user()->can('view analytics'), 403);

        $langues = Langue::orderBy('ordre')->get();

        // ✅ Passages groupés par langue
        $passagesParLangue = [];
        foreach ($langues as $langue) {
            $passagesParLangue[$langue->code] = LanguePassage::with([
                'serie:id,titre,nom',
                'discipline:id,nom,code',
                'langue:id,code,nom'
            ])
                ->where('user_id', $user->id)
                ->where('langue_id', $langue->id)
                ->where('statut', 'termine')
                ->orderByDesc('created_at')
                ->get();
        }

        // Stats globales user
        $statsUser = [
            'total'       => LanguePassage::where('user_id', $user->id)->count(),
            'termines'    => LanguePassage::where('user_id', $user->id)
                                          ->where('statut','termine')
                                          ->count(),
            'score_moyen' => (int) round(
                LanguePassage::where('user_id', $user->id)
                             ->where('statut','termine')
                             ->avg('score') ?? 0
            ),
            'meilleur'    => LanguePassage::where('user_id', $user->id)
                                          ->where('statut','termine')
                                          ->max('score') ?? 0,
        ];

        // ✅ Progression dans le temps
        $progression = LanguePassage::where('user_id', $user->id)
            ->where('statut', 'termine')
            ->selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, ROUND(AVG(score),0) as score_moyen, COUNT(*) as nb")
            ->groupBy('mois')
            ->orderBy('mois')
            ->limit(12)
            ->get();

        // Abonnements de l'utilisateur
        $abonnements = LangueAbonnement::with(['plan:id,nom,code', 'langue:id,nom,code'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('admin.analytics.user_detail',
            compact('user', 'langues', 'passagesParLangue', 'statsUser', 'progression', 'abonnements'));
    }
}