<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function update(UpdateRequest $request)
    {
        $user = Auth::user();
        $user->update($request->validate());

        return response();
    }
}
