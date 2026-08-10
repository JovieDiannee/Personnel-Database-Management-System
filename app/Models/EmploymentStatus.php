<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentStatus extends Model
{
    use HasFactory;

    protected $table = 'employment_status';

    protected $fillable = [
        'users_id',
        'plantilla_db_id',
        'school_db_id',
        'date_of_original_appointment',
        'date_of_last_promotion',
        'employment_status',
        'warm_body_status',
        'nature_of_work',
        'source_of_fund',
        'monthly_salary',
        'contract_duration',
    ];

    protected $casts = [
        'date_of_original_appointment' => 'date',
        'date_of_last_promotion' => 'date',
        'monthly_salary' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'users_id'
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
}