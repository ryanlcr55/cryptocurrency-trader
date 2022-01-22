<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRunningRobotHistory extends Model
{
    use HasFactory;

    public $fillable = [
        'user_id',
        'user_robot_reference_id',
        'robot_uid',
        'exchange',
        'base_coin_code',
        'target_coin_code',
        'type',
        'amount',
        'starting_price',
        'ending_price',
        'profit',
        'creating_at',
        'ending_at',
    ];
}


