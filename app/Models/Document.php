<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $fillable = [
        'consultation_id',
        'etape_index',
        'name',
        'file_path',
        'type',
        'status',
        'comment'
    ];

    /**
     * Relation : Le document appartient à une consultation spécifique.
     */
    public function consultation(): BelongsTo
    {
        return $this->belongsTo(Consultation::class);
    }
    
    /**
     * Un petit "Accessor" pour récupérer facilement l'URL de téléchargement du fichier
     */
    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}