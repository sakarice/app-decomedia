<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Media;
use App\Models\MediaAudio;
use App\Models\PublicAudio;
use App\Models\UserOwnAudio;

use App\Lib\MediaAudioUtil;

class MediaAudioUtilTest extends TestCase
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
    // 引数として渡したメディアIDとメディア音楽情報がDB保存されること。
    public function test_saveMediaAudioData(){
        // 0. 【準備】音楽情報を登録
        Media::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();

        // 1. 登録用データ準備
        //    登録したいデータをリクエスト形式で作成
        $media_audio_datas = array();
        for($i=1; $i<=2; $i++){
            $media_audio_id = mt_rand(1, 2147483647);
            $tmp_media_audio = array(
                'type' => $i,
                'volume' => 1,
                'isLoop' => true,
            );
            switch ($i) {
                case 1:
                    $tmp_media_audio['audio_url']
                     = PublicAudio::latest()->first()->audio_url;
                    break;
                case 2:
                    $tmp_media_audio['audio_url']
                     = UserOwnAudio::latest()->first()->audio_url;
                    break;
            }
            $media_audio_datas[] = $tmp_media_audio;
        }
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->audios = $media_audio_datas;
        $media_id = mt_rand(1, 2147483647); // 適当なメディアID

        // 2. 登録
        //    requestのデータを指定したメディアIDに紐づくメディア音楽情報として保存
        MediaAudioUtil::saveMediaAudioData($media_id, $request);

        // 3. 検証
        //    指定の値がデータベースに存在するかチェック
        for($i=1; $i<=2; $i++){
            $this->assertDatabaseHas('media_audios',[
                'media_id' => $media_id,
                'audio_type' => $i,
                'order_seq' => $i,
                'volume' => 1,
                'isLoop' => true,
            ]);
        }
    }


    public function test_getMediaAudioData(){
        // 0. 【準備】音楽情報を登録
        Media::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();
        $sample_media_audio_datas = array();
        $sample_media_audio_datas[] = MediaAudio::factory()->default()->create();
        $sample_media_audio_datas[] = MediaAudio::factory()->userown()->create(['order_seq'=>2]);
        $media_id = $sample_media_audio_datas[0]->media_id;
        $media_audio_datas = MediaAudioUtil::getMediaAudioData($media_id);

        foreach($media_audio_datas as $index => $media_audio_data){
            $this->assertDatabaseHas('media_audios',[
               'audio_type' => $media_audio_data['type'], 
               'audio_id' => $media_audio_data['id'], 
               'order_seq' => $index + 1,
               'volume' => $media_audio_data['volume'], 
               'isLoop' => $media_audio_data['isLoop'], 
            ]);
        }
    }


    public function test_equalizeNumOfMediaAudioDataWithRequest(){
        // 0.【準備】mediaAudioを2つ作成
        Media::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();
        MediaAudio::factory()->default()->create();
        MediaAudio::factory()->userown()->create(['order_seq'=>2]);
        $media_id = MediaAudio::max('media_id');

        // パターン1:mediaAudioが減る場合(2つ⇒1つ)
        MediaAudioUtil::equalizeNumOfMediaAudioDataWithRequest($media_id, 1);
        $media_audio_num = MediaAudio::where('media_id', $media_id)->count();
        $this->assertEquals($media_audio_num, 1);

        // パターン2:mediaAudioが増える場合(1つ⇒3つ)
        MediaAudioUtil::equalizeNumOfMediaAudioDataWithRequest($media_id, 3);
        $media_audio_num = MediaAudio::where('media_id', $media_id)->count();
        $this->assertEquals($media_audio_num, 3);
    }


    public function test_updateMediaAudioData(){
        // 0.【準備】更新対象のmediaAudioを作成
        Media::factory()->create();
        PublicAudio::factory()->create();
        UserOwnAudio::factory()->create();
        MediaAudio::factory()->default()->create();
        MediaAudio::factory()->userown()->create(['order_seq'=>2]);
        $media_id = MediaAudio::max('media_id');

        // パターン1:requestのmediaAudioが更新前より少ない(更新前:2つ 更新後:1つ)
        $media_audio_datas = array();
        $tmp_media_audio = array(
            'type' => 1,
            'volume' => 0.1,
            'isLoop' => false,
            'audio_url' => PublicAudio::latest()->first()->audio_url,
        );
        $media_audio_datas[] = $tmp_media_audio;        
        $request1 = new \stdClass(); //key:value形式のリクエスト
        $request1->audios = $media_audio_datas;

        // 2. 更新
        MediaAudioUtil::updateMediaAudioData($media_id, $request1);

        // 3. 【検証】
        // mediaAudioの数が1つになっていること
        $media_audio_num = MediaAudio::where('media_id', $media_id)->count();
        $this->assertEquals($media_audio_num, 1);
        $this->assertDatabaseHas('media_audios', [
            'audio_type' => 1,
            'volume' => 0.1,
            'isLoop' => 0,
            'owner_user_id' => NULL,
        ]);

        // パターン2:requestのmediaAudioが更新前より多い(更新前:1つ 更新後:2つ)
        // 1. 更新用リクエストを用意
        $tmp_media_audio['type'] = 2;
        $tmp_media_audio['audio_url'] = userOwnAudio::latest()->first()->audio_url;
        $media_audio_datas[] = $tmp_media_audio;
        
        $request2 = new \stdClass(); //key:value形式のリクエスト
        $request2->audios = $media_audio_datas;

        // 2. 更新
        MediaAudioUtil::updateMediaAudioData($media_id, $request2);

        // 3. 【検証】
        // mediaAudioの数が2つになっていること
        $media_audio_num = MediaAudio::where('media_id', $media_id)->count();
        $this->assertEquals($media_audio_num, 2);
        $this->assertDatabasehas('media_audios', [
            'audio_type' => 1,
            'volume' => 0.1,
            'isLoop' => false,
            'owner_user_id' => NULL,
        ]);
        $this->assertDatabasehas('media_audios', [
            'audio_type' => 2,
            'volume' => 0.1,
            'isLoop' => false,
            'owner_user_id' => $this->user->id,
        ]);


        // パターン3:requestにmediaAudioが設定されていない場合
        $request3 = new \stdClass(); //key:value形式のリクエスト
        // 2. 更新
        MediaAudioUtil::updateMediaAudioData($media_id, $request3);

        // 3. 【検証】
        // mediaAudioがDBに存在しないこと
        $media_audio_num = MediaAudio::where('media_id', $media_id)->count();
        $this->assertEquals($media_audio_num, 0);
    }


    public function test_addEmptyMediaAudioData(){
        $media_id = mt_rand(1, 2147483647);
        MediaAudioUtil::addEmptyMediaAudioData($media_id);
        $this->assertDatabaseHas('media_audios', [
            'media_id' => $media_id,
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
        $public_audio = PublicAudio::factory()->create();
        $public_audio_url = $public_audio->audio_url;        
        $expected_audio_id = $public_audio->id;
        $actual_audio_id = MediaAudioUtil::getAudioId(1, $public_audio_url);
        $this->assertEquals($expected_audio_id, $actual_audio_id);

        // パターン2:ユーザがアップロードした音楽
        $user_own_audio = UserOwnAudio::factory()->create();
        $user_own_audio_url = $user_own_audio->audio_url;
        $expected_audio_id = $user_own_audio->id;
        $actual_audio_id = MediaAudioUtil::getAudioId(2, $user_own_audio_url);
        $this->assertEquals($expected_audio_id, $actual_audio_id);
    }

}
