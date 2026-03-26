<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TcfAbonnement;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AbonnementController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('manage users');

        $abonnements = TcfAbonnement::with('user')
            ->latest()
            ->paginate(20);

        $stats = [
            'actifs'       => TcfAbonnement::where('actif', true)->where('fin_at', '>=', now())->count(),
            'expires'      => TcfAbonnement::where(fn($q) => $q->where('actif', false)->orWhere('fin_at', '<', now()))->count(),
            'revenus_mois' => TcfAbonnement::whereMonth('created_at', now()->month)
                                ->whereYear('created_at', now()->year)->sum('montant'),
            'revenus_total'=> TcfAbonnement::sum('montant'),
        ];

        return view('admin.abonnements.index', compact('abonnements', 'stats'));
    }
}