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
        Schema::table('plans_abonnements', function (Blueprint $table) {
            $table->timestamp('fin_at')->nullable()->after('actif');
            $table->timestamp('debut_at')->nullable()->after('fin_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans_abonnements', function (Blueprint $table) {
            //
        });
    }
};
