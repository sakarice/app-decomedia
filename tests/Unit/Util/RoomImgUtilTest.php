<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomImg;
use App\Models\DefaultImg;
use App\Models\UserOwnImg;

use App\Lib\RoomImgUtil;

class RoomImgUtilTest extends TestCase
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
    // 引数として渡したルームIDとルーム画像情報がDB保存されること。
    public function test_saveRoomImgData(){
        // 1. 登録用データ準備
        //    登録したいデータをリクエスト形式で作成
        $room_img_id = mt_rand(1, 2147483647);
        $room_img_data = array(
            'type' => 1,
            'id' => $room_img_id,
            'width' => 1000,
            'height' => 1000,
            'opacity' => 1,
            'layer' => 1,
        );
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->img = $room_img_data;
        $room_id = mt_rand(1, 2147483647); // 適当なルームID

        // 2. 登録
        //    requestのデータを指定したルームIDに紐づくルーム画像情報として保存
        RoomImgUtil::saveRoomImgData($room_id, $request);

        // 3. 検証
        //    指定の値がデータベースに存在するかチェック
        $this->assertDatabaseHas('room_imgs',[
            'width' => 1000,
            'height' => 1000,
            'opacity' => 1,
            'owner_user_id' => Auth::user()->id,
            'img_layer' => 1,
        ]);
    }

    // 指定したルームIDでデフォルトのルーム画像情報を登録する
    public function test_saveTentativeRoomImgData(){
        // 1. 対象ルームIDの準備
        $room_id = mt_rand(1, 2147483647); // 適当なルームID
        // 2. 登録
        RoomImgUtil::saveTentativeRoomImgData($room_id);
        // 3. 検証
        //    関数内で指定の値がデータベースに存在するかチェック
        $this->assertDatabaseHas('room_imgs',[
            'room_id' => $room_id,
            'owner_user_id' => Auth::user()->id,
            'img_id' => 0,
            'img_type' => 0,
            'width' => 500,
            'height' => 500,
            'opacity' => 1,
            'img_layer' => 1,
        ]);
    }

    // 4.show
    // 引数のルームIDに対応したルーム画像情報がDBから取得できること
    public function test_getRoomImgData(){
        // 準備：対象のルーム、ルームと紐づける画像データ作成
        $room = Room::factory()->create();
        $default_img = DefaultImg::factory()->create();

        // パターン1 ルーム画像未設定のためDBにはデフォルト値が保存されている
        // 1. ルームID取得（※ルーム画像のimg_idを0に設定）
        $room_img = RoomImg::factory()->create(['img_id' => 0]);
        $room_id = RoomImg::max('room_id');
        // 2. ルーム画像データ取得
        $room_img_data = RoomImgUtil::getRoomImgData($room_id);
        // 3. 検証：データが取得できていること(中身はここでは問わない)
        $this->assertArrayHasKey('id', $room_img_data);

        // パターン2 ルーム画像が登録されている
        // 1. 取得対象データ登録
        $room_img = RoomImg::factory()->create();
        $room_id = RoomImg::max('room_id');
        // 2. ルーム画像データ取得
        $room_img_data = RoomImgUtil::getRoomImgData($room_id);
        // 3. 検証
        //    ダミーデータと取得したデータが一致すること
        $this->assertDatabaseHas('room_imgs', [
            'img_id' => $room_img_data['id'],
        ]);
    }


    // 6. update
    // 引数のルームIDに対応したレコードを、引数のルーム画像情報で更新できること
    public function test_updateRoomImgData() {
        // 1. 更新対象データ登録
        //    ダミーデータ登録
        $room = Room::factory()->create();
        $default_img = DefaultImg::factory()->create();
        $room_img = RoomImg::factory()->create();
        $room_id = RoomImg::max('room_id');

        // 2. 更新用データ作成
        //    データをリクエスト形式で作成
        $room_img_id = mt_rand(1, 2147483647);
        $room_img_data = array(
            'type' => 2,
            'id' => $room_img_id,
            'width' => 1001,
            'height' => 1001,
            'opacity' => 1.1,
            'layer' => 1.1,
        );
        $request = new \stdClass(); // key:value形式のリクエストを用意
        $request->img = $room_img_data;

        // 3. 更新
        //    指定したルームIDのレコードをrequestの値で更新する
        RoomImgUtil::updateRoomImgData($room_id, $request);

        // 4. 検証
        //    DBのデータが更新用データと一致すること
        $this->assertDatabaseHas('room_imgs',[
            'width' => 1001,
            'height' => 1001,
            'opacity' => 1.1,
            'img_layer' => 1.1,
        ]);
    }


    // 引数のルームIDに対応したレコードを、デフォルト値に更新する
    public function test_updateRoomImgDataToTentative() {
        // 1. 更新対象データ登録
        $room = Room::factory()->create();
        $default_img = DefaultImg::factory()->create();
        $room_img = RoomImg::factory()->create();
        $room_id = RoomImg::max('room_id');

        // 2. 更新：指定したルームIDのレコードをrequestの値で更新する
        RoomImgUtil::updateRoomImgDataToTentative($room_id);

        // 3. 検証：DBのデータが関数で指定しているデータと一致すること
        $this->assertDatabaseHas('room_imgs',[
            'room_id' => $room_id,
            'img_id' => 0,
            'img_type' => 0,
            'width' => 500,
            'height' => 500,
            'opacity' => 1,
            'img_layer' => 1,
        ]);
    }

    // 仮のRoom画像情報を作成
    public function test_getEmptyRoomImgData(){
        // 1. 取得
        $room_img_data = RoomImgUtil::getEmptyRoomImgData();
        // 2. 検証：取得したデータがgetEmptyRoomImgData関数で設定している値と一致すること
        $this->assertEquals($room_img_data['id'], "");
        $this->assertEquals($room_img_data['type'], 0);
        $this->assertEquals($room_img_data['url'], "");
        $this->assertEquals($room_img_data['width'], 500);
        $this->assertEquals($room_img_data['height'], 500);
        $this->assertEquals($room_img_data['opacity'], "1");
        $this->assertEquals($room_img_data['layer'], 1);
    }

    // Room画像のModelを取得:タイプに応じて取得先DBを選択
    public function test_getRoomImgModel(){
        //  準備：デフォルト画像とユーザアップロード画像をDBに登録
        $default_img = DefaultImg::factory()->create();
        $user_own_img = UserOwnImg::factory()->create();

        // パターン1 デフォルト画像
        // 1. 取得
        $room_img_data = RoomImgUtil::getRoomImgModel($default_img->id, 1);
        // 2. 検証：デフォルト画像は所有者ユーザIDがNULL
        $this->assertNull($room_img_data['owner_user_id']);

        // パターン2 ユーザアップロード画像
        // 1. 取得
        $room_img_data = RoomImgUtil::getRoomImgModel($user_own_img->id, 2);
        // 2. 検証：デフォルト画像は所有者ユーザIDがNULLでない
        $this->assertNotNull($room_img_data['owner_user_id']);
    }



}
