<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('affiliate_wallets', function (Blueprint $table) {
            $table->id();
            
            // Relations
            $table->unsignedBigInteger('user_id'); // L'affilié qui retire
            
            // Détails du retrait
            $table->decimal('amount', 15, 2)->default(0);
            $table->enum('method', ['orange_money', 'mtn', 'bank_transfer', 'other'])->default('other');
            $table->string('reference')->nullable(); // Ref transaction (ex: numéro phone)
            
            // Status
            $table->enum('status', ['pending', 'approved', 'completed', 'failed'])->default('pending');
            $table->text('notes')->nullable();

            // Total gagné dans la vie
            $table->decimal('total_earned', 15, 2)->default(0);
            
            // Total retiré
            $table->decimal('total_withdrawn', 15, 2)->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_wallets');
    }
};
