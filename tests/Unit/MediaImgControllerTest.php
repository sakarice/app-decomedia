<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Media;
use App\Models\MediaImg;
use App\Models\MediaImgSetting;
use App\Models\PublicImg;
use App\Models\UserOwnImg;

use App\Http\Controllers\MediaImgController;

class MediaImgControllerTest extends TestCase
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
    public function test_example()
    {
        $this->assertTrue(true);
    }

    // 3.store
    // 引数として渡したメディアIDとメディア画像情報がDB保存されること。
    public function test_store(){
        // 1. 登録用データ準備
        //    登録したいデータをリクエスト形式で作成
        $public_img = PublicImg::factory()->create();
        $media_img_id = $public_img->id;
        $media_img_data = array(
            'type' => 99,
            'groupNo' => null,
            'img_type' => 1,
            'img_id' => $media_img_id,
            'left' => 1000,
            'top' => 1000,
            'width' => 1000,
            'height' => 1000,
            'degree' => 0,
            'opacity' => 1,
            'layer' => 1,
        );
        $media_id = mt_rand(1, 2147483647); // 適当なメディアID
        $send_data = array($media_img_data);
        // 2. 登録
        //    requestのデータを指定したメディアIDに紐づくメディア画像情報として保存
        MediaImgController::store($media_id, $send_data);

        // 3. 検証
        //    指定の値がデータベースに存在するかチェック
        $this->assertDatabaseHas('media_imgs',[
            'width' => 1000,
            'height' => 1000,
            'opacity' => 1,
            'owner_user_id' => Auth::user()->id,
            'img_layer' => 1,
        ]);
    }

    // 4.show
    // 引数のメディアIDに対応したメディア画像情報がDBから取得できること
    public function test_show(){
        // 1. 取得対象データ登録
        //    ダミーデータ登録
        $media = Media::factory()->create();
        $public_img = PublicImg::factory()->create();
        $media_img = MediaImg::factory()->create();
        MediaImgSetting::factory()->create();
        $media_id = $media_img->media_id;

        // 2. 取得
        //    作成したダミーデータを取得
        $media_img_datas = MediaImgController::show($media_id);

        // 3. 検証
        //    ダミーデータと取得したデータが一致すること
        $this->assertDatabaseHas('media_imgs', [
            'img_id' => $media_img_datas[0]['img_id'],
        ]);
    }


    // 6. update
    // 引数のメディアIDに対応したレコードを、引数のメディア画像情報で更新できること
    public function test_update() {
        // 1. 更新対象データ登録
        //    ダミーデータ登録
        $media = Media::factory()->create();
        $public_img = PublicImg::factory()->create();
        $media_img = MediaImg::factory()->create();
        MediaImgSetting::factory()->create();
        $id = $media_img->id;
        $media_id = $media_img->media_id;
        $media_img_id = $public_img->id;

        // 2. 更新用データ作成
        //    データをリクエスト形式で作成
        $media_img_data = array(
            'id' => $id,
            'type' => 99,
            'groupNo' => NULL,
            'img_type' => 1,
            'img_id' => $media_img_id,
            'left' => 1001,
            'top' => 1001,
            'degree' => 0,
            'width' => 1001,
            'height' => 1001,
            'opacity' => 0.9,
            'layer' => 2,
        );
        $send_data = array($media_img_data);

        // 3. 更新
        //    指定したメディアIDのレコードをrequestの値で更新する
        MediaImgController::update($media_id, $send_data);

        // 4. 検証
        //    DBのデータが更新用データと一致すること
        $this->assertDatabaseHas('media_imgs',[
            'id' => $id,
            'media_id' => $media_id,
            'img_type' => 1,
            'img_id' => $media_img_id,
            'width' => 1001,
            'height' => 1001,
            'opacity' => 0.9,
            'img_layer' => 2,
        ]);
    }

    // 7. destroy
    // 引数で指定したメディアIDのレコードを削除する。
    public function test_destroy(){
        // 1. 削除対象データ登録
        //    ダミーデータ作成
        $media = Media::factory()->create();
        $public_img = PublicImg::factory()->create();
        $media_img = MediaImg::factory()->create();
        $media_id = MediaImg::max('media_id');

        // 2. 削除
        //    指定したメディアIDのデータを削除
        MediaImgController::destroy($media_id);

        // 3. 検証
        //    削除対象データがDBに存在しないこと
        $this->assertDatabaseMissing('media_imgs', [
            'media_id' => $media_id,
        ]);
    }
}
