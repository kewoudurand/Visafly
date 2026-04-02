<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Remplir langue_id pour tous les passages existants
     * basé sur la relation discipline -> langue
     */
    public function up(): void
    {
        // Mettre à jour tous les passages pour avoir langue_id
        DB::statement(
            'UPDATE langue_passages lp
             INNER JOIN langue_disciplines ld ON lp.discipline_id = ld.id
             SET lp.langue_id = ld.langue_id
             WHERE lp.langue_id IS NULL'
        );
    }

    public function down(): void
    {
        // Si on revient en arrière, remettre NULL
        DB::statement('UPDATE langue_passages SET langue_id = NULL');
    }
};