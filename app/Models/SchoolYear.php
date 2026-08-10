<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $table = 'school_years';

    protected $fillable = [
        'school_year',
        'is_current',
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function enrollments()
    {
        return $this->hasMany(
            Enrollment::class,
            'school_year_id'
        );
    }
}