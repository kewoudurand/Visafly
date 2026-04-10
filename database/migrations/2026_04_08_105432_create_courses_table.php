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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();          // a1-debutant, b1-intermediaire
            $table->string('titre');                   // "Allemand A1 — Débutant"
            $table->string('sous_titre')->nullable();  // "Les bases de l'allemand"
            $table->text('description')->nullable();
            $table->string('niveau');                  // A1, A2, B1, B2, C1
            $table->string('couleur')->default('#1B3A6B');
            $table->string('icone')->default('bi-book');
            $table->integer('duree_heures')->default(10);
            $table->boolean('gratuit')->default(false);
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();

            $table->index(['actif', 'ordre']);
            $table->index('niveau');
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
