<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── 1. Plans d'abonnement (créés par l'admin) ──
        Schema::create('plans_abonnements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                    // Mensuel, Trimestriel, Annuel
            $table->string('code')->unique();         // mensuel, trimestriel, annuel
            $table->string('couleur')->default('#1B3A6B');
            $table->string('icone')->default('bi-star'); // Bootstrap Icons class
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 0);           // 5000, 12000, 40000
            $table->string('devise')->default('XAF');
            $table->integer('duree_jours');           // 30, 90, 365
            $table->json('points');                   // [{icone, texte}]
            $table->boolean('populaire')->default(false); // Badge "Populaire"
            $table->boolean('actif')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });

        // ── 2. Abonnements utilisateurs ──
        // (si elle n'existe pas encore — sinon adapter)
        if (!Schema::hasTable('tcf_abonnements')) {
            Schema::create('tcf_abonnements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('plan_id')->nullable()
                      ->constrained('plans_abonnements')->nullOnDelete();
                $table->string('forfait');               // code du plan
                $table->decimal('montant', 10, 0);
                $table->string('devise')->default('XAF');
                $table->timestamp('debut_at');
                $table->timestamp('fin_at');
                $table->boolean('actif')->default(true);
                $table->string('reference_paiement')->nullable();
                $table->string('methode_paiement')->nullable(); // mtn_money, orange_money, carte
                $table->timestamps();
            });
        } else {
            // Ajouter plan_id si table existe déjà
            Schema::table('tcf_abonnements', function (Blueprint $table) {
                if (!Schema::hasColumn('tcf_abonnements', 'plan_id')) {
                    $table->foreignId('plan_id')->nullable()
                          ->constrained('plans_abonnements')->nullOnDelete()
                          ->after('user_id');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('plans_abonnements');
    }
};