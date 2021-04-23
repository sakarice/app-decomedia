<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserOwnImgTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\UserOwnImg::create([
            'owner_user_id' => 1,
            'name' => 'test_img',
            'img_path' => 'test_img_path',
            'img_url' => 'test_img_url'
        ]);
    }
}
