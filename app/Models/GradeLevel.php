<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class GradeLevel extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

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