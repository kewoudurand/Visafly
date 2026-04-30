<?php

// FILE: app/Http/Controllers/Admin/AffiliateAdminController.php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Referral;
use App\Models\AffiliateWallet;
use App\Services\AffiliationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AffiliateWithdrawal;
use Illuminate\Support\Facades\DB;

class AffiliateAdminController extends Controller
{
    protected $affiliationService;

    public function __construct(AffiliationService $affiliationService)
    {
        $this->affiliationService = $affiliationService;
    }

    /**
     * ✅ Dashboard global des affiliations
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'affiliated_users' => User::whereNotNull('referred_by')->count(),
            'total_referrals' => Referral::count(),
            'pending_commissions' => Referral::where('status', 'pending')->sum('commission'),
            'completed_commissions' => Referral::where('status', 'completed')->sum('commission'),
            'withdrawn_commissions' => Referral::where('status', 'withdrawn')->sum('commission'),
            'total_commissions' => Referral::sum('commission'),
            'total_wallets' => AffiliateWallet::sum('amount'),
        ];

        // Top 10 affiliés
        $topAffiliates = User::withCount('referrals')
                            ->having('referrals_count', '>', 0)
                            ->orderByDesc('referrals_count')
                            ->take(10)
                            ->get();

        // Parrainages récents
        $recentReferrals = Referral::with(['referrer', 'referred'])
                                    ->latest()
                                    ->take(20)
                                    ->get();

        return view('admin.affiliate.index', compact(
            'stats',
            'topAffiliates',
            'recentReferrals'
        ));
    }

    /**
     * ✅ Liste des parrainages en attente
     */
    public function pendingReferrals()
    {
        $referrals = Referral::where('status', 'pending')
                            ->with(['referrer', 'referred'])
                            ->paginate(50);

        return view('admin.affiliate.pending', compact('referrals'));
    }

    /**
     * ✅ Compléter manuellement un parrainage
     */
    public function completeReferral(Referral $referral)
    {
        try {
            DB::transaction(function () use ($referral) {
                // Ne peut compléter que les "pending"
                if ($referral->status !== 'pending') {
                    throw new \Exception("Ce parrainage a déjà le statut: {$referral->status}");
                }

                $referral->complete();

                // Notifier l'affilié
                // $referral->referrer->notify(new AffiliationCompletedNotification($referral));
            });

            return back()->with('success', "✅ Parrainage #{$referral->id} complété avec succès!");

        } catch (\Exception $e) {
            return back()->with('error', "❌ Erreur: {$e->getMessage()}");
        }
    }

