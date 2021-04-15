<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ChatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i = 1; $i <= 3; $i ++){
            \App\Models\Chat::create([
                'user_id' => $i,
                'room_id' => 1,
                'message' => $i.'番目のテキスト'
            ]);
        }
    }
}
