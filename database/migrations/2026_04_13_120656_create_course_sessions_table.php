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
        Schema::create('course_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('progression_id')
                  ->constrained('course_progressions')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamp('ouverture_at')->useCurrent();
            $table->timestamp('fermeture_at')->nullable();
            $table->unsignedInteger('duree_secondes')->nullable();

            // Source de la session (utile pour analytics)
            $table->enum('source', ['web', 'mobile', 'api'])->default('web');

            $table->timestamps();
            $table->index(['progression_id', 'ouverture_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_sessions');
    }
};
