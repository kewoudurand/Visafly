<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lesson_progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('courses')->onDelete('cascade');

            $table->enum('statut', ['en_cours', 'terminee'])->default('en_cours');

            // Score exercices (0-100)
            $table->unsignedTinyInteger('score')->default(0);
            $table->unsignedSmallInteger('bonnes_reponses')->default(0);
            $table->unsignedSmallInteger('total_questions')->default(0);
            $table->unsignedTinyInteger('tentatives')->default(1);

            // Récompenses
            $table->unsignedSmallInteger('points_gagnes')->default(0);

            // Réponses détaillées de l'étudiant [{ exercice_index, reponse_donnee, correct }]
            $table->json('reponses_etudiant')->nullable();

            $table->timestamp('commencee_le')->useCurrent();
            $table->timestamp('terminee_le')->nullable();

            $table->timestamps();
            $table->unique(['user_id', 'lesson_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_progressions');
    }
};
