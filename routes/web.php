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

// 前の仕様(trackとroomで分けている時)
Route::get('/home/mypage/createtrack', 'App\Http\Controllers\CreateTrackController@index');
Route::post('/home/mypage/createtrack', 'App\Http\Controllers\CreateTrackController@createTrack');
Route::get('/home/mypage/tracks', 'App\Http\Controllers\TracksController@view');
Route::post('/home/mypage/tracks', 'App\Http\Controllers\TracksController@deleteTrack');
Route::post('/home/mypage/updatetrack', 'App\Http\Controllers\UpdateTrackController@view');
Route::post('/home/mypage/trackupdating', 'App\Http\Controllers\UpdateTrackController@update');
Route::post('/home/mypage/createroom', 'App\Http\Controllers\Room2Controller@createRoom');
Route::post('/home/mypage/deleteroom', 'App\Http\Controllers\Room2Controller@deleteRoom');
Route::post('/home/mypage/showroom', 'App\Http\Controllers\Room2Controller@showRoom');
Route::post('/home/mypage/showroom/getTrackInfo', 'App\Http\Controllers\Room2Controller@getTrackInfo');
Route::post('/home/mypage/showroom/updateRoom', 'App\Http\Controllers\Room2Controller@updateRoom');
Route::post('/home/searchResult', 'App\Http\Controllers\SearchController@searchRooms');
Route::post('/home/enterRoom', 'App\Http\Controllers\Room2Controller@enterRoom');
Route::post('/home/enterRoom/getTrackInfo', 'App\Http\Controllers\Room2Controller@getTrackInfo');


// Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Mypage
Route::get('/home/mypage', 'App\Http\Controllers\MypageController@view');
Route::get('/home/mypage/profile', 'App\Http\Controllers\MypageController@profile');


// ★今の仕様
Route::get('/home/room/create', 'App\Http\Controllers\RoomController@create');
Route::get('/home/room/{id}', 'App\Http\Controllers\RoomController@show');
Route::get('/home/room/{id}/edit', 'App\Http\Controllers\RoomController@edit');

// Chat
Route::get('/home/chat', 'App\Http\Controllers\Room2Controller@chat');
Route::get('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@index');
Route::post('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@create');

// Ajax
Route::get('/ajax/getUserOwnImgs', 'App\Http\Controllers\Ajax\RoomController@getUserOwnImgs');
Route::get('/ajax/getDefaultImgs', 'App\Http\Controllers\Ajax\RoomController@getDefaultImgs');
Route::post('/ajax/uploadImg', 'App\Http\Controllers\Ajax\RoomController@saveImgFile');
Route::post('/ajax/uploadAudio', 'App\Http\Controllers\Ajax\RoomController@saveAudioFile');
Route::post('/ajax/deleteImg', 'App\Http\Controllers\Ajax\RoomController@deleteImgFile');
Route::post('/ajax/deleteAudio', 'App\Http\Controllers\Ajax\RoomController@deleteAudio');
Route::get('/ajax/getUserOwnAudioThumbnails', 'App\Http\Controllers\Ajax\RoomController@getUserOwnAudioThumbnails');
Route::get('/ajax/getDefaultAudioThumbnails', 'App\Http\Controllers\Ajax\RoomController@getDefaultAudioThumbnails');
Route::get('/ajax/getDefaultAudios', 'App\Http\Controllers\Ajax\RoomController@getDefaultAudios');
Route::get('/ajax/getUserOwnAudios', 'App\Http\Controllers\Ajax\RoomController@getUserOwnAudios');
Route::post('/ajax/room/create', 'App\Http\Controllers\Ajax\RoomController@createRoom');
Route::post('/ajax/room/delete', 'App\Http\Controllers\Ajax\RoomController@deleteRoom');
Route::post('/ajax/room/update', 'App\Http\Controllers\Ajax\RoomController@updateRoom');


// defaultImgアップロード(開発用、後で消す)
Route::get('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@view');
Route::post('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@uploadFile');
Route::post('/ajax/uploadDefaultImg', 'App\Http\Controllers\CreateRoom2Controller@saveDefaultImgFile');

// phpinfo(確認用、後で消す)
Route::get('/home/phpinfo', function () {
    return view('phpinfo');
});