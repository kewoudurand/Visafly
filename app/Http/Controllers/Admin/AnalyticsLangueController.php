<?php
// ════════════════════════════════════════════════════════════════
//  app/Http/Controllers/Admin/AnalyticsLangueController.php
// ════════════════════════════════════════════════════════════════
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Langue;
use App\Models\LangueSerie;
use App\Models\LangueDiscipline;
use App\Models\LanguePassage; // ou LanguePassage si tu as créé ce model
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class AnalyticsLangueController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->can('view analytics'), 403);
 
        $langues = Langue::orderBy('ordre')->get();
 
        // ── Passages avec relations ──
        // Note: adapter selon ton model TcfPassage ou LanguePassage
        $query = LanguePassage::with(['user', 'serie.discipline.langue'])
            ->latest();
 
        if ($request->filled('langue')) {
            $query->whereHas('serie.discipline.langue', fn($q) =>
                $q->where('code', $request->langue)
            );
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('score')) {
            match($request->score) {
                'high' => $query->where('score', '>=', 60),
                'mid'  => $query->whereBetween('score', [40, 59]),
                'low'  => $query->where('score', '<', 40),
                default => null,
            };
        }
 
        $passages = $query->paginate(20)->withQueryString();
 
        // ── Stats globales ──
        $stats = [
            'total_passages' => LanguePassage::count(),
            'termines'       => LanguePassage::where('statut', 'termine')->count(),
            'score_moyen'    => (int) round(LanguePassage::where('statut', 'termine')->avg('score') ?? 0),
            'ce_mois'        => LanguePassage::whereMonth('created_at', now()->month)
                                          ->whereYear('created_at', now()->year)->count(),
            'scores' => [
                '80_100' => LanguePassage::where('statut','termine')->where('score','>=',80)->count(),
                '60_79'  => LanguePassage::where('statut','termine')->whereBetween('score',[60,79])->count(),
                '40_59'  => LanguePassage::where('statut','termine')->whereBetween('score',[40,59])->count(),
                '0_39'   => LanguePassage::where('statut','termine')->where('score','<',40)->count(),
            ],
        ];
 
        // ── Passages par examen ──
        $passagesParLangue = [];
        foreach ($langues as $l) {
            $passagesParLangue[$l->code] = LanguePassage::whereHas(
                'serie.discipline.langue', fn($q) => $q->where('code', $l->code)
            )->count();
        }
 
        // ── Par mois (6 derniers) ──
        $parMois = LanguePassage::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as total")
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('mois')->orderBy('mois')->get();
 
        // ── Top étudiants ──
        $topEtudiants = LanguePassage::select('user_id',
                DB::raw('COUNT(*) as nb_passages'),
                DB::raw('ROUND(AVG(score),0) as score_moyen')
            )
            ->with('user')
            ->where('statut', 'termine')
            ->groupBy('user_id')
            ->orderByDesc('score_moyen')
            ->limit(5)->get();
 
        return view('admin.analytics.langues', compact(
            'langues', 'passages', 'stats',
            'passagesParLangue', 'parMois', 'topEtudiants'
        ));
    }
}
 