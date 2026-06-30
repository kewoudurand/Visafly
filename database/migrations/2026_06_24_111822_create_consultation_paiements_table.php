<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultation_paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')
                  ->constrained('consultations')
                  ->onDelete('cascade');
            $table->foreignId('enregistre_par')        // admin/consultant qui saisit
                  ->constrained('users')
                  ->onDelete('restrict');

            $table->decimal('montant', 10, 2);          // montant de cette tranche
            $table->string('devise', 5)->default('XAF');
            $table->enum('statut', ['en_attente', 'recu', 'annule'])->default('recu');
            $table->enum('mode', ['especes', 'virement', 'mobile_money', 'carte', 'autre'])
                  ->default('especes');
            $table->string('reference')->nullable();    // référence reçu / transaction
            $table->date('date_paiement');
            $table->text('note')->nullable();           // commentaire libre admin
            $table->timestamps();
            $table->softDeletes();
        });

        // Ajouter colonne montant_total sur consultations si elle n'existe pas
        if (!Schema::hasColumn('consultations', 'montant_total')) {
            Schema::table('consultations', function (Blueprint $table) {
                $table->decimal('montant_total', 10, 2)->nullable()->after('status');
                $table->string('devise', 5)->default('XAF')->after('montant_total');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('consultation_paiements');
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumnIfExists('montant_total');
            $table->dropColumnIfExists('devise');
        });
    }
};