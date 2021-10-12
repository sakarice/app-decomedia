<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomMovie;

use App\Http\Controllers\RoomMovieController;

class RoomMovieControllerTest extends TestCase
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
    // 引数として渡したルームIDとルーム画像情報がDB保存されること。
    public function test_store(){
        // 1. 登録用データ準備
        //    登録したいデータをリクエスト形式で作成
        $room_movie_data = array(
            'videoId' => 1,
            'width' => 1000,
            'height' => 1000,
            'isLoop' => true,
            'layer' => 1,
        );
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->movie = $room_movie_data;
        $room_id = mt_rand(1, 2147483647); // 適当なルームID

        // 2. 登録
        //    requestのデータを指定したルームIDに紐づくルーム画像情報として保存
        RoomMovieController::store($room_id, $request);

        // 3. 検証
    }

    // 4.show
    // 引数のルームIDに対応したルーム画像情報がDBから取得できること
    public function test_show(){
        // 1. 取得対象データ登録
        //    ダミーデータ登録
        $room = Room::factory()->create();
        $room_movie = RoomMovie::factory()->create();
        $room_id = $room_movie->room_id;
        \Log::info($room_id);

        // 2. 取得
        //    作成したダミーデータを取得
        $room_movie_data = RoomMovieController::show($room_id);

        // 3. 検証

    }


}
