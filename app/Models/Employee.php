<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'contact_no',
        'email',
    ];
}
