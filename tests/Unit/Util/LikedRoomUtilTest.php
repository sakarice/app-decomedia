<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\UserLikeRoom;

use App\Lib\LikeRoomUtil;

class LikeRoomUtilTest extends TestCase
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

    // 引数として渡したルームIDと動画情報がDB保存されること。
    public function test_updateLikeState(){
        // 0. いいねするルームのidを作成
        $room_id = mt_rand(1, 2147483647); // 適当なルームID

        // パターン1 いいねされた場合
        // 1. requestを送信
        $response = $this->postJson('/room/like', [
            'isLike' => true,
            'room_id' => $room_id,
        ]);
        // 2. 検証：いいねしたユーザとルームのレコードがDBに存在すること
        $this->assertDatabaseHas('user_like_rooms',[
            'user_id' => $this->user->id,
            'room_id' => $room_id,
        ]);

        // パターン2 いいね解除された場合
        // 1. requestを送信
        $response = $this->postJson('/room/like', [
            'isLike' => false,
            'room_id' => $room_id,
        ]);
        // 2. 検証：いいね解除されたユーザとルームのレコードがDBから消えていること
        $this->assertDatabaseMissing('user_like_rooms',[
            'user_id' => $this->user->id,
            'room_id' => $room_id,
        ]);
    }

}
