<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    public function update(UpdateRequest $request)
    {
        try {
            $user = Auth::user();
            $attributes = collect($request->all())
                ->filter(function ($row) {
                    return ($row);
                })
                ->toArray();
            $user->update($attributes);
            
            //return redirect('user/profile');
            Auth::logout();

        echo ("<script>alert('修改成功！請重新登入');location='/user/login'</script>");

        } catch (\Exception $e) { 

            return back()->withErrors($e->getMessage);
        } 
      }
}
