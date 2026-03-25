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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->string('country')->nullable()->after('avatar');
            $table->string('language')->default('fr')->after('country');
            $table->string('timezone')->default('Africa/Yaounde')->after('language');
            $table->string('phone')->nullable()->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
    */

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar','country','language','timezone','phone']);
        });
    }



};
