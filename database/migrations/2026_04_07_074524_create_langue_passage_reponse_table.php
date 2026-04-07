<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Créer la table pour enregistrer les réponses des utilisateurs aux passages
     */
    public function up(): void
    {
        Schema::create('langue_passages_reponses', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->unsignedBigInteger('passage_id');
            $table->unsignedBigInteger('question_id')->nullable();
            
            // Données
            $table->text('reponse_donnee')->nullable()->comment('La réponse donnée par l\'utilisateur');
            $table->boolean('correcte')->default(false)->comment('Est-ce que la réponse est correcte?');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes
            $table->foreign('passage_id')
                  ->references('id')
                  ->on('langue_passages')
                  ->onDelete('cascade');
            
            $table->index('passage_id');
            $table->index('correcte');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('langue_passages_reponses');
    }
};