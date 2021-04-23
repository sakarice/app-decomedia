<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomRoomlistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\RoomRoomlist::create([
            'roomlist_id' => 1,
            'room_id' => 1,
            'room_order_seq' => 1
        ]);

    }
}
