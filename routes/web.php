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
Route::resource('users', 'App\Http\Controllers\User\UserController');
// ->except([
//     'index' // 複数ユーザのプロフィール一覧を表示することはない
// ]);
Route::put('/user/{id}', 'App\Http\Controllers\User\UserController@update');

// プロフィールに表示するユーザ情報を取得
Route::get('/user/getProfile', 'App\Lib\UserUtil@getProfile');
// roomの作成者情報を表示
Route::get('/user/getRoomOwnerInfo/{room_id}', 'App\Lib\UserUtil@getRoomOwnerInfo');

// Roomへ「いいね」する
Route::post('/room/like', 'App\Lib\LikeRoomUtil@updateLikeState');

// 検索結果
Route::post('/searchResult', 'App\Lib\SearchUtil@searchRooms');

// マイページで、作成済みRoomをもっと見る
Route::get('/addCreatedRoomPreviewInfos', 'App\Lib\RoomUtil@getCreatedRoomPreviewInfos');
Route::get('/addLikedRoomPreviewInfos', 'App\Lib\RoomUtil@getLikedRoomPreviewInfos');


// ★今の仕様
Route::get('/home/room/create', 'App\Http\Controllers\Room\RoomController@create');
Route::post('home/room/store', 'App\Http\Controllers\Room\RoomController@store');
Route::get('/home/room/{id}', 'App\Http\Controllers\Room\RoomController@show');
Route::get('/home/room/{id}/edit', 'App\Http\Controllers\Room\RoomController@edit');
Route::post('/home/room/update', 'App\Http\Controllers\Room\RoomController@update');
Route::post('/home/room/delete', 'App\Http\Controllers\Room\RoomController@destroy');

// Chat
Route::get('/home/chat', 'App\Http\Controllers\Chat\ChatController@index');
Route::get('ajax/chat', 'App\Http\Controllers\Ajax\Chat\ChatController@index');
Route::post('ajax/chat', 'App\Http\Controllers\Ajax\Chat\ChatController@create');

// Ajax
Route::get('/ajax/getUserOwnImgs', 'App\Http\Controllers\Img\UserOwnImgController@index');
Route::get('/ajax/getDefaultImgs', 'App\Http\Controllers\Img\DefaultImgController@index');
Route::post('/ajax/uploadImg', 'App\Http\Controllers\Img\ImgController@store');
Route::post('/ajax/deleteImg', 'App\Http\Controllers\Img\UserOwnImgController@destroy');
Route::get('/ajax/getDefaultAudios', 'App\Http\Controllers\Audio\DefaultAudioController@index');
Route::get('/ajax/getUserOwnAudios', 'App\Http\Controllers\Audio\UserOwnAudioController@index');
Route::post('/ajax/uploadAudio', 'App\Http\Controllers\Audio\AudioController@store');
Route::post('/ajax/deleteAudio', 'App\Http\Controllers\Audio\UserOwnAudioController@destroy');
// 入ったroomが自分の作成したroomか判別する
Route::get('/ajax/judgeIsMyRoom/{room_id}', 'App\Http\Controllers\Ajax\Room\RoomController@judgeIsMyRoom');
// マイページからRoomを選択し、手早くRoomリストを作成する
Route::post('/ajax/roomlist/store', 'App\Http\Controllers\Ajax\Roomlist\RoomListController@quickStore');
// マイページから選択したRoomを削除する
Route::post('/ajax/selectedRoom/destroy', 'App\Http\Controllers\Ajax\Room\RoomController@deleteSelectedOwnRooms');

// 作成したRoomリストのプレビュー情報を取得
Route::get('/ajax/addCreatedRoomListPreviewInfos/{num}', 'App\Http\Controllers\Ajax\Roomlist\RoomListController@getRoomListPreviewInfos');

// 作成したRoomリストを削除
Route::post('/ajax/roomList/delete', 'App\Http\Controllers\Ajax\Roomlist\RoomListController@destroy');




// defaultImgアップロード(開発用、後で消す)
Route::get('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@view');
Route::post('/home/uploadDefaultFiles', 'App\Http\Controllers\Functions@uploadFile');
// Route::post('/ajax/uploadDefaultImg', 'App\Http\Controllers\CreateRoom2Controller@saveDefaultImgFile');

// phpinfo(確認用、後で消す)
Route::get('/home/phpinfo', function () {
    return view('phpinfo');
});