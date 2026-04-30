<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AffiliateWithdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    /**
     * ✅ ÉTAPE 1: Choix du montant
     */
    public function showForm()
    {
        $user = Auth::user();
        $wallet = $user->affiliateWallet;

        return view('affiliate.withdraw.step1-amount', [
            'user' => $user,
            'wallet' => $wallet,
            'balance' => $wallet->amount, // CHANGÉ: balance -> amount
        ]);
    }

    /**
     * ✅ ÉTAPE 1b: Validation du montant
     */
    public function validateAmount(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->affiliateWallet;

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1000',
                'max:' . $wallet->amount, // CHANGÉ: balance -> amount
            ],
        ], [
            'amount.required' => 'Le montant est obligatoire',
            'amount.min' => 'Le montant minimum est 1,000 F',
            'amount.max' => 'Vous ne pouvez pas retirer plus que votre solde (' . number_format($wallet->amount, 0) . ' F)',
        ]);

        $request->session()->put('withdrawal_amount', $validated['amount']);

        return redirect()->route('affiliate.withdraw.show-method')
                         ->with('amount', $validated['amount']);
    }

    /**
     * ✅ ÉTAPE 2: Choix du moyen de paiement
     */
    public function chooseMethod(Request $request)
    {
        $amount = $request->session()->get('withdrawal_amount');

        if (!$amount) {
            return redirect()->route('affiliate.withdraw.show-form')
                             ->with('error', 'Veuillez d\'abord saisir un montant');
        }

        return view('affiliate.withdraw.step2-method', [
            'amount' => $amount,
            'methods' => [
                'orange_money' => AffiliateWithdrawal::getPaymentInstructions('orange_money'),
                'mtn' => AffiliateWithdrawal::getPaymentInstructions('mtn'),
                'bank_transfer' => AffiliateWithdrawal::getPaymentInstructions('bank_transfer'),
            ],
        ]);
    }

    /**
     * ✅ ÉTAPE 3: Détails du moyen
     */
    public function showMethodDetails($method)
    {
        $user = Auth::user();
        $amount = session('withdrawal_amount');

        if (!$amount) {
            return redirect()->route('affiliate.withdraw.show-form');
        }

        $instructions = AffiliateWithdrawal::getPaymentInstructions($method);

        if (!$instructions) {
            return redirect()->route('affiliate.withdraw.choose-method')
                             ->with('error', 'Moyen de paiement invalide');
        }

        return view('affiliate.withdraw.step3-details', [
            'method' => $method,
            'amount' => $amount,
            'instructions' => $instructions,
            'user' => $user,
        ]);
    }

    /**
     * ✅ ÉTAPE 4: Soumettre le retrait
     */
    public function submitWithdrawal(Request $request)
    {
        $user = Auth::user();
        $amount = session('withdrawal_amount');

        if (!$amount) {
            return redirect()->route('affiliate.withdraw.show-form')
                             ->with('error', 'Session expirée, veuillez recommencer');
        }

        $validated = $request->validate([
            'method' => 'required|in:orange_money,mtn,bank_transfer,other',
            'reference' => 'required|string|min:5|max:255',
        ]);

        try {
            DB::transaction(function () use ($user, $amount, $validated) {
                $wallet = $user->affiliateWallet;
                
                // CHANGÉ: balance -> amount
                if ($wallet->amount < $amount) {
                    throw new \Exception('Solde insuffisant');
                }

                $withdrawal = AffiliateWithdrawal::create([
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'method' => $validated['method'],
                    'reference' => $validated['reference'],
                    'status' => 'pending',
                    'notes' => 'Demande créée le ' . now()->format('d/m/Y H:i'),
                ]);

                // CHANGÉ: balance -> amount
                $wallet->decrement('amount', $amount);

                Log::info("Retrait demandé", [
                    'user_id' => $user->id,
                    'amount' => $amount,
                    'withdrawal_id' => $withdrawal->id,
                ]);
            });

            $request->session()->forget('withdrawal_amount');

            return redirect()->route('affiliate.dashboard')
                             ->with('success', '✅ Demande de retrait soumise avec succès !');

        } catch (\Exception $e) {
            return back()->with('error', '❌ Erreur: ' . $e->getMessage());
        }
    }

    /**
     * 📋 Historique
     */
    public function history()
    {
        $user = Auth::user();
        $withdrawals = AffiliateWithdrawal::where('user_id', $user->id)
                                         ->latest()
                                         ->paginate(20);

        return view('affiliate.withdraw.history', compact('withdrawals'));
    }

    /**
     * ❌ Annuler un retrait
     */
    public function cancelWithdrawal(AffiliateWithdrawal $withdrawal)
    {
        $user = Auth::user();

        if ($withdrawal->user_id !== $user->id) {
            return back()->with('error', 'Non autorisé');
        }

        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Ce retrait ne peut pas être annulé');
        }

        try {
            DB::transaction(function () use ($withdrawal, $user) {
                // CHANGÉ: balance -> amount
                $user->affiliateWallet->increment('amount', $withdrawal->amount);

                $withdrawal->update([
                    'status' => 'failed',
                    'notes' => 'Annulé par l\'utilisateur',
                ]);
            });

            return back()->with('success', 'Retrait annulé. L\'argent a été remis sur votre compte.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}