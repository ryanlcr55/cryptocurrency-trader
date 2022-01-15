<?php

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


Route::prefix('user')->group(function () {
    Route::get('/login', function() {
        if (!Auth::check()) {
            return view('user.login');
        } 

        return redirect('/');
    });

    Route::group('profile', function () {
        $user = Auth::user();
        Route::get('/', function() use ($user) {
            return view('user.profile', [
                'name' => $user->name,
                'username' => $user->username,
            ]);    
        }); 
        Route::get('/update', function() {
            return view('user.update_profile');
        });
    });
});