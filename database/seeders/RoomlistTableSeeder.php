<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MedialistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\Medialist::create([
            'name' => 'test_medialist'
        ]);
    }
}
