<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for($i = 1; $i <= 10; $i ++){
            \App\Models\Message::create([
                'user_id' => 1,
                'room_id' => 1,
                'message' => $i.'番目のテキスト'
            ]);
        }
    }
}
