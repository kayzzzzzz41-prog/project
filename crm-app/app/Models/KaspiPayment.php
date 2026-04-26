<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaspiPayment extends Model
{
    protected $fillable = [
        'txn_id',
        'prv_txn_id',
        'account',
        'sum',
        'txn_date',
        'result',
        'command',
    ];
}