<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rendez_vous', function (Blueprint $table) {
            $table->id();

            // ── Relations ────────────────────────────────────────────────
            $table->foreignId('consultation_id')
                  ->constrained()
                  ->onDelete('cascade');

            $table->foreignId('consultant_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            // ── Planification ─────────────────────────────────────────────
            $table->dateTime('date_heure');
            $table->unsignedSmallInteger('duree_minutes')->default(45);

            // ── Canal de communication ────────────────────────────────────
            $table->enum('canal', ['zoom', 'google_meet', 'teams', 'presentiel', 'telephone'])
                  ->default('zoom');
            $table->string('lien_visio')->nullable();    // URL de la réunion
            $table->string('adresse')->nullable();        // Si présentiel

            // ── Statut du rendez-vous ─────────────────────────────────────
            // prevu    → confirmé par le client
            // confirme → validé par les deux parties
            // annule   → annulé (voir motif_annulation)
            // termine  → déroulé, compte-rendu disponible
            $table->enum('statut', ['prevu', 'confirme', 'annule', 'termine'])
                  ->default('prevu');

            $table->text('motif_annulation')->nullable();   // Si annulé
            $table->text('compte_rendu')->nullable();        // Résumé post-RDV (consultant)
            $table->boolean('rappel_envoye')->default(false); // Pour éviter les doublons de rappel

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rendez_vous');
    }
};