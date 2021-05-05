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

Route::get('/home/chat', 'App\Http\Controllers\RoomController@chat');

Route::get('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@index');

Route::post('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@create');

Route::get('/home/mypage/createroom2', 'App\Http\Controllers\CreateRoom2Controller@index');

Route::get('/ajax/getUserOwnImgs', 'App\Http\Controllers\CreateRoom2Controller@getUserOwnImgs');

Route::get('/ajax/getDefaultImgs', 'App\Http\Controllers\CreateRoom2Controller@getDefaultImgs');

Route::post('/ajax/uploadImg', 'App\Http\Controllers\CreateRoom2Controller@saveImgFile');

Route::post('/ajax/uploadAudio', 'App\Http\Controllers\CreateRoom2Controller@saveAudioFile');

Route::post('/ajax/deleteImg', 'App\Http\Controllers\CreateRoom2Controller@deleteImgFile');

Route::post('/ajax/deleteAudio', 'App\Http\Controllers\CreateRoom2Controller@deleteAudio');

Route::get('/ajax/getUserOwnAudioThumbnails', 'App\Http\Controllers\CreateRoom2Controller@getUserOwnAudioThumbnails');

Route::get('/ajax/getDefaultAudioThumbnails', 'App\Http\Controllers\CreateRoom2Controller@getDefaultAudioThumbnails');

Route::get('/ajax/getDefaultAudios', 'App\Http\Controllers\CreateRoom2Controller@getDefaultAudios');

Route::get('/ajax/getUserOwnAudios', 'App\Http\Controllers\CreateRoom2Controller@getUserOwnAudios');

// defaultImgアップロード用 後で消す
Route::get('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@view');

Route::post('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@uploadFile');

Route::post('/ajax/uploadDefaultImg', 'App\Http\Controllers\CreateRoom2Controller@saveDefaultImgFile');

// phpinfo確認用 後で消す
Route::get('/home/phpinfo', function () {
    return view('phpinfo');
});