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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            
            // 🔐 Relations obligatoires
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Le candidat
            $table->foreignId('consultant_id')->nullable()->constrained('users')->onDelete('set null'); // Le consultant assigné
            
            // 👤 Infos d'état civil de base (toujours requises)
            $table->string('full_name');
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('residence_country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('profession')->nullable();
            
            // 🎯 Projet d'immigration cible
            $table->string('project_type')->nullable(); // Études, Travail, Permanent, etc.
            $table->string('destination_country')->nullable(); // Canada, Allemagne, etc.
            
            // ⚙️ Structure Flexible pour le reste des données de tout type
            // Permet de stocker : parcours pro, diplômes, scores de langues, budget sous forme de tableau JSON
            $table->json('metadata')->nullable(); 

            // 📊 Suivi et Statut du dossier
            $table->string('status')->default('en_attente'); // en_attente, en_cours, approuve, refuse
            $table->boolean('urgent')->default(false);
            $table->decimal('montant_total', 15, 2)->default(0)->nullable();
            $table->string('devise', 5)->default('XAF')->nullable();
             // Index de l'étape active dans la pipeline (0-based)
            // Ex: 2 = l'étudiant est à la 3ème étape
            $table->unsignedTinyInteger('etape_courante')->default(0);
            // Progression calculée (0.00 → 1.00)
            // Mise à jour par un Observer ou un job après chaque validation
            $table->float('progression', 5, 2)->default(0.00);
            $table->boolean('is_urgent')->default(false);
            $table->text('note_admin')->nullable(); // Commentaires du consultant
            $table->text('motif_declin')->nullable(); // Si le dossier est refusé
            
            // 📅 Planification du premier rendez-vous de consultation
            $table->dateTime('date_confirmee')->nullable();
            $table->integer('duree_minutes')->nullable()->default(45);
            $table->string('canal')->nullable(); // Zoom, Google Meet, Présentiel
            $table->string('lien_visio')->nullable();

            

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};