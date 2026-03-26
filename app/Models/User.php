<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\TcfAbonnement;

class User extends Authenticatable
{
    use HasRoles, Notifiable;          

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password','role',
        'country', 'language','phone',         
        'timezone', 'avatar',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function abonnements()
    {
        return $this->hasMany(TcfAbonnement::class, 'user_id');
    }

    public function abonnementActif()
    {
        return $this->hasOne(TcfAbonnement::class, 'user_id')
            ->where('actif', true)
            ->where('fin_at', '>=', now())
            ->latest();
    }
}