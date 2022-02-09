<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Exchange\ExchangeBinance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::post('/authenticate', [AuthController::class, 'authenticate']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

Route::prefix('user')->group(function () {
    Route::get('/login', function() {
        if (!Auth::check()) {
            return view('user.login');
        } 
     return redirect('/user/profile');
    })->name('login');

    Route::prefix('profile')->middleware('auth')->group(function () {
        Route::get('/', function() {
            $user = Auth::user();
            $api_key = $user->exchange_api_key;
            $secert_key = $user->exchange_secert_key;
            $model = new ExchangeBinance($api_key, $secert_key);
           $coin = $model->getBnbBurnStatus();
            return view('user.profile', [
                'name' => $user->name,
                'username' => $user->username,
                'exchange_api_key' => $user->exchange_api_key,
                'coin' => $coin
            ]);    
        }); 

        Route::get('/log', function() {
            $user = Auth::user();
            return view('user.log', [
                'name' => $user->name,
                'username' => $user->username,

            ]);    
        }); 


        Route::prefix('update')->group(function () {
            Route::get('/', function() {
                $user = Auth::user();
                return view('user.update_profile', ['user' => $user]);
            });
            Route::post('/', [UserController::class, 'update']);
        });

    });

});