<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UsersTableSeeder::class);
        $this->call(ChatsTableSeeder::class);
        $this->call(DefaultBgmTableSeeder::class);
        $this->call(DefaultImgTableSeeder::class);
        $this->call(UserOwnBgmTableSeeder::class);
        $this->call(UserOwnImgTableSeeder::class);
        $this->call(RoomTableSeeder::class);
        $this->call(RoomBgmTableSeeder::class);
        $this->call(RoomImgTableSeeder::class);
        $this->call(RoomlistTableSeeder::class);
        $this->call(RoomRoomlistTableSeeder::class);
    }
}
