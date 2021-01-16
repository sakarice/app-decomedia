<?php

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/home/mypage', 'App\Http\Controllers\MypageController@view');

Route::get('/home/mypage/maketrack', 'App\Http\Controllers\MakeTrackController@index');

Route::post('/home/mypage/maketrack', 'App\Http\Controllers\MakeTrackController@uploadfile');