<?php

// app/Services/AffiliationService.php
namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AffiliationService
{
    /**
     * Commission par défaut (en CFA ou monnaie locale)
     */
    protected const DEFAULT_COMMISSION = 250; // Montant de commision par défaut

    /**
     * Enregistrer un nouvel utilisateur avec affiliation
     * 
     * @param array $userData - Données de l'utilisateur
     * @param string|null $referralCode - Code de parrainage du sponsor
     * @return User
     */
    public function registerWithAffiliation(array $userData, ?string $referralCode = null)
    {
        return DB::transaction(function () use ($userData, $referralCode) {
            // Créer l'utilisateur
            $user = User::create($userData);

            // Si un code de parrainage est fourni
            if ($referralCode) {
                $this->processReferral($user, $referralCode);
            }

            return $user;
        });
    }

    /**
     * Traiter la relation de parrainage
     */
    public function processReferral(User $referredUser, string $referralCode)
    {
        // Trouver le parrain
        $referrer = User::where('referral_code', $referralCode)
                        ->where('is_active_affiliate', true)
                        ->first();

        if (!$referrer) {
            throw new \Exception('Code de parrainage invalide');
        }

        // Éviter l'auto-parrainage
        if ($referrer->id === $referredUser->id) {
            throw new \Exception('Impossible de se parrainer soi-même');
        }

        // Associer le parrain à l'utilisateur
        $referredUser->update(['referred_by' => $referrer->id]);

        // Créer l'enregistrement d'affiliation
        $commission = $this->calculateCommission($referrer, $referredUser);

        $referral = Referral::create([
            'referrer_id' => $referrer->id,
            'referred_id' => $referredUser->id,
            'commission' => $commission,
            'status' => 'pending',
            'description' => "Parrainage de {$referredUser->name} ({$referredUser->email})",
        ]);

        // Logger l'action
        Log::info("Affiliation créée", [
            'referrer_id' => $referrer->id,
            'referred_id' => $referredUser->id,
            'commission' => $commission,
        ]);

        return $referral;
    }

    /**
     * Calculer la commission (personnalisable selon la logique métier)
     */
    public function calculateCommission(User $referrer, User $referred)
    {
        $config = config('affiliate');
        
        // Utiliser la config pour la commission de base
        $baseCommission = $config['default_commission'];
        
        // Appliquer les bonus par tier
        $referralCount = $referrer->affiliateActivity()
                                ->where('status', 'completed')
                                ->count();
        
        foreach ($config['bonus_tiers'] as $threshold => $multiplier) {
            if ($referralCount >= $threshold) {
                $baseCommission *= $multiplier;
            }
        }
        
        // Commission en pourcentage si configurée
        if ($config['commission_percentage']) {
            // À adapter selon votre logique (prix du produit acheté, etc)
            // $productPrice = $referred->lastPurchase()->amount;
            // $baseCommission = ($productPrice * $config['commission_percentage']) / 100;
        }
        
        return $baseCommission;
    }
    /**
     * Valider et compléter une affiliation
     * Appelé quand l'utilisateur parrainé fait sa première action (achat, abonnement, etc)
     */
    public function completeAffiliation(User $referredUser)
    {
        $referral = Referral::where('referred_id', $referredUser->id)
                           ->where('status', 'pending')
                           ->first();

        if ($referral) {
            $referral->complete();

            // Envoyer une notification
            // $referral->referrer->notify(new AffiliationCompletedNotification($referral));

            return $referral;
        }

        return null;
    }

    /**
     * Obtenir les affiliés d'un utilisateur avec pagination
     */
    public function getAffiliatesList(User $user, $perPage = 15)
    {
        return $user->affiliateActivity()
                    ->with('referred')
                    ->orderBy('created_at', 'desc')
                    ->paginate($perPage);
    }

    /**
     * Obtenir le réseau d'affiliation (arbre généalogique)
     */
    public function getAffiliationTree(User $user, $levels = 2)
    {
        $tree = [
            'user' => $user,
            'referrals' => [],
        ];

        if ($levels > 0) {
            $user->referrals()->get()->each(function ($referral) use (&$tree, $levels) {
                $tree['referrals'][] = $this->getAffiliationTree($referral, $levels - 1);
            });
        }

        return $tree;
    }

    /**
     * Obtenir les statistiques détaillées d'affiliation
     */
    public function getDetailedStats(User $user)
    {
        $pending = $user->affiliateActivity()
                       ->where('status', 'pending')
                       ->sum('commission');

        $completed = $user->affiliateActivity()
                         ->where('status', 'completed')
                         ->sum('commission');

        $withdrawn = $user->affiliateActivity()
                         ->where('status', 'withdrawn')
                         ->sum('commission');

        return [
            'user' => $user->name,
            'referral_code' => $user->referral_code,
            'affiliate_link' => $user->getAffiliateLink(),
            'referrals' => [
                'total' => $user->referrals()->count(),
                'active' => $user->directReferrals()->count(),
                'inactive' => $user->referrals()->where('is_active_affiliate', false)->count(),
            ],
            'commissions' => [
                'pending' => $pending,
                'completed' => $completed,
                'withdrawn' => $withdrawn,
                'total_earned' => $pending + $completed + $withdrawn,
            ],
            'wallet' => [
                'amount' => $user->affiliateWallet->amount ?? 0,
                'total_earned' => $user->affiliateWallet->total_earned ?? 0,
                'total_withdrawn' => $user->affiliateWallet->total_withdrawn ?? 0,
            ],
        ];
    }

    /**
     * Retirer les commissions (transfert vers compte bancaire, etc)
     */
    public function withdrawCommissions(User $user, $amount) // J'ai renommé $balance en $amount pour plus de clarté
    {
        $wallet = $user->affiliateWallet;

        if (!$wallet || $wallet->amount < $amount) {
            throw new \Exception('Solde insuffisant');
        }

        return DB::transaction(function () use ($wallet, $amount) {
            // 1. DÉDUIRE L'ARGENT DU WALLET (Indispensable !)
            $wallet->withdrawFunds($amount); 

            // 2. Marquer les commissions comme retirées
            $referrals = $wallet->user->affiliateActivity()
                                    ->where('status', 'completed')
                                    ->orderBy('updated_at')
                                    ->get();

            $remainingToMark = $amount;
            foreach ($referrals as $referral) {
                if ($remainingToMark <= 0) break;

                if ($referral->commission <= $remainingToMark) {
                    $referral->markAsWithdrawn();
                    $remainingToMark -= $referral->commission;
                }
            }

            return [
                'success' => true,
                'amount' => $amount,
                'remaining_balance' => $wallet->refresh()->amount, // Utilise bien 'amount'
            ];
        });
    }

    public function requestWithdrawal(User $user, float $amount, string $method, string $details)
    {
        $wallet = $user->affiliateWallet;

        if (!$wallet || $wallet->amount < $amount) {
            throw new \Exception('Solde insuffisant pour ce retrait.');
        }

        return DB::transaction(function () use ($user, $wallet, $amount, $method, $details) {
            // 1. Déduire du wallet immédiatement (pour bloquer les fonds)
            $wallet->withdrawFunds($amount);

            // 2. Créer la demande de retrait
            return AffiliateWithdrawal::create([
                'user_id' => $user->id,
                'amount' => $amount,
                'method' => $method,
                'payment_details' => $details,
                'status' => 'pending',
            ]);
        });
    }
}
