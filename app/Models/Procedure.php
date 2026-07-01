<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProcedurePaiement;

class Procedure extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'procedures';

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'devise',
        'actif',
    ];

    protected $casts = [
        'prix'  => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function clientProcedures()
    {
        return $this->hasMany(ClientProcedure::class);
    }

    public function prixFormate(): string
    {
        return number_format($this->prix, 0, ',', ' ') . ' ' . $this->devise;
    }
}