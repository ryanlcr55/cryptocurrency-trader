<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRunningRobot extends Model
{
    use HasFactory;

    const RUNNING_ROBOT_STATUS_INIT = 'init';
    const RUNNING_ROBOT_STATUS_ACTIVED = 'actived';
    const RUNNING_ROBOT_STATUS_STOPPED = 'stopped';

    public $fillable = [
        'user_id',
        'signal_id',
        'robot_uid',
        'coin_code',
        'base_coin_code',
        'cost',
        'quantity',
        'starting_price',
        'lower_limit_price',
        'disabled',
    ];
}
