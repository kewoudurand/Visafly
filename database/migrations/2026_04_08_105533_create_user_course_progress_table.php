<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ─────────────────────────────────────────────────────────────────────────────
//  FICHIER : database/migrations/2026_04_13_000001_create_courses_tables.php
//  Système de cours par niveaux (A1→C2) avec leçons audio/texte
// ─────────────────────────────────────────────────────────────────────────────

return new class extends Migration
{
    public function up(): void
    {
        // ── 4. Progression utilisateur par cours ─────────────────────────
        Schema::create('user_course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->foreignId('course_id')
                  ->constrained('courses')
                  ->onDelete('cascade');

            $table->enum('statut', ['non_commence', 'en_cours', 'termine'])
                  ->default('en_cours');

            $table->unsignedTinyInteger('progression_pct')->default(0);
            $table->unsignedSmallInteger('lecons_terminees')->default(0);
            $table->unsignedSmallInteger('lecons_total')->default(0);

            // Score moyen du quiz
            $table->unsignedTinyInteger('score_quiz_moyen')->nullable();

            $table->timestamp('debut_at')->useCurrent();
            $table->timestamp('fin_at')->nullable();
            $table->timestamp('derniere_activite_at')->useCurrent();

            $table->timestamps();

            $table->unique(['user_id', 'course_id']);
            $table->index(['user_id', 'statut']);
            $table->index('course_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_course_progress');
    }
};