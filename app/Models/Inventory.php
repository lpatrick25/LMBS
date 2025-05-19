<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $primaryKey = 'inventory_id';

    protected $fillable = [
        'inventory_number',
        'item_id',
        'beginning_inventory',
        'ending_inventory',
        'starting_period',
        'ending_period',
        'total_borrowed',
        'usable_qty',
        'damaged_qty',
        'lost_qty',
        'repair_qty',
        'disposal_qty',
        'laboratory',
    ];
}
