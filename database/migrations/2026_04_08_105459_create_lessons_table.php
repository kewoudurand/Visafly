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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('courses')->onDelete('cascade');
            $table->string('titre');                   // "Bonjour en allemand"
            $table->string('slug')->unique();
            $table->text('contenu');                   // Texte explicatif (HTML ou Markdown)
            $table->enum('type', [
                'vocabulaire',   // Liste de mots
                'grammaire',     // Règles grammaticales
                'dialogue',      // Conversations
                'exercice',      // Quiz / exercices
                'culture',       // Culture allemande
                'prononciation', // Audio & prononciation
            ])->default('vocabulaire');
             $table->json('mots')->nullable();
            /*
             * Structure mots : [
             *   { "de": "Hallo", "fr": "Bonjour", "phonetique": "ˈhalo", "exemple": "Hallo, wie geht's?" },
             * ]
             */
            $table->json('exercices')->nullable();
            /*
             * Structure exercices : [
             *   {
             *     "question": "Comment dit-on 'Bonjour' ?",
             *     "type": "qcm",    // qcm | texte_libre | association
             *     "choix": ["Hallo", "Tschüss", "Danke", "Bitte"],
             *     "reponse": "Hallo",
             *     "explication": "Hallo est le mot standard..."
             *   }
             * ]
             */

            $table->string('audio')->nullable();       // fichier audio stocké
            $table->integer('duree_minutes')->default(15);
            $table->boolean('gratuite')->default(false);
            $table->integer('ordre')->default(0);
            $table->integer('points_recompense')->default(10);
            $table->timestamps();

            $table->index(['cours_id', 'ordre']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
