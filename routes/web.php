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
    // 入っているMediaが自分のMediaかチェック
        Route::post('/checkIsMyMedia', 'App\Http\Controllers\Ajax\Lib\MediaUtilAjax@judgeIsMyMedia');
    // マイページ
        Route::get('/mypage', 'App\Http\Controllers\MypageController@view');
        Route::get('/mypage/profile', 'App\Http\Controllers\MypageController@profile');
    // ユーザ情報(プロフィール)
        Route::resource('users', 'App\Http\Controllers\UserController');
        Route::get('/user/getOwnProfile', 'App\Lib\UserUtil@getOwnProfile');
        Route::put('/user/{id}', 'App\Http\Controllers\UserController@update');
    // マイページで、作成済みMediaをもっと見る
        Route::get('/addCreatedMediaPreviewInfos', 'App\Lib\MediaUtil@getCreatedMediaPreviewInfos');
        Route::get('/addLikedMediaPreviewInfos', 'App\Lib\MediaUtil@getLikedMediaPreviewInfos');    
    // マイページからMediaを選択し、手早くMediaリストを作成する
        Route::post('/medialists/store', 'App\Http\Controllers\Ajax\MediaListController@quickStore');
    // マイページから選択したMediaを削除する(他ユーザのMediaは除外)
        Route::post('/medias/destroy', 'App\Http\Controllers\Ajax\MediaController@destroy');
    // 作成したMediaリストのプレビュー情報を取得
        Route::get('/ajax/addCreatedMediaListPreviewInfos/{num}', 'App\Http\Controllers\Ajax\MediaListController@getMediaListPreviewInfos');
    // 作成したMediaリストを削除
        Route::post('/mediaLists/delete', 'App\Http\Controllers\Ajax\MediaListController@destroy');

    // Media操作
        // 通常のCRUDルーティング
        Route::get('/media/create', 'App\Http\Controllers\MediaController@create');
        Route::post('/media/store', 'App\Http\Controllers\MediaController@store');
        // Route::get('/media/{id}/edit', 'App\Http\Controllers\MediaController@edit');
        Route::post('/media/update', 'App\Http\Controllers\MediaController@update');
        Route::post('/media/delete', 'App\Http\Controllers\MediaController@destroy');
        // Route::get('/media/{id}', 'App\Http\Controllers\MediaController@show');
        // vue-router用の定義
        Route::get('/media/{any}', function(){return view('medias.show');})->where('any', '.*');

        // 入ったMediaをいいねしているかチェックする
        Route::get('/user/likeState/{media_id}', 'App\Lib\LikeMediaUtil@getLikeState');
    // Mediaへ、いいね/いいね解除する
        Route::post('/media/like', 'App\Lib\LikeMediaUtil@updateLikeState');
    // 自分が入ったMediaの作成者をフォローしているかチェックする
        Route::get('/user/followState/{media_owner_id}', 'App\Lib\FollowUtil@getFollowState');
    // メディア作成者をフォロー/フォロー解除する
        Route::post('/user/follow', 'App\Lib\FollowUtil@updateFollowState');
    
    // メディア画像
        Route::get('/mediaImg/{mediaId}', 'App\Lib\MediaImgUtil@getMediaImgData');
    // メディア音楽
        Route::get('/mediaAudios/{mediaId}', 'App\Lib\MediaAudioUtil@getMediaAudioData');
    // メディア動画
        Route::get('/mediaMovie/{mediaId}', 'App\Lib\MediaMovieUtil@getMediaMovieData');
    // メディア設定
        Route::get('/mediaSetting/{mediaId}', 'App\Lib\MediaSettingUtil@getMediaSettingData');

    // Ajax
        Route::get('/ajax/getUserOwnImgs', 'App\Http\Controllers\UserOwnImgController@index');
        Route::get('/ajax/getPublicImgs', 'App\Http\Controllers\PublicImgController@index');
        Route::post('/ajax/uploadImg', 'App\Http\Controllers\ImgController@store');
        Route::post('/ajax/deleteImg', 'App\Http\Controllers\UserOwnImgController@destroy');
        Route::get('/ajax/getPublicAudios', 'App\Http\Controllers\PublicAudioController@index');
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
    // Route::get('/home', function(){ return view('home');});
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// // ★vur-routerテスト用
//     Route::get('/home/vue', function(){
//         return view('vue');
//     });

// 検索結果
    Route::post('/media/show/search/result', 'App\Lib\SearchUtil@searchMedias');

// 入ったmediaが自分の作成したmediaか判別する
    Route::get('/ajax/judgeIsMyMedia/{media_id}', 'App\Http\Controllers\Ajax\Lib\MediaUtilAjax@judgeIsMyMedia');

// mediaの作成者情報を表示
    Route::get('/user/mediaOwner/profile/show/{media_id}', 'App\Lib\UserUtil@getMediaOwnerData');
// publicImgアップロード(開発用、後で消す)
    Route::get('/uploadPublicFiles', 'App\Lib\Common\Functions@view');
    Route::post('/uploadPublicFiles', 'App\Lib\Common\Functions@uploadFile');

// phpinfo(確認用、後で消す)
    Route::get('/home/phpinfo', function () {
        return view('phpinfo');
    });






