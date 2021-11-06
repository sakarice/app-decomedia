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


// ★ログイン認証必須ページ
// ミドルウェアによるログインチェックをかませる
Route::middleware('auth')->group(function(){
    // 入っているRoomが自分のRoomかチェック
        Route::post('/checkIsMyRoom', 'App\Http\Controllers\Ajax\Lib\RoomUtilAjax@judgeIsMyRoom');
    // マイページ
        Route::get('/mypage', 'App\Http\Controllers\MypageController@view');
        Route::get('/mypage/profile', 'App\Http\Controllers\MypageController@profile');
    // ユーザ情報(プロフィール)
        Route::resource('users', 'App\Http\Controllers\UserController');
        Route::get('/user/getOwnProfile', 'App\Lib\UserUtil@getOwnProfile');
        Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
    // マイページで、作成済みRoomをもっと見る
        Route::get('/addCreatedRoomPreviewInfos', 'App\Lib\RoomUtil@getCreatedRoomPreviewInfos');
        Route::get('/addLikedRoomPreviewInfos', 'App\Lib\RoomUtil@getLikedRoomPreviewInfos');    
    // マイページからRoomを選択し、手早くRoomリストを作成する
        Route::post('/roomlists/store', 'App\Http\Controllers\Ajax\RoomListController@quickStore');
    // マイページから選択したRoomを削除する(他ユーザのRoomは除外)
        Route::post('/rooms/destroy', 'App\Http\Controllers\Ajax\RoomController@destroy');
    // 作成したRoomリストのプレビュー情報を取得
        Route::get('/ajax/addCreatedRoomListPreviewInfos/{num}', 'App\Http\Controllers\Ajax\RoomListController@getRoomListPreviewInfos');
    // 作成したRoomリストを削除
        Route::post('/roomLists/delete', 'App\Http\Controllers\Ajax\RoomListController@destroy');

    // Room操作
        Route::get('/room/create', 'App\Http\Controllers\RoomController@create');
        Route::post('/room/store', 'App\Http\Controllers\RoomController@store');
        Route::get('/room/{id}/edit', 'App\Http\Controllers\RoomController@edit');
        Route::post('/room/update', 'App\Http\Controllers\RoomController@update');
        Route::post('/room/delete', 'App\Http\Controllers\RoomController@destroy');
    // 入ったRoomをいいねしているかチェックする
        Route::get('/user/likeState/{room_id}', 'App\Lib\LikeRoomUtil@getLikeState');
    // Roomへ、いいね/いいね解除する
        Route::post('/room/like', 'App\Lib\LikeRoomUtil@updateLikeState');
    // 自分が入ったRoomの作成者をフォローしているかチェックする
        Route::get('/user/followState/{room_owner_id}', 'App\Lib\FollowUtil@getFollowState');
    // ルーム作成者をフォロー/フォロー解除する
        Route::post('/user/follow', 'App\Lib\FollowUtil@updateFollowState');

    // Ajax
        Route::get('/ajax/getUserOwnImgs', 'App\Http\Controllers\UserOwnImgController@index');
        Route::get('/ajax/getDefaultImgs', 'App\Http\Controllers\DefaultImgController@index');
        Route::post('/ajax/uploadImg', 'App\Http\Controllers\ImgController@store');
        Route::post('/ajax/deleteImg', 'App\Http\Controllers\UserOwnImgController@destroy');
        Route::get('/ajax/getDefaultAudios', 'App\Http\Controllers\DefaultAudioController@index');
        Route::get('/ajax/getUserOwnAudios', 'App\Http\Controllers\UserOwnAudioController@index');
        Route::post('/ajax/uploadAudio', 'App\Http\Controllers\AudioController@store');
        Route::post('/ajax/deleteAudio', 'App\Http\Controllers\UserOwnAudioController@destroy');

    // Chat
        Route::get('/home/chat', 'App\Http\Controllers\ChatController@index');
        Route::get('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@index');
        Route::post('ajax/chat', 'App\Http\Controllers\Ajax\ChatController@create');

});

// ★ログイン認証不要ページ
// ユーザがログインしているかチェック
    Route::get('/checkIsLogin', function(){
        return ['isLogin' => Auth::check()];
    });


// Home
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
// 検索結果
    Route::post('/room/show/search/result', 'App\Lib\SearchUtil@searchRooms');

// Room閲覧
    Route::get('/room/{id}', 'App\Http\Controllers\RoomController@show');
// 入ったroomが自分の作成したroomか判別する
    Route::get('/ajax/judgeIsMyRoom/{room_id}', 'App\Http\Controllers\Ajax\Lib\RoomUtilAjax@judgeIsMyRoom');

// roomの作成者情報を表示
    Route::get('/user/roomOwner/profile/show/{room_id}', 'App\Lib\UserUtil@getRoomOwnerData');
// defaultImgアップロード(開発用、後で消す)
    Route::get('/uploadPublicFiles', 'App\Lib\Common\Functions@view');
    Route::post('/uploadPublicFiles', 'App\Lib\Common\Functions@uploadFile');
// Route::post('/ajax/uploadDefaultImg', 'App\Http\Controllers\CreateRoom2Controller@saveDefaultImgFile');

// phpinfo(確認用、後で消す)
    Route::get('/home/phpinfo', function () {
        return view('phpinfo');
    });






