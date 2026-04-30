<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
 
class AffiliateWithdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'method',
        'reference',
        'status',
        'notes',
    ];
 
    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
 
    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
 
    // ✅ Approuver le retrait
    public function approve()
    {
        if ($this->status !== 'pending') {
            throw new \Exception("Ce retrait ne peut pas être approuvé (status: {$this->status})");
        }
 
        $this->update(['status' => 'approved']);
        return $this;
    }
 
    // ✅ Marquer comme complété
    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
        
        // Mettre à jour le wallet
        $this->user->affiliateWallet->increment('total_withdrawn', $this->amount);
        
        return $this;
    }
 
    // ❌ Rejeter le retrait
    public function reject($reason)
    {
        if ($this->status !== 'pending') {
            throw new \Exception("Ce retrait ne peut pas être rejeté");
        }
 
        // Remettrer les fonds au wallet
        $this->user->affiliateWallet->increment('balance', $this->amount);
 
        $this->update([
            'status' => 'failed',
            'notes' => 'Rejeté: ' . $reason,
        ]);
 
        return $this;
    }
 
    // Obtenir les instructions de paiement
    public static function getPaymentInstructions($method)
    {
        $instructions = [
            'orange_money' => [
                'title' => '🟠 Orange Money',
                'description' => 'Retrait via Orange Money Cameroun',
                'steps' => [
                    '1. Composez *150# sur votre téléphone Orange',
                    '2. Sélectionnez "Retrait d\'argent"',
                    '3. Entrez le montant',
                    '4. Recevez le code de retrait',
                    '5. Allez à un point Orange Money',
                    '6. Donnez votre numéro de téléphone et le code',
                    '7. Retirez votre argent',
                ],
                'fees' => '2,5% (débité automatiquement)',
                'processing_time' => 'Immédiat',
                'min_amount' => 1000,
                'max_amount' => 500000,
                'field_label' => 'Votre numéro Orange Money',
                'field_placeholder' => '+237 6XX XXX XXX',
                'field_hint' => 'Format: +237 6XX XXX XXX (incluant le +237)',
            ],
            'mtn' => [
                'title' => '🔴 MTN Mobile Money',
                'description' => 'Retrait via MTN Mobile Money Cameroun',
                'steps' => [
                    '1. Composez *136# sur votre téléphone MTN',
                    '2. Sélectionnez "Cashout"',
                    '3. Entrez le montant',
                    '4. Confirmez avec votre code PIN',
                    '5. Recevez votre argent',
                ],
                'fees' => '3% (débité automatiquement)',
                'processing_time' => 'Immédiat',
                'min_amount' => 1000,
                'max_amount' => 500000,
                'field_label' => 'Votre numéro MTN Money',
                'field_placeholder' => '+237 6XX XXX XXX',
                'field_hint' => 'Format: +237 6XX XXX XXX',
            ],
            'bank_transfer' => [
                'title' => '🏦 Virement Bancaire',
                'description' => 'Retrait via virement bancaire',
                'steps' => [
                    '1. Virement effectué sur votre compte bancaire',
                    '2. Délai: 1 à 3 jours ouvrables',
                    '3. Frais bancaires applicables',
                ],
                'fees' => '0 - 1% (selon la banque)',
                'processing_time' => '1 à 3 jours ouvrables',
                'min_amount' => 5000,
                'max_amount' => 5000000,
                'field_label' => 'Numéro de compte bancaire',
                'field_placeholder' => 'Ex: 1234567890',
                'field_hint' => 'Votre numéro de compte complet',
            ],
        ];
 
        return $instructions[$method] ?? null;
    }
}