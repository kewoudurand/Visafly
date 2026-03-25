<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcf_passages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('discipline_id')->constrained('tcf_disciplines')->onDelete('cascade');
            $table->timestamp('debut_at');
            $table->timestamp('fin_at')->nullable();
            $table->integer('score')->nullable();
            $table->integer('nb_correctes')->default(0);
            $table->integer('temps_utilise')->nullable();  // secondes
            $table->enum('statut', ['en_cours', 'termine', 'abandonne'])->default('en_cours');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tcf_passages'); }
};
