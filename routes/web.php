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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// 前の仕様(trackとroomで分けている時)
Route::post('/home/mypage/createroom', 'App\Http\Controllers\Room2Controller@createRoom');
Route::post('/home/mypage/deleteroom', 'App\Http\Controllers\Room2Controller@deleteRoom');
Route::post('/home/mypage/showroom', 'App\Http\Controllers\Room2Controller@showRoom');
Route::post('/home/mypage/showroom/getTrackInfo', 'App\Http\Controllers\Room2Controller@getTrackInfo');
Route::post('/home/mypage/showroom/updateRoom', 'App\Http\Controllers\Room2Controller@updateRoom');
Route::post('/home/searchResult', 'App\Lib\SearchUtil@searchRooms');
Route::post('/home/enterRoom', 'App\Http\Controllers\Room2Controller@enterRoom');
Route::post('/home/enterRoom/getTrackInfo', 'App\Http\Controllers\Room2Controller@getTrackInfo');


// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Mypage
Route::get('/home/mypage', 'App\Http\Controllers\MypageController@view');
Route::get('/home/mypage/profile', 'App\Http\Controllers\MypageController@profile');


// ★今の仕様
Route::get('/home/room/create', 'App\Http\Controllers\Room\RoomController@create');
Route::post('home/room/store', 'App\Http\Controllers\Room\RoomController@store');
Route::get('/home/room/{id}', 'App\Http\Controllers\Room\RoomController@show');
Route::get('/home/room/{id}/edit', 'App\Http\Controllers\Room\RoomController@edit');
Route::post('/home/room/update', 'App\Http\Controllers\Room\RoomController@update');
Route::post('/home/room/delete', 'App\Http\Controllers\Room\RoomController@destroy');

// Chat
Route::get('/home/chat', 'App\Http\Controllers\Room2Controller@chat');
Route::get('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@index');
Route::post('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@create');

// Ajax
Route::get('/ajax/getUserOwnImgs', 'App\Http\Controllers\Img\UserOwnImgController@index');
Route::get('/ajax/getDefaultImgs', 'App\Http\Controllers\Img\DefaultImgController@index');
Route::post('/ajax/uploadImg', 'App\Http\Controllers\Img\ImgController@store');
Route::post('/ajax/deleteImg', 'App\Http\Controllers\Img\UserOwnImgController@destroy');
Route::get('/ajax/getDefaultAudios', 'App\Http\Controllers\Audio\DefaultAudioController@index');
Route::get('/ajax/getUserOwnAudios', 'App\Http\Controllers\Audio\UserOwnAudioController@index');
Route::post('/ajax/uploadAudio', 'App\Http\Controllers\Audio\AudioController@store');
Route::post('/ajax/deleteAudio', 'App\Http\Controllers\Audio\UserOwnAudioController@destroy');

// Route::get('/ajax/getUserOwnAudioThumbnails', 'App\Http\Controllers\Ajax\RoomController@getUserOwnAudioThumbnails');
// Route::get('/ajax/getDefaultAudioThumbnails', 'App\Http\Controllers\Ajax\RoomController@getDefaultAudioThumbnails');


// defaultImgアップロード(開発用、後で消す)
Route::get('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@view');
Route::post('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@uploadFile');
Route::post('/ajax/uploadDefaultImg', 'App\Http\Controllers\CreateRoom2Controller@saveDefaultImgFile');

// phpinfo(確認用、後で消す)
Route::get('/home/phpinfo', function () {
    return view('phpinfo');
});