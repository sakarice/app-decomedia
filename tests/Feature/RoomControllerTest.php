<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class RoomControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        // 認証済みユーザを作成
        // $user = factory(User::class)->create();
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/room/create');

        $response->assertStatus(200);
    }
}
