<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcf_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('tcf_questions')->onDelete('cascade');
            $table->string('lettre');                     // A, B, C, D
            $table->text('texte');                        // contenu de la réponse
            $table->boolean('est_correcte')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tcf_reponses'); }
};
