<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'action',
        'date',
        'user_id',
        'message',
        'signal_id',
    ];

    public function userRobotReferences()
    {
        return $this->hasMany(UserRobotReference::class);
    }

}
