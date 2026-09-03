<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class Enrollment extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'enrollments';

    protected $fillable = [
        'school_db_id',
        'school_year_id',
        'grade_level_id',
        'enrollment_count',
    ];

    protected $casts = [
        'school_db_id' => 'integer',
        'school_year_id' => 'integer',
        'grade_level_id' => 'integer',
        'enrollment_count' => 'integer',
    ];

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
    | School Year
    |--------------------------------------------------------------------------
    */

    public function schoolYear(): BelongsTo
    {
        return $this->belongsTo(
            SchoolYear::class,
            'school_year_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Grade Level
    |--------------------------------------------------------------------------
    */

    public function gradeLevel(): BelongsTo
    {
        return $this->belongsTo(
            GradeLevel::class,
            'grade_level_id'
        );
    }
}