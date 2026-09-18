<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_of_report',
        'deadline',
        'remarks',
    ];

    protected $attributes = [
        'status' => 'Ongoing',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function submissions(): HasMany
    {
        return $this->hasMany(ReportSubmission::class);
    }
}