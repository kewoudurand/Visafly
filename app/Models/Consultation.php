<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = [
        'full_name',
        'birth_date',
        'nationality',
        'residence_country',
        'phone',
        'email',
        'profession',

        'project_type',
        'destination_country',

        'visa_history',
        'visa_history_details',

        'last_degree',
        'graduation_year',
        'field_of_study',
        'language_level',
        'work_experience',

        'passport_valid',
        'documents_available',
        'admission_or_contract',
        'financial_proof',

        'budget',
        'departure_date',
        'referral_source',
        'message',

        'need_consultation',
        'status',
    ];

    protected $casts = [
        'visa_history' => 'boolean',
        'passport_valid' => 'boolean',
        'admission_or_contract' => 'boolean',
        'financial_proof' => 'boolean',
        'need_consultation' => 'boolean',
        'documents_available' => 'boolean',
        'status' => 'boolean',
    ];
}

