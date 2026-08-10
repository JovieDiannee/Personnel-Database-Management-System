<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeLevel extends Model
{
    use HasFactory;

    protected $table = 'grade_levels';

    protected $fillable = [
        'name',
        'school_level',
        'sort_order',
    ];

    public function enrollments()
    {
        return $this->hasMany(
            Enrollment::class,
            'grade_level_id'
        );
    }
}