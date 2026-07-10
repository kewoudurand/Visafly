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
        Schema::create('langue_abonnements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('plan_id');
            
            // 1. Ajoutez ->nullable() ici pour permettre le 'set null'
            $table->unsignedBigInteger('langue_id')->nullable(); 
            $table->decimal('montant', 10, 2)->nullable();
            // Optionnel : ajouter la devise si vous avez la même erreur plus tard
            $table->string('devise', 10)->nullable();
            
            $table->timestamp('debut_at')->useCurrent();
            $table->timestamp('fin_at')->nullable();
            
            $table->enum('statut',[
                'en_attente',
                'actif',
                'expire',
                'annule'
            ])->default('en_attente');
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('plan_id');
            $table->index('langue_id');
            $table->index('statut');
            $table->index('fin_at');
            
            // Contraintes
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans_abonnements')->onDelete('restrict');
            
            // 2. On garde une seule définition de clé étrangère, bien configurée avec nullable()
            $table->foreign('langue_id')->references('id')->on('langues')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('langue_abonnements');
    }
};