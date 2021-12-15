<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Media;
use App\Models\MediaMovie;

use App\Http\Controllers\MediaMovieController;

class MediaMovieControllerTest extends TestCase
{
    // DBをクリア（各テスト毎にクリア）
    use RefreshDatabase;

    private $user;

    // 認証済みユーザを作成
    public function setUp(): void {
        parent::setUp();
        // ユーザを作成
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    // 3.store
    // 引数として渡したメディアIDとメディア画像情報がDB保存されること。
    public function test_store(){
        // 1. 登録用データ準備
        //    登録したいデータをリクエスト形式で作成
        $media_movie_data = array(
            'videoId' => 1,
            'left' => 1000,
            'top' => 1000,
            'width' => 1000,
            'height' => 1000,
            'opacity' => 1,
            'isLoop' => true,
            'layer' => 1,
        );
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->movie = $media_movie_data;
        $media_id = mt_rand(1, 2147483647); // 適当なメディアID

        // 2. 登録
        //    requestのデータを指定したメディアIDに紐づくメディア画像情報として保存
        MediaMovieController::store($media_id, $request);

        // 3. 検証
    }

    // 4.show
    // 引数のメディアIDに対応したメディア画像情報がDBから取得できること
    public function test_show(){
        // 1. 取得対象データ登録
        //    ダミーデータ登録
        $media = Media::factory()->create();
        $media_movie = MediaMovie::factory()->create();
        $media_id = $media_movie->media_id;

        // 2. 取得
        //    作成したダミーデータを取得
        $media_movie_data = MediaMovieController::show($media_id);

        // 3. 検証

    }


}
