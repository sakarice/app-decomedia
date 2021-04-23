<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DefaultImgTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\DefaultImg::create([
            'owner_user_id' => NULL,
            'name' => 'test_img',
            'img_path' => 'test_img_path',
            'img_url' => 'test_img_url'
        ]);
    }
}
