<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionPenalty extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_penalty_id';

    protected $fillable = [
        'transaction_no',
        'item_id',
        'user_id',
        'quantity',
        'amount',
        'status',
        'remarks',
    ];
}
