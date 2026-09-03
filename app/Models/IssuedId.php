<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class IssuedId extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    protected $table = 'issued_id';

    protected $fillable = [
        'basic_information_id',
        'umid_no',
        'gsis_no',
        'philsys_no',
        'pagibig_no',
        'tin_no',
        'philhealth_no',
        'employee_id',
    ];

    public function basicInformation(): BelongsTo
    {
        return $this->belongsTo(
            BasicInformation::class,
            'basic_information_id'
        );
    }
}