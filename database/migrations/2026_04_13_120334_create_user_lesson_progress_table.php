<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ── 5. Progression par leçon ─────────────────────────────────────
        Schema::create('user_lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');

            $table->boolean('vue')->default(false);
            $table->boolean('terminee')->default(false);

            // Résultat du quiz
            $table->unsignedTinyInteger('score_quiz')->nullable();
            $table->boolean('quiz_reussi')->nullable();

            $table->timestamp('terminee_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'lesson_id']);
            $table->enum('statut', ['non_commence', 'en_cours', 'termine'])->default('non_commence');
            $table->timestamp('started_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_lesson_progress');
    }
};
