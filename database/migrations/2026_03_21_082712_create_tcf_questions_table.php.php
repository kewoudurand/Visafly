<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcf_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discipline_id')->constrained('tcf_disciplines')->onDelete('cascade');
            $table->integer('numero');                    // 1, 2, 3...
            $table->text('consigne')->nullable();         // texte/document support
            $table->string('type_support')->nullable();   // "texte", "image", "audio"
            $table->string('fichier_support')->nullable();// chemin image/audio
            $table->text('enonce');                       // question posée
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tcf_questions'); }
};

