<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Controllers\UserController;
use App\Models\User;

class UserControllerTest extends TestCase
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


    public function test_show(){
        // // ダミーデータ作成:User
        // $user = User::factory()->create();
        // $this->actingAs($user);
        // $id = $user->id;
        // // ★中間チェック 後で消す。
        // $this->assertDatabaseHas('users', [
        //     'id' => $id,
        // ]);
        // $response = $this->get('/users/'.$id);
        // $response->assertStatus(200);
    }


}
