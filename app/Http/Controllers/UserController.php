<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\RobotRequest;
use App\Models\UserOrderRecord;
use App\Models\Signal;
use App\Models\UserRobotReference;
use App\Exchange\ExchangeBinance;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


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

    public function robotlog()
    {
        $user = Auth::user();
        $model = new UserOrderRecord();

        $data = $model::query()
            ->where('user_id', '=', $user->id)
            ->get();

        $val_arr = [];


        foreach ($data as $val) {
            $val_arr[] = json_decode($val);
        }

        return $val_arr;
    }

    public function selectSignal()
    {
        $user = Auth::user();
        $model = new Signal();
        $data = $model::query()
            ->groupBy('name')
            ->get();

        $val_arr = [];
        $robot_signal_arr = [];

        $model = new UserRobotReference();  

        $robot_data = $model::query()
        ->where('user_id', '=', $user->id)
        ->get();

        foreach ($robot_data as $val){
            $tmp_val = json_decode($val);
            $robot_signal_arr[] = $tmp_val->signal_id;
        }

        foreach ($data as $val) {
            $tmp_val = json_decode($val);
            if(!in_array($tmp_val->id, $robot_signal_arr)){
                $val_arr[] = $tmp_val;
            }
        }

        return $val_arr;
    }

    public function selectRobot()
    {
        $user = Auth::user();
        $data = DB::table('user_robot_references')
            ->leftJoin('signals', 'user_robot_references.signal_id', '=', 'signals.id')
            ->where('user_id', '=', $user->id)
            ->get();

        return $data;
    }

    public function updateRobot(RobotRequest $request)
    {
        try {
            $attributes = collect($request->all())
                ->filter(function ($row) {
                    return ($row);
                })
                ->toArray();

            $model = new UserRobotReference();    
            $user = Auth::user();

            $model->insert([
                    'user_id' => $user->id,
                    'signal_id' => $attributes['signal_id'],
                    'unit_percent' => $attributes['unit_percent'],
                    'limit_percent' =>  $attributes['limit_percent'],
                    'stop_percent' => $attributes['stop_percent'],
                    'created_at' => date("Y-m-d H:i:s")
                ]);

            return redirect('user/profile/robot');

        } catch (\Exception $e) {

            echo ("<script>alert('新增失敗');");

            return back()->withErrors($e->getMessage);
        }
    }

}
