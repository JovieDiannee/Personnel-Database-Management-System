<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

class PlantillaDb extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'plantilla_db';

    protected $fillable = [
        'item_number',
        'item_from',
        'item_from_school_level',
        'position_title',
        'salary_grade',
        'area_code',
        'area_type',
        'plantilla_level',
        'pppa_attribution',
    ];

    protected $casts = [
        'salary_grade' => 'integer',
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
            'plantilla_db_id'
        );
    }
}