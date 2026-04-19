<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_progressions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cours_id')->constrained('courses')->onDelete('cascade');

            $table->unsignedSmallInteger('lecons_terminees')->default(0);
            $table->unsignedSmallInteger('total_lecons')->default(0);
            $table->unsignedTinyInteger('pourcentage')->default(0);   // 0-100
            $table->unsignedSmallInteger('points_total')->default(0);

            $table->boolean('termine')->default(false);
            $table->timestamp('terminee_le')->nullable();

            $table->timestamps();
            $table->unique(['user_id', 'cours_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_progressions');
    }
};