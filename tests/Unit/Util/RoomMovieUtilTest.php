<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomMovie;

use App\Lib\RoomMovieUtil;

class RoomMovieUtilTest extends TestCase
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
    // 引数として渡したルームIDと動画情報がDB保存されること。
    public function test_saveRoomMovieData(){
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
        //    requestのデータを指定したルームIDに紐づく動画情報として保存
        RoomMovieUtil::saveRoomMovieData($room_id, $request);

        // 3. 検証
        //    指定の値がデータベースに存在するかチェック
        $this->assertDatabaseHas('room_movies',[
            'video_id' => 1,
            'width' => 1000,
            'height' => 1000,
            'isLoop' => true,
            'movie_layer' => 1,
        ]);
    }

    // 4.show
    // 引数のルームIDに対応した動画情報がDBから取得できること
    public function test_getRoomMovieData(){
        // 1. 取得対象データ登録
        //    ダミーデータ登録
        $room = Room::factory()->create();
        $room_movie = RoomMovie::factory()->create();
        $room_id = $room_movie->room_id;
        \Log::info($room_id);

        // 2. 取得
        //    作成したダミーデータを取得
        $room_movie_data = RoomMovieUtil::getRoomMovieData($room_id);

        // 3. 検証
        //    ダミーデータと取得したデータが一致すること
        $this->assertDatabaseHas('room_movies', [
            // 'video_id' => $room_movie_data->videoId,
            'video_id' => $room_movie_data['videoId'],
        ]);
    }

    // 6.update
    // 指定したルームIDに対応した動画情報のレコードをrequestの値で更新する。
    public function test_updateRoomMovieData(){
        // 1. 更新対象データ登録
        //    ダミーデータ登録
        $room = Room::factory()->create();
        $room_movie = RoomMovie::factory()->create();
        // 更新対象のルームID取得
        $room_id = $room_movie->room_id;

        // 2. 更新用データ準備
        //    更新したいデータをリクエスト形式で作成
        $room_movie_data = array(
            'videoId' => 99,
            'width' => 999,
            'height' => 999,
            'isLoop' => false,
            'layer' => 99,
        );
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->movie = $room_movie_data;

        // 3. 更新
        $room_movie_data = RoomMovieUtil::updateRoomMovieData($room_id, $request);

        // 4. 検証
        // 更新したレコードの内容が更新用データの値と一致すること
        $this->assertDatabaseHas('room_movies', [
            'room_id' => $room_id,
            'video_id' => 99,
            'width' => 999,
            'height' => 999,
            'isLoop' => false,
            'movie_layer' => 99,
        ]);


    }

}
