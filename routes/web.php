<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth');
});

Route::prefix('user')->group(function () {
    Route::get('/login', function() {
        if (!Auth::check()) {
            return view('user.login');
        } 

        return redirect('/');
    })->name('login');

    Route::prefix('profile')->middleware('auth')->group(function () {
        Route::get('/', function() {
            $user = Auth::user();
            return view('user.profile', [
                'name' => $user->name,
                'username' => $user->username,
                'exchange_api_key' => $user->exchange_api_key
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