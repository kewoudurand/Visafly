<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tcf_series', function (Blueprint $table) {
            $table->id();
            $table->string('nom');                        // "Série 100"
            $table->string('code')->unique();             // "serie_100"
            $table->string('type')->default('TCF');       // TCF, TEF, etc.
            $table->boolean('gratuit')->default(false);   // 2 gratuites max
            $table->integer('ordre')->default(0);         // ordre d'affichage
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('tcf_series'); }
};
