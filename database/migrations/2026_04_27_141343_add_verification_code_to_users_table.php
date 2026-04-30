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
            $table->string('referral_code')->nullable()->unique()->after('id');
            $table->unsignedBigInteger('referred_by')->nullable()->after('referral_code');
                        $table->foreign('referred_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Flag pour savoir si l'utilisateur peut être affilié
            $table->boolean('is_active_affiliate')->default(true)->after('referred_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
