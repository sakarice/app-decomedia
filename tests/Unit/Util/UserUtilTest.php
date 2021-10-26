<?php

namespace Tests\Unit\Util;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Room;

use App\Lib\UserUtil;


class UserUtilTest extends TestCase
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

    // ログインユーザ自身のユーザ情報を取得
    // ※↓のtest_getUserDataと同じ
    public function test_getOwnProfile(){
        $user_id = $this->user->id;
        $user_data = UserUtil::getOwnProfile($user_id);
        $this->assertDatabaseHas('users', [
            'id' => $user_data['id'],
            'name' => $user_data['name'],
            'profile' => $user_data['aboutMe'],
        ]);
    }


    // room所有者(=作成者)のユーザ情報を取得
    public function test_getRoomOwnerData(){
        $room_id = Room::factory()->create(['user_id'=> $this->user->id])->id;
        $room_owner_user_id = Room::find($room_id)->user_id;
        $user_data = UserUtil::getRoomOwnerData($room_id);
        $this->assertDatabaseHas('users', [
            'id' => $room_owner_user_id,
        ]);
    }


    // (ログイン中の)ユーザ情報を取得(id,ユーザ名,プロフィール文)
    public function test_getUserData(){
        $user_id = $this->user->id;
        $user_data = UserUtil::getUserData($user_id);
        $this->assertDatabaseHas('users', [
            'id' => $user_data['id'],
            'name' => $user_data['name'],
            'profile' => $user_data['aboutMe'],
        ]);
    }

}