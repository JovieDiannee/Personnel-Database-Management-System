<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolDb extends Model
{
    use HasFactory;

    protected $table = 'school_db';

    protected $fillable = [
        'school_id',
        'school_name',
        'school_area',
        'legislative_district',
        'school_district',
        'school_municipality',
        'school_sector',
        'school_curricular_offering',
    ];

    /*
    |--------------------------------------------------------------------------
    | Employment Status Records
    |--------------------------------------------------------------------------
    */

    public function employmentStatuses(): HasMany
    {
        return $this->hasMany(
            EmploymentStatus::class,
            'school_db_id'
        );
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(
            Enrollment::class,
            'school_db_id'
        );
    }
}