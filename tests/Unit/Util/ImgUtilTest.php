<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\UserOwnImg;
use App\Models\PublicImg;

use App\Lib\ImgUtil;

class ImgUtilTest extends TestCase
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
    public function test_saveImgData(){
        // 【準備】保存する画像ファイルの情報を作成
        $fileDatas = array(
            'owner_user_id' => NULL,
            'name' => "test",
            'path' => "test",
            'url' => "test",
        );

        // パターン1 デフォルト画像
        // 1. 保存
        $saved_img_id = ImgUtil::saveImgData($fileDatas);
        // 2. 検証：保存したレコードがPublicImgテーブルに存在すること
        $this->assertDatabaseHas('public_imgs',[
            'id' => $saved_img_id,
            'owner_user_id' => NULL,
            'name' => "test",
            'img_path' => "test",
            'img_url' => "test",
        ]);

        // パターン2 ユーザがアップロードした画像
        // 0. 所有者のユーザidを設定
        $fileDatas['owner_user_id'] = $this->user->id;
        // 1. 保存
        $saved_img_id = ImgUtil::saveImgData($fileDatas);
        // 2. 検証：保存したレコードがUserOwnImgテーブルに存在すること
        $this->assertDatabaseHas('user_own_imgs',[
            'id' => $saved_img_id,
            'owner_user_id' => $this->user->id,
            'name' => "test",
            'img_path' => "test",
            'img_url' => "test",
        ]);
    }

}
