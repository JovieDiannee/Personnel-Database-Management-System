<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OfficeUnit extends Model
{
    protected $fillable = [
        'office_group_id',
        'parent_id',
        'code',
        'name',
        'short_name',
        'unit_type',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];


    /*
    |--------------------------------------------------------------------------
    | Office Group
    |--------------------------------------------------------------------------
    */

    public function officeGroup(): BelongsTo
    {
        return $this->belongsTo(OfficeGroup::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Parent Unit
    |--------------------------------------------------------------------------
    */

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            OfficeUnit::class,
            'parent_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Child Units
    |--------------------------------------------------------------------------
    */

    public function children(): HasMany
    {
        return $this->hasMany(
            OfficeUnit::class,
            'parent_id'
        )->orderBy('sort_order');
    }


    /*
    |--------------------------------------------------------------------------
    | Employment Statuses
    |--------------------------------------------------------------------------
    */

    public function employmentStatuses(): HasMany
    {
        return $this->hasMany(
            EmploymentStatus::class,
            'office_unit_id'
        );
    }
}