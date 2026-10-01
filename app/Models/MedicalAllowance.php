<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalAllowance extends Model
{
    use HasFactory;

    protected $table = 'medical_allowance';

    protected $fillable = [
        'users_id',
        'year',
        'mode_of_availment',
        'disbursement_status',
        'validation_status',
    ];


    /*
    |--------------------------------------------------------------------------
    | User Relationship
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'users_id'
        );
    }
}