<?php

namespace Tests\Unit\Util;

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

use App\Lib\MediaImgUtil;

class MediaImgUtilTest extends TestCase
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
    // 引数として渡したメディアIDとメディア画像情報がDB保存されること。
    public function test_saveMediaImgData(){
        // 1. 登録用データ準備
        //    登録したいデータをリクエスト形式で作成
        $media_img_id = mt_rand(1, 2147483647);
        $media_img_data = array(
            'img_id' => $media_img_id,
            'img_type' => 1,
            'width' => 1000,
            'height' => 1000,
            'opacity' => 1,
            'layer' => 1,
        );
        $request = new \stdClass(); //key:value形式のリクエスト
        $request->img = $media_img_data;
        $media_id = mt_rand(1, 2147483647); // 適当なメディアID

        // 2. 登録
        //    requestのデータを指定したメディアIDに紐づくメディア画像情報として保存
        MediaImgUtil::saveMediaImgData($media_id, $request->img);

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

    // 指定したメディアIDでデフォルトのメディア画像情報を登録する
    public function test_saveTentativeMediaImgData(){
        // 1. 対象メディアIDの準備
        $media_id = mt_rand(1, 2147483647); // 適当なメディアID
        // 2. 登録
        MediaImgUtil::saveTentativeMediaImgData($media_id);
        // 3. 検証
        //    関数内で指定の値がデータベースに存在するかチェック
        $this->assertDatabaseHas('media_imgs',[
            'media_id' => $media_id,
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
    // 引数のメディアIDに対応したメディア画像情報がDBから取得できること
    public function test_getMediaImgData(){
        // 準備：対象のメディア、メディアと紐づける画像データ作成
        $media = Media::factory()->create();
        $default_img = PublicImg::factory()->create();

        // // パターン1 メディア画像未設定のためDBにはデフォルト値が保存されている
        // // 1. メディアID取得（※メディア画像のimg_idを0に設定）
        // $media_img = MediaImg::factory()->create(['img_id' => 0]);
        // $media_id = MediaImg::max('media_id');
        // \Log::info($media_img);
        // // 2. メディア画像データ取得
        // $media_img_data = MediaImgUtil::getMediaImgData($media_id);
        // // 3. 検証：データが取得できていること(中身はここでは問わない)
        // $this->assertArrayHasKey('id', $media_img_data);

        // パターン2 メディア画像が登録されている
        // 1. 取得対象データ登録
        $media_img = MediaImg::factory()->create();
        $media_img_setting = MediaImgSetting::factory()->create();
        $media_id = MediaImg::max('media_id');
        // 2. メディア画像データ取得
        $media_img_data = MediaImgUtil::getMediaImgData($media_id);
        // 3. 検証
        //    ダミーデータと取得したデータが一致すること
        $this->assertDatabaseHas('media_imgs', [
            'img_id' => $media_img_data[0]['img_id'],
        ]);
    }


    // 6. update
    // 引数のメディアIDに対応したレコードを、引数のメディア画像情報で更新できること
    public function test_updateMediaImgData() {
        // 1. 更新対象データ登録
        //    ダミーデータ登録
        $media = Media::factory()->create();
        $default_img = PublicImg::factory()->create();
        $media_img = MediaImg::factory()->create();
        MediaImgSetting::factory()->create();
        $media_id = MediaImg::max('media_id');

        // 2. 更新用データ作成
        //    データをリクエスト形式で作成
        $media_img_id = mt_rand(1, 2147483647);
        $media_img_data = array(
            'type' => 2,
            'id' => $media_img_id,
            'width' => 1001,
            'height' => 1001,
            'opacity' => 1.1,
            'layer' => 1.1,
        );
        // $request = new \stdClass(); // key:value形式のリクエストを用意
        // $request->img = $media_img_data;

        // 3. 更新
        //    指定したメディアIDのレコードをrequestの値で更新する
        MediaImgUtil::updateMediaImgData($media_id, $media_img_data);

        // 4. 検証
        //    DBのデータが更新用データと一致すること
        $this->assertDatabaseHas('media_imgs',[
            'width' => 1001,
            'height' => 1001,
            'opacity' => 1.1,
            'img_layer' => 1.1,
        ]);
    }


    // 引数のメディアIDに対応したレコードを、デフォルト値に更新する
    public function test_updateMediaImgDataToTentative() {
        // 1. 更新対象データ登録
        $media = Media::factory()->create();
        $default_img = PublicImg::factory()->create();
        $media_img = MediaImg::factory()->create();
        MediaImgSetting::factory()->create();
        $media_id = MediaImg::max('media_id');

        // 2. 更新：指定したメディアIDのレコードをrequestの値で更新する
        MediaImgUtil::updateMediaImgDataToTentative($media_id);

        // 3. 検証：DBのデータが関数で指定しているデータと一致すること
        $this->assertDatabaseHas('media_imgs',[
            'media_id' => $media_id,
            'img_id' => 0,
            'img_type' => 0,
            'width' => 500,
            'height' => 500,
            'opacity' => 1,
            'img_layer' => 1,
        ]);
    }

    // 仮のMedia画像情報を作成
    public function test_getEmptyMediaImgData(){
        // 1. 取得
        $media_img_data = MediaImgUtil::getEmptyMediaImgData();
        // 2. 検証：取得したデータがgetEmptyMediaImgData関数で設定している値と一致すること
        $this->assertEquals($media_img_data['id'], "");
        $this->assertEquals($media_img_data['type'], 0);
        $this->assertEquals($media_img_data['url'], "");
        $this->assertEquals($media_img_data['width'], 500);
        $this->assertEquals($media_img_data['height'], 500);
        $this->assertEquals($media_img_data['opacity'], "1");
        $this->assertEquals($media_img_data['layer'], 1);
    }

    // Media画像のModelを取得:タイプに応じて取得先DBを選択
    public function test_getMediaImgModel(){
        //  準備：デフォルト画像とユーザアップロード画像をDBに登録
        $default_img = PublicImg::factory()->create();
        $user_own_img = UserOwnImg::factory()->create();

        // パターン1 デフォルト画像
        // 1. 取得
        $media_img_data = MediaImgUtil::getMediaImgModel($default_img->id, 1);
        // 2. 検証：デフォルト画像は所有者ユーザIDがNULL
        $this->assertNull($media_img_data['owner_user_id']);

        // パターン2 ユーザアップロード画像
        // 1. 取得
        $media_img_data = MediaImgUtil::getMediaImgModel($user_own_img->id, 2);
        // 2. 検証：デフォルト画像は所有者ユーザIDがNULLでない
        $this->assertNotNull($media_img_data['owner_user_id']);
    }



}
