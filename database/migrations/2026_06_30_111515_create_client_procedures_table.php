<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Attribution d'une procédure du catalogue à un client précis
        Schema::create('client_procedures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('procedure_id')
                  ->constrained('procedures')
                  ->onDelete('restrict');

            $table->foreignId('user_id')                // le client qui fait la procédure
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->foreignId('assigne_par')             // admin/secrétaire qui a attribué
                  ->constrained('users')
                  ->onDelete('restrict');

            // Snapshot du prix au moment de l'attribution (modifiable indépendamment du catalogue)
            $table->decimal('prix_total', 10, 2);
            $table->string('devise', 5)->default('XAF');

            $table->enum('statut', ['en_cours', 'terminee', 'annulee'])->default('en_cours');
            $table->date('date_debut')->nullable();
            $table->text('note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_procedures');
    }
};