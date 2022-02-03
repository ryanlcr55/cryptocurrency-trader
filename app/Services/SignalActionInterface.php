<?php
namespace App\Services;

use App\Models\UserRobotReference;

interface SignalActionInterface
{
    public function exec(UserRobotReference $robotReference, string $coinCode);
}
