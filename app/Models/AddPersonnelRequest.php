<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddPersonnelRequest extends Model
{
    protected $fillable = [

        // Request
        'requested_by',

        // Personal Information
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'email',
        'sex',
        'birth_place',
        'birth_date',
        'mobile_number',
        'specialization',

        // Employment
        'school_db_id',
        'plantilla_db_id',
        'date_of_original_appointment',
        'date_of_last_promotion',
        'employment_status',
        'warm_body_status',
        'nature_of_work',
        'source_of_fund',
        'monthly_salary',
        'contract_duration',

        // Approval
        'status',
        'request_remarks',
        'review_remarks',
        'reviewed_by',
        'reviewed_at',

        // Created personnel
        'created_user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',

        'date_of_original_appointment' => 'date',
        'date_of_last_promotion' => 'date',

        'monthly_salary' => 'decimal:2',

        'reviewed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | User Who Submitted Request
    |--------------------------------------------------------------------------
    */

    public function requester(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'requested_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HR / Personnel Staff Who Reviewed
    |--------------------------------------------------------------------------
    */

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'reviewed_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Personnel Account Created After Approval
    |--------------------------------------------------------------------------
    */

    public function createdUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_user_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | School
    |--------------------------------------------------------------------------
    */

    public function school(): BelongsTo
    {
        return $this->belongsTo(
            SchoolDb::class,
            'school_db_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Plantilla
    |--------------------------------------------------------------------------
    */

    public function plantilla(): BelongsTo
    {
        return $this->belongsTo(
            PlantillaDb::class,
            'plantilla_db_id'
        );
    }
}