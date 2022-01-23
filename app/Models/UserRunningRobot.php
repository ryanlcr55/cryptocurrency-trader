<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRunningRobot extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'signal_id',
        'robot_uid',
        'exchange',
        'coin_code',
        'base_coin_code',
        'amount',
        'starting_price',
        'lower_limit_price',
        'disabled',
    ];
}
