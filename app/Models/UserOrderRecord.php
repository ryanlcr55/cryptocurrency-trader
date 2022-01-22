<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrderRecord extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'robot_uid',
        'exchange',
        'exchange_order_id',
    ];
}
