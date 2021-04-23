<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomlistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Roomlist::create([
            'name' => 'test_roomlist'
        ]);
    }
}
