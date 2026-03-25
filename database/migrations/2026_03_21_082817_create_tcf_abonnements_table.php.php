<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcf_abonnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('forfait');
            $table->decimal('montant', 10, 2);
            $table->string('devise')->default('XAF');
            $table->dateTime('debut_at');
            $table->dateTime('fin_at');
            $table->boolean('actif')->default(true);
            $table->string('reference_paiement')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tcf_abonnements'); }
};