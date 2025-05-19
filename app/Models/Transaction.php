<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'transaction_no';

    protected $fillable = [
        'transaction_no',
        'item_id',
        'user_id',
        'quantity',
        'date_of_usage',
        'date_of_return',
        'time_of_return',
        'status',
        'remarks',
    ];

}
