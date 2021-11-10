<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Media;
use App\Models\UserLikeMedia;

use App\Lib\LikeMediaUtil;

class LikeMediaUtilTest extends TestCase
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

    // 引数として渡したメディアIDと動画情報がDB保存されること。
    public function test_updateLikeState(){
        // 0. いいねするメディアのidを作成
        $media_id = mt_rand(1, 2147483647); // 適当なメディアID

        // パターン1 いいねされた場合
        // 1. requestを送信
        $response = $this->postJson('/media/like', [
            'isLike' => true,
            'media_id' => $media_id,
        ]);
        // 2. 検証：いいねしたユーザとメディアのレコードがDBに存在すること
        $this->assertDatabaseHas('user_like_medias',[
            'user_id' => $this->user->id,
            'media_id' => $media_id,
        ]);

        // パターン2 いいね解除された場合
        // 1. requestを送信
        $response = $this->postJson('/media/like', [
            'isLike' => false,
            'media_id' => $media_id,
        ]);
        // 2. 検証：いいね解除されたユーザとメディアのレコードがDBから消えていること
        $this->assertDatabaseMissing('user_like_medias',[
            'user_id' => $this->user->id,
            'media_id' => $media_id,
        ]);
    }

}
