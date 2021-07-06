<?php

namespace Tests\Unit;

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

use App\Http\Controllers\RoomImg\RoomImgController;

class RoomImgControllerTest extends TestCase
{
    // DBをクリア（テスト後にクリアしている）
    use RefreshDatabase;

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
        $room_img_id = mt_rand(1, 2147483647);

        // Room画像の情報を作成
        $room_img_data = array(
            'type' => 1,
            'id' => $room_img_id,
            'width' => 1000,
            'height' => 1000,
            'opacity' => 1,
            'layer' => 1,
        );

        // key:value形式のリクエストを用意
        $request = new \stdClass();
        $request->img = $room_img_data;

        RoomImgController::store($room_id, $request);

        // 検証 指定の値がデータベースに存在するかチェック
        $this->assertDatabaseHas('room_imgs',[
            'width' => 1000,
            'height' => 1000,
            'opacity' => 1,
            'owner_user_id' => Auth::user()->id,
            'img_layer' => 1,
        ]);
    }

    public function test_show(){
        // ダミーデータ作成:Room
        $room = Room::factory()->create();
        $room_id = $room->id;
        // ★中間チェック 後で消す。
        $this->assertDatabaseHas('rooms', [
            'id' => $room_id,
        ]);

        // ダミーデータ作成:DefaultImg
        $default_img = DefaultImg::factory()->create();
        $default_img_id = $default_img->id;
        $img_type = 1; // DefaultImgのタイプは1
        // ★中間チェック 後で消す。
        $this->assertDatabaseHas('default_imgs', [
            'id' => $default_img_id,
        ]);

        $room_img = RoomImg::factory()->create();
        $room_id = RoomImg::max('room_id');
        $room_img_data = RoomImgController::show($room_id);
        $this->assertDatabaseHas('room_imgs', [
            'img_id' => $room_img_data['id'],
        ]);
    }


    public function test_destroy(){
        $this->test_store();
        $room_id = RoomImg::max('room_id');
        RoomImgController::destroy($room_id);
        $this->assertDatabaseMissing('room_imgs', [
            'room_id' => $room_id,
        ]);

    }
}
