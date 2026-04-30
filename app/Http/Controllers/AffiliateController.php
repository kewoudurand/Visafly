<?php

namespace App\Http\Controllers;

use App\Services\AffiliationService;
use Illuminate\Support\Facades\Auth;

class AffiliateController extends Controller
{
    protected $affiliationService;

    public function __construct(AffiliationService $affiliationService)
    {
        $this->affiliationService = $affiliationService;
    }

    /**
     * Afficher le dashboard d'affiliation
     */
    public function dashboard()
    {
        $user = Auth::user();
        $stats = $this->affiliationService->getDetailedStats($user);

        return view('affiliate.dashboard', compact('stats'));
    }

    /**
     * Lister tous les affiliés de l'utilisateur
     */
    public function listAffiliates()
    {
        $user = Auth::user();
        $affiliates = $this->affiliationService->getAffiliatesList($user);

        return view('affiliate.list', compact('affiliates'));
    }

    /**
     * Obtenir le lien d'affiliation en JSON (API)
     */
    public function getAffiliateLink()
    {
        $user = Auth::user();

        return response()->json([
            'referral_code' => $user->referral_code,
            'affiliate_link' => $user->getAffiliateLink(),
            'stats' => $user->getAffiliateStats(),
        ]);
    }

    /**
     * Retirer les commissions
     */
    public function withdraw()
    {
        $user = Auth::user();
        $amount = request()->input('amount');

        try {
            $result = $this->affiliationService->withdrawCommissions($user, $amount);

            return response()->json([
                'success' => true,
                'message' => 'Retrait effectué avec succès',
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Obtenir l'historique des transactions
     */
    public function transactionHistory()
    {
        $user = Auth::user();
        $history = $user->affiliateActivity()
                       ->with('referred')
                       ->orderBy('created_at', 'desc')
                       ->paginate(20);

        return view('affiliate.history', compact('history'));
    }

    /**
     * Stats en JSON pour frontend
     */
    public function stats()
    {
        $user = Auth::user();

        return response()->json(
            $this->affiliationService->getDetailedStats($user)
        );
    }
}