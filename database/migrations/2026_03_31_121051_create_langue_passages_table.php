<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Passages (sessions d'épreuve) ──────────────────
        Schema::create('langue_passages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained('users')->onDelete('cascade');

            $table->foreignId('serie_id')
                  ->constrained('langue_series')->onDelete('cascade');

            $table->foreignId('discipline_id')
                  ->constrained('langue_disciplines')->onDelete('cascade');

            // Statut du passage
            $table->enum('statut', ['en_cours', 'termine', 'abandonne'])
                  ->default('en_cours');

            // Résultats
            $table->integer('score')->nullable();           // 0-100 (%)
            $table->integer('bonnes_reponses')->default(0);
            $table->integer('mauvaises_reponses')->default(0);
            $table->integer('non_repondues')->default(0);
            $table->integer('total_questions')->default(0);
            $table->integer('points_obtenus')->default(0);
            $table->integer('points_total')->default(0);

            // Temps
            $table->timestamp('debut_at')->nullable();
            $table->timestamp('fin_at')->nullable();
            $table->integer('duree_secondes')->nullable();  // temps effectif passé

            $table->timestamps();

            // Index pour requêtes fréquentes
            $table->index(['user_id', 'statut']);
            $table->index(['serie_id', 'discipline_id']);
        });

        // ── 2. Réponses enregistrées par passage ──────────────
        Schema::create('langue_passage_reponses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('passage_id')
                  ->constrained('langue_passages')->onDelete('cascade');

            $table->foreignId('question_id')
                  ->constrained('langue_questions')->onDelete('cascade');

            $table->foreignId('reponse_id')
                  ->nullable()
                  ->constrained('langue_reponses')->nullOnDelete();

            $table->boolean('correcte')->default(false);
            $table->timestamps();

            $table->unique(['passage_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langue_passage_reponses');
        Schema::dropIfExists('langue_passages');
    }
};