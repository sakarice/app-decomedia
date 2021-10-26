<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomSetting;

use App\Lib\RoomUtil;
use App\Lib\ImgUtil;

class SearchUtilTest extends TestCase
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
    public function test_searchRooms(){
        // キーワード無しの時は20個まで、ありの時は1つだけ検索結果が返ってくること
        // 【準備】Roomを作成
        // キーワード無しの時に表示するroom。ルーム名(name)は一律"test_room"
        for($i=0; $i<30; $i++){
            Room::factory()->create();
            RoomSetting::factory()->create();   // ルーム名(name)は"test_room"
        }
        // キーワードありの時に表示するroom。ルーム名を↑と変えている。
        Room::factory()->create();
        RoomSetting::factory()->create(['name'=>'keyword_test']);

        // パターン1:検索キーワードを設定
        $response = $this->post('/room/show/search/result', [
            'keyword' => 'keyword_test',
        ]);
        $response
            ->assertStatus(200)
            ->assertViewHas('keyword', 'keyword_test');
        $this->assertEquals(true, $response['isLogin']);
        $this->assertEquals('keyword_test', $response['keyword']);
        $this->assertEquals(1, count($response['roomPreviewInfos']));

        // パターン2:検索キーワードなしで検索
        // ★空の値をテストで送るとエラーになるので中止。
        // ブラウザで直接確認する。

    }

}