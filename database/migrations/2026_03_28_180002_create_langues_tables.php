<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// ─────────────────────────────────────────────────────────────
//  FICHIER : database/migrations/2024_01_01_000001_create_langues_tables.php
//  Lance   : php artisan migrate
// ─────────────────────────────────────────────────────────────

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. langues ──────────────────────────────────────────
        Schema::create('langues', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();       // tcf | tef | ielts | goethe
            $table->string('nom');                  // TCF Canada, TEF Canada...
            $table->string('organisme');            // France Éducation International...
            $table->text('description')->nullable();
            $table->string('logo')->nullable();     // chemin image logo
            $table->string('couleur')->default('#1B3A6B'); // couleur UI hex
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // ── 2. langue_disciplines ────────────────────────────────
        Schema::create('langue_disciplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('langue_id')
                  ->constrained('langues')
                  ->onDelete('cascade');
            $table->string('code');         // ce | co | ee | eo | reading | lesen...
            $table->string('nom');          // Compréhension Écrite...
            $table->string('nom_court')->nullable(); // CE | CO | EE | EO
            $table->enum('type', ['texte','audio','image','production']);
            $table->boolean('has_audio')->default(false); // supporte upload audio
            $table->boolean('has_image')->default(false); // supporte upload image
            $table->integer('duree_minutes')->default(60);
            $table->text('consigne')->nullable();
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // ── 3. langue_series ─────────────────────────────────────
        Schema::create('langue_series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')
                  ->constrained('langue_disciplines')
                  ->onDelete('cascade');
            $table->string('titre');
            $table->text('description')->nullable();
            $table->tinyInteger('niveau')->default(1); // 1=débutant 2=inter 3=avancé
            $table->integer('duree_minutes')->default(30);
            $table->integer('nombre_questions')->default(0); // mis à jour auto
            $table->boolean('gratuite')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // ── 4. langue_questions ───────────────────────────────────
        Schema::create('langue_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')
                  ->constrained('langue_series')
                  ->onDelete('cascade');
            $table->text('enonce');
            $table->enum('type_question', ['qcm','vrai_faux','texte_libre','audio'])
                  ->default('qcm');
            $table->string('image')->nullable();  // storage/langues/images/...
            $table->string('audio')->nullable();  // storage/langues/audio/...
            $table->text('contexte')->nullable(); // texte/transcript affiché avant la question
            $table->tinyInteger('points')->default(1);
            $table->integer('duree_secondes')->default(60);
            $table->text('explication')->nullable(); // explication bonne réponse
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // ── 5. langue_reponses ────────────────────────────────────
        Schema::create('langue_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                  ->constrained('langue_questions')
                  ->onDelete('cascade');
            $table->text('texte');
            $table->boolean('correcte')->default(false);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langue_reponses');
        Schema::dropIfExists('langue_questions');
        Schema::dropIfExists('langue_series');
        Schema::dropIfExists('langue_disciplines');
        Schema::dropIfExists('langues');
    }
};