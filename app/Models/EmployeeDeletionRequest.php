<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDeletionRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'requested_by',
        'request_remarks',
        'status',
        'source',
        'pending_employee_id',
        'reviewed_by',
        'review_remarks',
        'reviewed_at',
        'restored_by',
        'restored_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'restored_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id')
            ->withTrashed();
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by')
            ->withTrashed();
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by')
            ->withTrashed();
    }

    public function restorer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'restored_by')
            ->withTrashed();
    }
}