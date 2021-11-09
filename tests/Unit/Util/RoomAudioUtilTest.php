<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomAudio;
use App\Models\PublicAudio;
use App\Models\UserOwnAudio;

use App\Lib\RoomAudioUtil;

class RoomAudioUtilTest extends TestCase
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
    // 引数として渡したルームIDとルーム音楽情報がDB保存されること。
    public function test_saveRoomAudioData(){
        // 0. 【準備】音楽情報を登録
        Room::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();

        // 1. 登録用データ準備
        //    登録したいデータをリクエスト形式で作成
        $room_audio_datas = array();
        for($i=1; $i<=2; $i++){
            $room_audio_id = mt_rand(1, 2147483647);
            $tmp_room_audio = array(
                'type' => $i,
                'volume' => 1,
                'isLoop' => true,
            );
            switch ($i) {
                case 1:
                    $tmp_room_audio['audio_url']
                     = PublicAudio::latest()->first()->audio_url;
                    break;
                case 2:
                    $tmp_room_audio['audio_url']
                     = UserOwnAudio::latest()->first()->audio_url;
                    break;
            }
            $room_audio_datas[] = $tmp_room_audio;
        }
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->audios = $room_audio_datas;
        $room_id = mt_rand(1, 2147483647); // 適当なルームID

        // 2. 登録
        //    requestのデータを指定したルームIDに紐づくルーム音楽情報として保存
        RoomAudioUtil::saveRoomAudioData($room_id, $request);

        // 3. 検証
        //    指定の値がデータベースに存在するかチェック
        for($i=1; $i<=2; $i++){
            $this->assertDatabaseHas('room_bgms',[
                'room_id' => $room_id,
                'audio_type' => $i,
                'order_seq' => $i,
                'volume' => 1,
                'isLoop' => true,
            ]);
        }
    }


    public function test_getRoomAudioData(){
        // 0. 【準備】音楽情報を登録
        Room::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();
        $sample_room_audio_datas = array();
        $sample_room_audio_datas[] = RoomAudio::factory()->default()->create();
        $sample_room_audio_datas[] = RoomAudio::factory()->userown()->create(['order_seq'=>2]);
        $room_id = $sample_room_audio_datas[0]->room_id;
        $room_audio_datas = RoomAudioUtil::getRoomAudioData($room_id);

        foreach($room_audio_datas as $index => $room_audio_data){
            $this->assertDatabaseHas('room_bgms',[
               'audio_type' => $room_audio_data['type'], 
               'audio_id' => $room_audio_data['id'], 
               'order_seq' => $index + 1,
               'volume' => $room_audio_data['volume'], 
               'isLoop' => $room_audio_data['isLoop'], 
            ]);
        }
    }


    public function test_equalizeNumOfRoomAudioDataWithRequest(){
        // 0.【準備】roomAudioを2つ作成
        Room::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();
        RoomAudio::factory()->default()->create();
        RoomAudio::factory()->userown()->create(['order_seq'=>2]);
        $room_id = RoomAudio::max('room_id');

        // パターン1:roomAudioが減る場合(2つ⇒1つ)
        RoomAudioUtil::equalizeNumOfRoomAudioDataWithRequest($room_id, 1);
        $room_audio_num = RoomAudio::where('room_id', $room_id)->count();
        $this->assertEquals($room_audio_num, 1);

        // パターン2:roomAudioが増える場合(1つ⇒3つ)
        RoomAudioUtil::equalizeNumOfRoomAudioDataWithRequest($room_id, 3);
        $room_audio_num = RoomAudio::where('room_id', $room_id)->count();
        $this->assertEquals($room_audio_num, 3);
    }


    public function test_updateRoomAudioData(){
        // 0.【準備】更新対象のroomAudioを作成
        Room::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();
        RoomAudio::factory()->default()->create();
        RoomAudio::factory()->userown()->create(['order_seq'=>2]);
        $room_id = RoomAudio::max('room_id');

        // パターン1:requestのroomAudioが更新前より少ない(更新前:2つ 更新後:1つ)
        $room_audio_datas = array();
        $tmp_room_audio = array(
            'type' => 1,
            'volume' => 0.1,
            'isLoop' => false,
            'audio_url' => PublicAudio::latest()->first()->audio_url,
        );
        $room_audio_datas[] = $tmp_room_audio;        
        $request1 = new \stdClass(); //key:value形式のリクエスト
        $request1->audios = $room_audio_datas;

        // 2. 更新
        RoomAudioUtil::updateRoomAudioData($room_id, $request1);

        // 3. 【検証】
        // roomAudioの数が1つになっていること
        $room_audio_num = RoomAudio::where('room_id', $room_id)->count();
        $this->assertEquals($room_audio_num, 1);
        $this->assertDatabaseHas('room_bgms', [
            'audio_type' => 1,
            'volume' => 0.1,
            'isLoop' => 0,
            'owner_user_id' => NULL,
        ]);

        // パターン2:requestのroomAudioが更新前より多い(更新前:1つ 更新後:2つ)
        // 1. 更新用リクエストを用意
        $tmp_room_audio['type'] = 2;
        $tmp_room_audio['audio_url'] = userOwnBgm::latest()->first()->audio_url;
        $room_audio_datas[] = $tmp_room_audio;
        
        $request2 = new \stdClass(); //key:value形式のリクエスト
        $request2->audios = $room_audio_datas;

        // 2. 更新
        RoomAudioUtil::updateRoomAudioData($room_id, $request2);

        // 3. 【検証】
        // roomAudioの数が2つになっていること
        $room_audio_num = RoomAudio::where('room_id', $room_id)->count();
        $this->assertEquals($room_audio_num, 2);
        $this->assertDatabasehas('room_bgms', [
            'audio_type' => 1,
            'volume' => 0.1,
            'isLoop' => false,
            'owner_user_id' => NULL,
        ]);
        $this->assertDatabasehas('room_bgms', [
            'audio_type' => 2,
            'volume' => 0.1,
            'isLoop' => false,
            'owner_user_id' => $this->user->id,
        ]);


        // パターン3:requestにroomAudioが設定されていない場合
        $request3 = new \stdClass(); //key:value形式のリクエスト
        // 2. 更新
        RoomAudioUtil::updateRoomAudioData($room_id, $request3);

        // 3. 【検証】
        // roomAudioがDBに存在しないこと
        $room_audio_num = RoomAudio::where('room_id', $room_id)->count();
        $this->assertEquals($room_audio_num, 0);
    }


    public function test_addEmptyRoomAudioData(){
        $room_id = mt_rand(1, 2147483647);
        RoomAudioUtil::addEmptyRoomAudioData($room_id);
        $this->assertDatabaseHas('room_bgms', [
            'room_id' => $room_id,
            'audio_type' => 0,
            'audio_id' => 0,
            'order_seq' => -1,
            'volume' => 1,
            'isLoop' => false,
            'owner_user_id' => $this->user->id,
        ]);
    }

    public function test_getAudioId(){
        // パターン1:デフォルト音楽
        $public_bgm = PublicAudio::factory()->create();
        $public_bgm_url = $public_bgm->audio_url;        
        $expected_audio_id = $public_bgm->id;
        $actual_audio_id = RoomAudioUtil::getAudioId(1, $public_bgm_url);
        $this->assertEquals($expected_audio_id, $actual_audio_id);

        // パターン2:ユーザがアップロードした音楽
        $user_own_bgm = UserOwnAudio::factory()->create();
        $user_own_bgm_url = $user_own_bgm->audio_url;
        $expected_audio_id = $user_own_bgm->id;
        $actual_audio_id = RoomAudioUtil::getAudioId(2, $user_own_bgm_url);
        $this->assertEquals($expected_audio_id, $actual_audio_id);
    }

}
