<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cours_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();

            // Informations générales
            $table->string('titre');
            $table->string('slug')->unique();
            $table->enum('type', ['vocabulaire', 'dialogue', 'grammaire', 'audio', 'lecture'])
                  ->default('vocabulaire');
            $table->longText('contenu')->nullable();  // Corps de leçon en Markdown

            // Contenu structuré (JSON)
            $table->json('mots')->nullable();         // [{ de, fr, phonetique, exemple }]
            $table->json('exercices')->nullable();    // [{ question, type, choix, reponse, explication }]

            // Support audio (type = 'audio')
            $table->string('fichier_audio')->nullable();       // Storage path
            $table->text('transcription_audio')->nullable();   // Texte de l'audio
            $table->json('questions_audio')->nullable();       // Questions de compréhension audio

            // Méta
            $table->boolean('gratuite')->default(false);
            $table->boolean('publiee')->default(true);
            $table->integer('ordre')->default(0);
            $table->integer('points_recompense')->default(10);
            $table->integer('duree_estimee_minutes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};