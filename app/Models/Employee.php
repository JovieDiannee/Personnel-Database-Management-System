<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'first_name', 'middle_name', 'last_name', 'email',
        'contact_number', 'address', 'birthdate', 'gender', 'department',
        'position', 'date_hired', 'employment_status', 'profile_photo',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'date_hired' => 'date',
    ];

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }
}
