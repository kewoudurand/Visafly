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
        // ── 1. Cours (créés par les instructeurs) ─────────────────────────
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('langue_id')
                  ->nullable()
                  ->constrained('langues')
                  ->onDelete('set null');
            $table->string('slug')->nullable();
            $table->text('sous_titre')->nullable();
            $table->string('couleur')->nullable();
            $table->string('icone')->nullable();
            $table->string('titre');
            $table->text('description')->nullable();

            // Niveau CEF : A1, A2, B1, B2, C1, C2
            $table->enum('niveau', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])
                  ->default('A1');
            $table->boolean('gratuit')->default(false);

            $table->string('image_couverture')->nullable();
            $table->integer('duree_estimee_minutes')->default(0); // calculée auto
            $table->boolean('publie')->default(false);
            $table->integer('ordre')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['langue_id', 'niveau', 'publie']);
            $table->index('instructor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
