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
        Schema::create('affiliate_withdrawals', function (Blueprint $table) {
                $table->id();
                
                // Relations
                $table->unsignedBigInteger('user_id'); // L'affilié qui retire
                
                // Détails du retrait
                $table->decimal('amount', 15, 2);      // Montant demandé
                $table->enum('method', ['orange_money', 'mtn', 'bank_transfer', 'other']); // Moyen de paiement
                $table->string('reference')->nullable(); // Numéro phone ou compte bancaire
                
                // Status du retrait
                $table->enum('status', ['pending', 'approved', 'completed', 'failed'])->default('pending');
                
                // Notes (raison du rejet, ID transaction, etc)
                $table->text('notes')->nullable();
                
                // Dates
                $table->timestamps();
                
                // Indexes pour les requêtes rapides
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index('user_id');
                $table->index('status');
                $table->index('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_withdrawals');
    }
};
