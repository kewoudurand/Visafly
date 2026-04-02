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
            $table->unsignedBigInteger('langue_id')->nullable(); // Pour tracker quelle langue
            
            $table->string('code')->unique()->default(''); // Code du plan
            $table->decimal('montant', 10, 2)->default(0);
            $table->string('devise', 10)->default('XAF');
            
            $table->timestamp('debut_at')->useCurrent();
            $table->timestamp('fin_at')->nullable();
            $table->boolean('actif')->default(true);
            
            $table->string('reference_paiement')->nullable()->unique();
            $table->string('methode_paiement')->default('en_attente'); // flutterwave, momo, etc
            $table->string('statut_paiement')->default('en_attente'); // en_attente, confirme, echec
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('plan_id');
            $table->index('langue_id');
            $table->index('actif');
            $table->index('fin_at');
            
            // Contraintes
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('plan_id')->references('id')->on('plans_abonnements')->onDelete('restrict');
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