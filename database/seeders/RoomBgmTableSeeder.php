<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomBgmTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザがアップロードした音源ファイルを登録
        \App\Models\RoomBgm::create([
            'room_id' => 1,
            'audio_id' => 1,
            'audio_order_seq' => 1,
            'owner_user_id' => 1
        ]);
        // デフォルトの音源ファイルを登録
        \App\Models\RoomBgm::create([
            'room_id' => 1,
            'audio_id' => 1,
            'audio_order_seq' => 2,
            'owner_user_id' => NULL
        ]);

    }
}
