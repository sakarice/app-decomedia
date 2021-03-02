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

Route::get('/home/mypage/profile', 'App\Http\Controllers\MypageController@profile');

Route::get('/home/mypage/createtrack', 'App\Http\Controllers\CreateTrackController@index');

Route::post('/home/mypage/createtrack', 'App\Http\Controllers\CreateTrackController@createTrack');

Route::get('/home/mypage/tracks', 'App\Http\Controllers\TracksController@view');

Route::post('/home/mypage/tracks', 'App\Http\Controllers\TracksController@deleteTrack');

Route::post('/home/mypage/updatetrack', 'App\Http\Controllers\UpdateTrackController@view');

Route::post('/home/mypage/trackupdating', 'App\Http\Controllers\UpdateTrackController@update');

Route::post('/home/mypage/createroom', 'App\Http\Controllers\RoomController@createRoom');

Route::post('/home/mypage/deleteroom', 'App\Http\Controllers\RoomController@deleteRoom');

Route::post('/home/mypage/showroom', 'App\Http\Controllers\RoomController@showRoom');

Route::post('/home/mypage/showroom/getTrackInfo', 'App\Http\Controllers\RoomController@getTrackInfo');

Route::post('/home/mypage/showroom/updateRoom', 'App\Http\Controllers\RoomController@updateRoom');

Route::post('/home/searchResult', 'App\Http\Controllers\SearchController@searchRooms');

Route::post('/home/enterRoom', 'App\Http\Controllers\RoomController@enterRoom');

Route::post('/home/enterRoom/getTrackInfo', 'App\Http\Controllers\RoomController@getTrackInfo');

