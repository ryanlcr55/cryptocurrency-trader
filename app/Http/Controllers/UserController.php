<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Models\UserOrderRecord;
use App\Exchange\ExchangeBinance;
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

    public function log()
    {
        $user = Auth::user();
        $api_key = $user->exchange_api_key;
        $secret_key = $user->exchange_secret_key;
        $model = new UserOrderRecord();

        $data = $model::query()
            ->groupBy('symbol')
            ->where('user_id', '=', $user->id)
            ->get();

        $model = new ExchangeBinance($api_key, $secret_key);

        $orders = [];


        foreach ($data as $val) {
            $val_arr = json_decode($val);
            $symbol = $val_arr->symbol;
            $single_coin_order = $model->getOrders($symbol);

            foreach ($single_coin_order as $val2)
                $orders[$val2['time']] = $val2;
        }

            krsort($orders);

            return $orders;
    }
}
