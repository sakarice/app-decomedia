<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\PublicAudio;
use App\Models\Room;
use App\Models\RoomImg;
use App\Models\RoomAudio;
use App\Models\RoomMovie;
use App\Models\RoomSetting;

use App\Lib\RoomUtil;

class RoomUtilTest extends TestCase
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

    // 3.store
    // 引数として渡したルームIDと動画情報がDB保存されること。
    public function test_getRoomPreviewImgUrl(){
        // 3パターンある テストしやすいので、パターン3からテストする。
        // パターン1：Room画像が設定されている
        // パターン2：Room画像が設定されてなくて、Room動画が設定されている
        // パターン3：Room画像も動画も設定されてなくて、Room音楽が設定されている
        // 0. 【準備】登録用データ作成

        // パターン3：Room画像も動画も設定されてなくて、Room音楽が設定されている
        $room = Room::factory()->create();
        PublicAudio::factory()->create();
        RoomAudio::factory()->create();
        $room_id = RoomImg::max('room_id');

        // テスト
        $room_img_url = RoomUtil::getRoomPreviewImgUrl($room_id);

        $this->assertEquals(
            $room_img_url,
            "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/tyOKqvszOb4LDP2egK6qTqWFzFiFnxlCurxaf98W.png"
        );

        // パターン2：Room画像が設定されてなくて、Room動画が設定されている
        RoomMovie::factory()->create();
        $room_id = RoomMovie::max('room_id');
        $room_img_url = RoomUtil::getRoomPreviewImgUrl($room_id);
        $this->assertEquals(
            $room_img_url,
            "https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/room/img/3oLdT6SSOkEUW0ejXRWsLX177aXQVOd5vRa8Qtse.png"
        );

    }

}
