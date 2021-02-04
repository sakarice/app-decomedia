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

Route::get('/home/mypage/createtrack', 'App\Http\Controllers\CreateTrackController@index');

Route::post('/home/mypage/createtrack', 'App\Http\Controllers\CreateTrackController@createTrack');

Route::get('/home/mypage/tracks', 'App\Http\Controllers\TracksController@view');

Route::post('/home/mypage/tracks', 'App\Http\Controllers\TracksController@deleteTrack');

Route::post('/home/mypage/updatetrack', 'App\Http\Controllers\UpdateTrackController@view');

Route::post('/home/mypage/trackupdating', 'App\Http\Controllers\UpdateTrackController@update');

Route::post('/home/mypage/createroom', 'App\Http\Controllers\RoomController@createRoom');

Route::post('/home/mypage/deleteroom', 'App\Http\Controllers\RoomController@deleteRoom');

