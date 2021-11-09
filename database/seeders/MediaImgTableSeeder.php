<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MediaImgTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ユーザがアップロードした画像ファイルを登録
        \App\Models\MediaImg::create([
            'room_id' => 1,
            'img_id' => 1,
            'owner_user_id' => 1,
        ]);
        // デフォルトの画像ファイルを登録
        \App\Models\MediaImg::create([
            'room_id' => 1,
            'img_id' => 1,
            'owner_user_id' => NULL,
        ]);

    }
}