    /**
     * ✅ Rejeter/Annuler un parrainage
     */
    public function rejectReferral(Referral $referral, Request $request)
    {
        try {
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);

            DB::transaction(function () use ($referral, $request) {
                $referral->update([
                    'status' => 'rejected',
                    'description' => 'Rejeté: ' . $request->reason,
                ]);
            });

            return back()->with('success', "❌ Parrainage #{$referral->id} rejeté");

        } catch (\Exception $e) {
            return back()->with('error', "❌ Erreur: {$e->getMessage()}");
        }
    }

    /**
     * ✅ Compléter tous les parrainages en attente
     */
    public function completeAllPending()
    {
        $referrals = Referral::where('status', 'pending')->get();

        if ($referrals->isEmpty()) {
            return back()->with('warning', '⚠️  Aucun parrainage en attente');
        }

        $count = 0;
        DB::transaction(function () use ($referrals, &$count) {
            foreach ($referrals as $referral) {
                $referral->complete();
                $count++;
            }
        });

        return back()->with('success', "✅ {$count} parrainage(s) complété(s)!");
    }

    /**
     * ✅ Gérer les utilisateurs affiliés (liste)
     */
    public function affiliatesList()
    {
        $affiliates = User::with('affiliateWallet')
                            ->withCount('referrals')
                            ->whereHas('affiliateWallet')
                            ->paginate(50);

        return view('admin.affiliate.affiliates-list', compact('affiliates'));
    }

    /**
     * ✅ Détail d'un affilié
     */
    public function affiliateDetail(User $user)
    {
        if (!$user->affiliateWallet) {
            return back()->with('error', 'Cet utilisateur n\'est pas un affilié');
        }

        $stats = $this->affiliationService->getDetailedStats($user);

        $referrals = $user->affiliateActivity()
                            ->with('referred')
                            ->latest()
                            ->paginate(50);

        return view('admin.affiliate.affiliate-detail', compact(
            'user',
            'stats',
            'referrals'
        ));
    }

    /**
     * ✅ Valider/Rejeter les parrainages d'un affilié
     */
    public function manageAffiliateReferrals(User $user)
    {
        if (!$user->affiliateWallet) {
            return back()->with('error', 'Utilisateur pas affilié');
        }

        $referrals = $user->affiliateActivity()
                            ->with('referred')
                            ->paginate(50);

        return view('admin.affiliate.manage-referrals', compact(
            'user',
            'referrals'
        ));
    }

    /**
     * ✅ Désactiver un affilié
     */
    public function deactivateAffiliate(User $user)
    {
        try {
            $user->update(['is_active_affiliate' => false]);

            return back()->with('success', "✅ Affilié {$user->name} désactivé");

        } catch (\Exception $e) {
            return back()->with('error', "❌ Erreur: {$e->getMessage()}");
        }
    }

    /**
     * ✅ Réactiver un affilié
     */
    public function activateAffiliate(User $user)
    {
        try {
            $user->update(['is_active_affiliate' => true]);

            return back()->with('success', "✅ Affilié {$user->name} réactivé");

        } catch (\Exception $e) {
            return back()->with('error', "❌ Erreur: {$e->getMessage()}");
        }
    }

    /**
     * ✅ Afficher la liste de toutes les demandes de retrait (Côté Admin)
     */
    public function withdrawals()
    {
        // On récupère les retraits avec les infos de l'utilisateur
        $withdrawals = AffiliateWithdrawal::with('user')
            ->latest()
            ->paginate(50);

        // Statistiques globales pour les widgets de l'admin
        $totalWithdrawn = AffiliateWithdrawal::where('status', 'completed')->sum('amount');
        $pendingWithdraw = AffiliateWithdrawal::where('status', 'pending')->sum('amount');

        return view('admin.affiliate.withdrawals', compact(
            'withdrawals',
            'totalWithdrawn',
            'pendingWithdraw'
        ));
    }

    /**
     * ✅ Approuver et Finaliser un retrait
     */
    public function approveWithdrawal(Request $request)
    {
        $id = $request->input('withdrawal_id');
        $request->validate([
            'reference_paiement' => 'nullable|string|max:255', // ex: ID de transaction Orange
        ]);

        try {
            $withdrawal = AffiliateWithdrawal::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('error', "Ce retrait a déjà été traité (Statut: {$withdrawal->status})");
            }

            DB::transaction(function () use ($withdrawal, $request) {
                // 1. Mettre à jour le statut du retrait
                $withdrawal->update([
                    'status' => 'completed',
                    'notes' => $request->reference_paiement ? "Payé via: " . $request->reference_paiement : $withdrawal->notes,
                    'updated_at' => now(),
                ]);

                // 2. Mettre à jour le cumul "Retiré" dans le wallet de l'utilisateur
                $wallet = $withdrawal->user->affiliateWallet;
                if ($wallet) {
                    $wallet->increment('total_withdrawn', $withdrawal->amount);
                }
            });

            return back()->with('success', "✅ Le retrait de {$withdrawal->amount} F pour {$withdrawal->user->name} a été marqué comme complété.");

        } catch (\Exception $e) {
            return back()->with('error', "❌ Erreur: " . $e->getMessage());
        }
    }

    /**
     * ❌ Rejeter un retrait (et rendre l'argent au portefeuille)
     */
    public function rejectWithdrawal(Request $request, $id)
    {
        try {
            $withdrawal = AffiliateWithdrawal::findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('error', "Impossible de rejeter un retrait déjà traité.");
            }

            DB::transaction(function () use ($withdrawal) {
                // 1. On rend l'argent sur le solde 'amount' de l'utilisateur
                $withdrawal->user->affiliateWallet->increment('amount', $withdrawal->amount);

                // 2. On marque le retrait comme échoué/rejeté
                $withdrawal->update([
                    'status' => 'failed',
                    'notes' => 'Rejeté par l\'administrateur.',
                ]);
            });

            return back()->with('success', "Le retrait a été rejeté et les fonds ont été restitués à l'utilisateur.");

        } catch (\Exception $e) {
            return back()->with('error', "Erreur lors du rejet : " . $e->getMessage());
        }
    }

    /**
     * ✅ Manuellement ajouter une commission
     */
    public function addManualCommission(Request $request)
    {
        $request->validate([
            'referrer_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
        ]);

        try {
            $referrer = User::findOrFail($request->referrer_id);

            DB::transaction(function () use ($referrer, $request) {
                // 1. Créer l'historique du parrainage manuel
                Referral::create([
                    'referrer_id' => $referrer->id,
                    'referred_id' => $referrer->id,
                    'commission' => $request->amount,
                    'status' => 'completed',
                    'description' => 'Commission manuelle: ' . $request->description,
                ]);

                // 2. Récupérer ou Créer le wallet s'il n'existe pas
                // C'est ici qu'on règle le "Call to on null"
                $wallet = $referrer->affiliateWallet()->firstOrCreate(
                    ['user_id' => $referrer->id],
                    [
                        'amount' => 0, 
                        'total_earned' => 0,
                        'total_withdrawn' => 0
                    ]
                );

                // 3. Ajouter les fonds sur l'objet $wallet (qui n'est plus null)
                $wallet->addFunds($request->amount);
            });

            return back()->with('success', "✅ Commission manuelle de " . number_format($request->amount, 0) . " F ajoutée à {$referrer->name}");

        } catch (\Exception $e) {
            return back()->with('error', "❌ Erreur: {$e->getMessage()}");
        }
    }

    /**
     * ✅ Exporter les stats en CSV
     */
    public function exportStats()
    {
        $affiliates = User::with('affiliateWallet')
                            ->whereHas('affiliateWallet')
                            ->get();

        $csv = "Nom,Email,Code,Parrainés,Commissions Complétées,Solde,Total Gagné\n";

        foreach ($affiliates as $user) {
            $completed = $user->affiliateActivity()
                            ->where('status', 'completed')
                            ->sum('commission');

            $csv .= "\"{$user->name}\",\"{$user->email}\",\"{$user->referral_code}\",";
            $csv .= "{$user->referrals()->count()},{$completed},";
            $csv .= "{$user->affiliateWallet->balance},{$user->affiliateWallet->total_earned}\n";
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="affiliates-' . date('Y-m-d') . '.csv"');
        }
    }
