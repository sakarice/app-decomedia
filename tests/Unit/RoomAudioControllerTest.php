<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomBgm;
use App\Models\DefaultBgm;
use App\Models\UserOwnBgm;
use App\Http\Controllers\RoomAudio\RoomAudioController;

class RoomAudioControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_store(){
        // 認証済みユーザを作成
        // $user = factory(User::class)->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        // 1以上かつint型の最大数となるランダムな整数を用意
        $room_id = mt_rand(1, 2147483647);
        // $room_audio_id = mt_rand(1, 2147483647);

        // Room音楽の情報を作成
        // $room_audios = array();
        $room_audio_data = array(
            'type' => 1,
            'audio_url' => "test_url",
            'volume' => 1,
            'isLoop' => true,
        );
        $room_audios[] = $room_audio_data;

        // key:value形式のリクエストを用意
        $request = new \stdClass();
        $request->audios = $room_audios;

        RoomAudioController::store($room_id, $request);

        // 検証 指定の値がデータベースに存在するかチェック
        $this->assertDatabaseHas('room_bgms',[
            'audio_url' => "test_url",
        ]);        
    }
}
