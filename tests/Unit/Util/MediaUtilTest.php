<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\PublicAudio;
use App\Models\Media;
use App\Models\MediaImg;
use App\Models\MediaAudio;
use App\Models\MediaMovie;
use App\Models\MediaSetting;

use App\Lib\MediaUtil;

class MediaUtilTest extends TestCase
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
    // 引数として渡したメディアIDと動画情報がDB保存されること。
    public function test_getMediaPreviewImgUrl(){
        // 3パターンある テストしやすいので、パターン3からテストする。
        // パターン1：Media画像が設定されている
        // パターン2：Media画像が設定されてなくて、Media動画が設定されている
        // パターン3：Media画像も動画も設定されてなくて、Media音楽が設定されている
        // 0. 【準備】登録用データ作成

        // パターン3：Media画像も動画も設定されてなくて、Media音楽が設定されている
        $media = Media::factory()->create();
        PublicAudio::factory()->create();
        MediaAudio::factory()->create();
        $media_id = MediaImg::max('media_id');

        // テスト
        $media_img_url = MediaUtil::getMediaPreviewImgUrl($media_id);

        $this->assertEquals(
            $media_img_url,
            "https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/tyOKqvszOb4LDP2egK6qTqWFzFiFnxlCurxaf98W.png"
        );

        // パターン2：Media画像が設定されてなくて、Media動画が設定されている
        MediaMovie::factory()->create();
        $media_id = MediaMovie::max('media_id');
        $media_img_url = MediaUtil::getMediaPreviewImgUrl($media_id);
        $this->assertEquals(
            $media_img_url,
            "https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/3oLdT6SSOkEUW0ejXRWsLX177aXQVOd5vRa8Qtse.png"
        );

    }

}
