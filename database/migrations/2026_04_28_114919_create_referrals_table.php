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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referrer_id');
            $table->unsignedBigInteger('referred_id');
            
            // Commission payable
            $table->decimal('commission', 10, 2)->default(0);
            
            // Status: pending, completed, withdrawn
            $table->enum('status', ['pending', 'completed', 'withdrawn'])->default('pending');
            
            // Description (optional)
            $table->string('description')->nullable();
            
            $table->timestamps();
            
            // Indexes pour les requêtes
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referred_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('referrer_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
