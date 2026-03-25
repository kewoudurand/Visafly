<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcf_passage_reponses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('passage_id')->constrained('tcf_passages')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('tcf_questions')->onDelete('cascade');
            $table->foreignId('reponse_id')->nullable()->constrained('tcf_reponses')->nullOnDelete();
            $table->boolean('est_correcte')->default(false);
            $table->timestamps();

            $table->unique(['passage_id', 'question_id']);
        });
    }
    public function down(): void { Schema::dropIfExists('tcf_passage_reponses'); }
};

