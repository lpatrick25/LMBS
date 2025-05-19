<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $primaryKey = 'item_id';

    protected $fillable = [
        'item_name',
        'category_id',
        'beginning_qty',
        'current_qty',
        'laboratory',
        'description',
        'image',
    ];
}
