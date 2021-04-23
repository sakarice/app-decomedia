<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomImgTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザがアップロードした画像ファイルを登録
        \App\Models\RoomImg::create([
            'room_id' => 1,
            'img_id' => 1,
            'owner_user_id' => 1,
        ]);
        // デフォルトの画像ファイルを登録
        \App\Models\RoomImg::create([
            'room_id' => 1,
            'img_id' => 1,
            'owner_user_id' => NULL,
        ]);

    }
}
