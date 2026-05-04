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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            
            // Destinataire
            $table->unsignedBigInteger('user_id');
            
            // Type et contenu
            $table->string('type'); 
            // Types: 'withdrawal_initiated', 'withdrawal_approved', 'withdrawal_rejected',
            //        'affiliation_completed', 'course_created', 'lesson_created',
            //        'new_student', 'commission_earned', 'system'
            $table->string('title');
            $table->text('message');
            $table->string('icon')->default('bell'); // bell, check, warning, x, star, etc
            
            // Données supplémentaires (JSON)
            $table->json('data')->nullable();
            
            // Action
            $table->string('action_url')->nullable();
            $table->string('action_label')->nullable();
            
            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('user_id');
            $table->index('is_read');
            $table->index('type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
