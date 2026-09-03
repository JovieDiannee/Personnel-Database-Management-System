<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class MedicalAllowance extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'medical_allowance';

    protected $fillable = [
        'users_id',
        'mode_of_availment',
        'disbursement_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}