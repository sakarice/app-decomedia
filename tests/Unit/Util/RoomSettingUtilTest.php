<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\MediaSetting;

use App\Lib\MediaSettingUtil;

class MediaSettingUtilTest extends TestCase
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
    public function test_saveMediaSettingData(){
        // 0. 【準備】登録用データ作成
        $room_setting_data = array(
            'isPublic' => true,
            // 'name' => "test_room",
            'description' => "test_room_desu",
            'finish_time' => 100,
            'isShowImg' => true,
            'isShowMovie' => true,
            'maxAudioNum' => 5,
            'roomBackgroundColor' => '#FFFFFF'
        );

        // パターン1 ルーム名未設定
        // 1. 登録用request作成
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->setting = $room_setting_data;
        $room_id = mt_rand(1, 2147483647); // 適当なルームID
        // 2. 登録：requestのデータを指定したルームIDに紐づく動画情報として保存
        MediaSettingUtil::saveMediaSettingData($room_id, $request);
        // 3. 検証：登録用に準備したレコードがDBに存在するかチェック
        $this->assertDatabaseHas('room_settings',[
            'room_id' => $room_id,
            'open_state' => true,
            'name' => "room",  // デフォルトのroom名
            'description' => "test_room_desu",
            'finish_time' => 100,
            'is_show_img' => true,
            'is_show_movie' => true,
            'max_audio_num' => 5,
            'background_color' => '#FFFFFF'
        ]);

        // パターン2 ルーム名設定あり
        // 1. 登録用request作成
        $request = new \stdClass(); //key:value形式のリクエスト
        // ★room名を追加↓
        $room_setting_data['name'] = "test_room";
        $request->setting = $room_setting_data;
        $room_id = mt_rand(1, 2147483647); // 適当なルームID
        // 2. 登録：requestのデータを指定したルームIDに紐づく動画情報として保存
        MediaSettingUtil::saveMediaSettingData($room_id, $request);
        // 3. 検証：登録用に準備したレコードがDBに存在するかチェック
        $this->assertDatabaseHas('room_settings',[
            'room_id' => $room_id,
            'open_state' => true,
            'name' => "test_room",
            'description' => "test_room_desu",
            'finish_time' => 100,
            'is_show_img' => true,
            'is_show_movie' => true,
            'max_audio_num' => 5,
            'background_color' => '#FFFFFF'
        ]);
    }

    // 4.show
    // 引数のルームIDに対応した動画情報がDBから取得できること
    public function test_getMediaSettingData(){
        // 1. 取得対象データ登録
        //    ダミーデータ登録
        $room = Room::factory()->create();
        $room_setting = MediaSetting::factory()->create();
        $room_id = $room_setting->room_id;

        // 2. 取得
        //    作成したダミーデータを取得
        $room_setting_data = MediaSettingUtil::getMediaSettingData($room_id);

        // 3. 検証
        //    ダミーデータと取得したデータが一致すること
        $this->assertDatabaseHas('room_settings', [
            'room_id' => $room_id,
            'open_state' => true,
            'name' => "test_room",
            'description' => "test_room_desu",
            'finish_time' => 100,
            'is_show_img' => true,
            'is_show_movie' => true,
            'max_audio_num' => 5,
            'background_color' => '#FFFFFF'
        ]);
    }

    // 6.update
    // 指定したルームIDに対応した動画情報のレコードをrequestの値で更新する。
    public function test_updateMediaSettingData(){
        // 1. 更新対象データ登録
        //    ダミーデータ登録
        $room = Room::factory()->create();
        $room_setting = MediaSetting::factory()->create();
        // 更新対象のルームID取得
        $room_id = $room_setting->room_id;

        // 2. 更新用データ準備
        //    更新したいデータをリクエスト形式で作成
        $room_setting_data = array(
            'isPublic' => false,
            'name' => "updated_room",
            'description' => "update_room_desu",
            'finish_time' => 999,
            'isShowImg' => false,
            'isShowMovie' => false,
            'maxAudioNum' => 99,
            'roomBackgroundColor' => '#999999'
        );
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->setting = $room_setting_data;

        // 3. 更新
        $room_setting_data = MediaSettingUtil::updateMediaSettingData($room_id, $request);

        // 4. 検証
        // 更新したレコードの内容が更新用データの値と一致すること
        $this->assertDatabaseHas('room_settings', [
            'room_id' => $room_id,
            'open_state' => false,
            'name' => "updated_room",
            'description' => "update_room_desu",
            'finish_time' => 999,
            'is_show_img' => false,
            'is_show_movie' => false,
            'max_audio_num' => 99,
            'background_color' => '#999999'
        ]);


    }

}
