<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_notes', function (Blueprint $table) {
            $table->id();

            // ── Relations ────────────────────────────────────────────────
            $table->foreignId('consultation_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('auteur_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // ── Contenu ───────────────────────────────────────────────────
            $table->text('contenu');

            // ── Visibilité ────────────────────────────────────────────────
            // false = note interne (consultants uniquement)
            // true  = visible par le client dans l'onglet Historique
            $table->boolean('visible_client')->default(false);

            // ── Contexte optionnel ────────────────────────────────────────
            // Lier la note à une étape ou un document spécifique
            $table->foreignId('pipeline_etape_id')
                  ->nullable()
                  ->constrained('pipeline_etapes')
                  ->onDelete('set null');

            $table->foreignId('document_id')
                  ->nullable()
                  ->constrained('documents')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_notes');
    }
};