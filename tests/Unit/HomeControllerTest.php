<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\MediaSetting;

class HomeControllerTest extends TestCase
{
    // DBをクリア（テスト後にクリアしている）
    use RefreshDatabase;

    private $user;

    // 認証済みユーザを作成
    public function setUp(): void {
        parent::setUp();
        // ユーザを作成
        $this->user = User::factory()->create();
    }
    /**
     * A basic unit test example.
     *
     * @return void
     */

    public function test_index(){

    }


}
