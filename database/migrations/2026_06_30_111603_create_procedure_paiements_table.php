<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procedure_paiements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_procedure_id')
                  ->constrained('client_procedures')
                  ->onDelete('cascade');

            $table->decimal('montant', 10, 2);
            $table->string('devise', 5)->default('XAF');

            // Personne qui verse physiquement l'argent — pas forcément un compte User
            // (peut être le client lui-même ou un tiers : parent, tuteur, etc.)
            $table->string('nom_payeur')->nullable();

            $table->foreignId('enregistre_par')          // admin/secrétaire qui saisit
                  ->constrained('users')
                  ->onDelete('restrict');

            $table->enum('mode', ['especes', 'virement', 'mobile_money', 'carte', 'autre'])
                  ->default('especes');
            $table->enum('statut', ['en_attente', 'recu', 'annule'])->default('recu');

            $table->string('reference')->nullable();
            $table->date('date_paiement');
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procedure_paiements');
    }
};