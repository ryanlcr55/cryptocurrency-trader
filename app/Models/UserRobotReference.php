<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRobotReference extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'signal_id',
        'base_coin_code',
        'unit_percent',
        'limit_percent',
        'stop_percent',
    ];

    public function runningRobot($coinCode)
    {
        return $this->hasMany(UserRunningRobot::class)
            ->where('status', '=', UserRunningRobot::STATUS_ACTIVED)
            ->where('coin_code', '=', $coinCode);
    }
}
