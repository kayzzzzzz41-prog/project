<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'name',
        'address',
        'contract_number',
        'tenant_phone',
        'tenant_iin',
        'amount',
        'is_paid',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'amount'  => 'decimal:2',
    ];
}