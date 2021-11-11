<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;

class MediaControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // 2. create
    public function testCreate(){
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/media/create');
        $response->assertStatus(200);
    }

    // // 5. edit
    // public function testEdit(){
    //     $user = User::factory()->create();
    //     $this->actingAs($user);
    //     $response = $this->get('/media/1/edit');
    //     $response->assertStatus(200);
    // }

}
