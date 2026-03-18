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
            Schema::create('consultations', function (Blueprint $table) {
                $table->id();

                $table->string('full_name');
                $table->date('birth_date')->nullable();
                $table->string('nationality')->nullable();
                $table->string('residence_country')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('profession')->nullable();

                $table->string('project_type')->nullable();
                $table->string('destination_country')->nullable();

                $table->boolean('visa_history')->default(false);
                $table->text('visa_history_details')->nullable();

                $table->string('last_degree')->nullable();
                $table->string('graduation_year')->nullable();
                $table->string('field_of_study')->nullable();
                $table->string('language_level')->nullable();
                $table->text('work_experience')->nullable();

                $table->boolean('passport_valid')->default(false);
                $table->boolean('documents_available')->default(false);
                $table->boolean('admission_or_contract')->default(false);
                $table->boolean('financial_proof')->default(false);

                $table->string('budget')->nullable();
                $table->string('departure_date')->nullable();
                $table->string('referral_source')->nullable();
                $table->text('message')->nullable();

                $table->boolean('need_consultation')->default(false);
                $table->boolean('status')->default(0);

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
