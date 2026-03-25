<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcf_disciplines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')->constrained('tcf_series')->onDelete('cascade');
            $table->string('nom');                        // "Compréhension écrite"
            $table->string('code');                       // "comprehension_ecrite"
            $table->string('icone')->nullable();          // nom d'icône Bootstrap
            $table->integer('duree_minutes');             // 60, 40, 12...
            $table->integer('nb_questions');              // 39, 3...
            $table->string('type_questions');             // "qcm" ou "redaction"
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tcf_disciplines'); }
};

