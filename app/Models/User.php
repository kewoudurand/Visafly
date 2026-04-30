<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasRoles, Notifiable;          

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','role',
        'country', 'language','phone',         
        'timezone', 'avatar','referral_code', 'referred_by', 'is_active_affiliate'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }
    public function abonnements()
    {
        return $this->hasMany(LangueAbonnement::class, 'user_id');
    }

    public function abonnementActif()
    {
        return $this->hasOne(LangueAbonnement::class, 'user_id')
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->latest();
    }

    // app/Models/User.php
    public function lessonsProgress() {
        return $this->belongsToMany(Lesson::class, 'user_lesson_progress')
                    ->withPivot('terminee', 'score_quiz', 'statut')
                    ->withTimestamps();
    }

      /**
     * Utilisateur qui nous a référé
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Tous les utilisateurs que nous avons référés
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by');
    }

    /**
     * Toutes nos activités d'affiliation (comme referrer)
     */
    public function affiliateActivity()
    {
        return $this->hasMany(Referral::class, 'referrer_id');
    }

    /**
     * Wallet affilié
     */
    public function affiliateWallet()
    {
        return $this->hasOne(AffiliateWallet::class);
    }

    /**
     * Tous les utilisateurs directs (niveau 1)
     */
    public function directReferrals()
    {
        return $this->hasMany(User::class, 'referred_by')
                    ->where('is_active_affiliate', true);
    }

        // ========== BOOTABLE METHODS ==========

    /**
     * Boot du modèle - génère le code de parrainage automatiquement
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // Générer un code unique si pas déjà présent
            if (!$user->referral_code) {
                $user->referral_code = self::generateUniqueReferralCode();
            }
        });

        static::created(function ($user) {
            // Créer un wallet affilié pour l'utilisateur
            AffiliateWallet::create(['user_id' => $user->id]);
        });
    }

    // ========== HELPER METHODS ==========

    /**
     * Générer un code de parrainage unique
     */
    public static function generateUniqueReferralCode()
    {
        do {
            // Format: PREFIX_RANDOM (ex: VF_ABC123XYZ)
            $code = 'VF_' . Str::random(10);
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Générer un lien d'affiliation
     */
    public function getAffiliateLink()
    {
        return config('app.url') . '/register?ref=' . $this->referral_code;
    }

    /**
     * Obtenir les statistiques d'affiliation
     */
    public function getAffiliateStats()
    {
        return [
            'referral_code'      => $this->referral_code,
            'total_referrals'    => $this->referrals()->count(),
            'active_referrals'   => $this->directReferrals()->count(),
            'pending_commissions' => $this->affiliateActivity()
                                        ->where('status', 'pending')
                                        ->sum('commission'),
            'completed_commissions' => $this->affiliateActivity()
                                        ->where('status', 'completed')
                                        ->sum('commission'),
            'wallet_balance'     => $this->affiliateWallet->balance ?? 0,
            'total_earned'       => $this->affiliateWallet->total_earned ?? 0,
        ];
    }

    /**
     * Vérifier si l'utilisateur peut être un affilié
     */
    public function isActiveAffiliate()
    {
        return $this->is_active_affiliate === true;
    }
}