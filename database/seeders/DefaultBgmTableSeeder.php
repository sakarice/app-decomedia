<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DefaultBgmTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\DefaultBgm::create([
            'owner_user_id' => NULL,
            'name' => 'test_audio',
            'audio_path' => 'test_audio_path',
            'audio_url' => 'test_audio_url',
            'thumbnail_path' => 'test_thumbnail_path',
            'thumbnail_url' => 'test_thumbnail_url'
        ]);
    }
}
