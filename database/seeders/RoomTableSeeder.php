<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Room::create([
            'user_id' => 1,
            'name' => 'testRoom'
        ]);
    }
}
