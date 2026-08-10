<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BasicInformation extends Model
{
    protected $table = 'basic_information';

    protected $fillable = [
        'users_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'sex',
        'birth_place',
        'birth_date',
        'civil_status',
        'religion',
        'citizenship',
        'mode_of_citizenship',
        'height_m',
        'weight_kg',
        'blood_type',
        'mobile_number',
        'telephone_number',
        'specialization',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'height_m' => 'decimal:2',
        'weight_kg' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function employmentStatus(): HasOne
    {
        return $this->hasOne(EmploymentStatus::class,'users_id');
    }
}