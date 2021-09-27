<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\UserOwnImg;

use App\Lib\ImgUtil;

class UserOwnImgUtilTest extends TestCase
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
    public function test_judgeIsOwnImg(){
        // 【準備】保存する画像ファイルの情報を作成
        $fileDatas = array(
            'owner_user_id' => NULL,
            'name' => "test",
            'path' => "test",
            'url' => "test",
        );


    }

}
