<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter colonne langue_id à langue_passages
     * pour avoir une relation directe vers Langue
     */
    public function up(): void
    {
        Schema::table('langue_passages', function (Blueprint $table) {
            // Ajouter colonne langue_id si elle n'existe pas
            if (!Schema::hasColumn('langue_passages', 'langue_id')) {
                $table->unsignedBigInteger('langue_id')->nullable()->after('discipline_id');
                
                // Index
                $table->index('langue_id');
                
                // Contrainte
                $table->foreign('langue_id')
                    ->references('id')
                    ->on('langues')
                    ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('langue_passages', function (Blueprint $table) {
            if (Schema::hasColumn('langue_passages', 'langue_id')) {
                $table->dropForeign(['langue_id']);
                $table->dropColumn('langue_id');
            }
        });
    }
};