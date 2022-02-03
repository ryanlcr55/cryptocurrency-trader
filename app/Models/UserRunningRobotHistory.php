<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRunningRobotHistory extends Model
{
    public $timestamps = false;

    public $fillable = [
        'user_id',
        'signal_id',
        'robot_uid',
        'base_coin_code',
        'coin_code',
        'base_cost',
        'starting_price',
        'ending_price',
        'profit',
        'quantity',
        'fee',
        'creating_at',
        'ending_at',
    ];
}


