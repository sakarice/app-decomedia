<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\UserController;

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

// Home
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Mypage
Route::get('/home/mypage', 'App\Http\Controllers\MypageController@view');
Route::get('/home/mypage/profile', 'App\Http\Controllers\MypageController@profile');

// ユーザプロフィール
Route::resource('users', 'App\Http\Controllers\UserController');
// ->except([
//     'index' // 複数ユーザのプロフィール一覧を表示することはない
// ]);
Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');

// プロフィールに表示するユーザ情報を取得
Route::get('/user/getProfile', 'App\Lib\UserUtil@getProfile');
// roomの作成者情報を表示
Route::get('/user/getRoomOwnerInfo/{room_id}', 'App\Lib\UserUtil@getRoomOwnerInfo');

// Roomへ「いいね」する
Route::post('/room/like', 'App\Lib\LikeRoomUtil@updateLikeState');

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
// Route::post('/ajax/uploadDefaultImg', 'App\Http\Controllers\CreateRoom2Controller@saveDefaultImgFile');

// phpinfo(確認用、後で消す)
Route::get('/home/phpinfo', function () {
    return view('phpinfo');
});