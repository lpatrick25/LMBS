<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_status_id';

    protected $fillable = [
        'transaction_no',
        'item_id',
        'quantity',
        'status',
    ];
}
