<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pipeline_etapes', function (Blueprint $table) {
            $table->id();

            // ── Relation principale ───────────────────────────────────────
            $table->foreignId('consultation_id')
                  ->constrained()
                  ->onDelete('cascade');

            // ── Définition de l'étape ─────────────────────────────────────
            $table->unsignedTinyInteger('ordre');          // Position : 0, 1, 2 …
            $table->string('titre');                       // Ex: "Test de langue"
            $table->text('description')->nullable();       // Instructions détaillées
            $table->string('pays_cle')->nullable();        // canada|allemagne|france …
            // Permet de retrouver les docs requis côté mobile sans re-coder la config

            // ── Statut ───────────────────────────────────────────────────
            // en_attente → en_cours → valide | rejete
            $table->enum('statut', ['en_attente', 'en_cours', 'valide', 'rejete'])
                  ->default('en_attente');

            // ── Validation par le consultant ─────────────────────────────
            $table->timestamp('validee_le')->nullable();
            $table->foreignId('validee_par')
                  ->nullable()
                  ->constrained('users')
                  ->onDelete('set null');
            $table->text('note_consultant')->nullable();   // Visible par l'étudiant

            // ── Contrainte d'unicité : une seule fois chaque ordre par dossier
            $table->unique(['consultation_id', 'ordre']);

            // Liste des types de documents demandés pour cette étape (ex: ["passeport", "preuve_fonds"])
            $table->json('documents_requis')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pipeline_etapes');
    }
};