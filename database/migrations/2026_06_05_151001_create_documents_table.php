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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            
            // Clé étrangère : lié à la consultation
            $table->foreignId('consultation_id')->constrained()->onDelete('cascade');
            // Lie le document à une étape précise de la pipeline
            // null = document général (soumis avant l'ouverture de la pipeline)
            $table->unsignedTinyInteger('etape_index')->nullable();
            
            // Informations sur le fichier
            $table->string('name'); // Ex: "Passeport_Jean_Dupont.pdf"
            $table->string('file_path'); // Le chemin réel sur le serveur (storage/app/...)
            
            // Catégorisation du document
            // Ex: 'passeport', 'diplome', 'releve_note', 'attestation_travail', 'preuve_fonds'
            $table->string('type'); 
            
            // Suivi par le consultant
            // Ex: 'en_attente', 'valide', 'rejete'
            $table->string('status')->default('en_attente'); 
            $table->text('comment')->nullable(); // Ex: "Flou, merci de re-scanner en couleur"

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
