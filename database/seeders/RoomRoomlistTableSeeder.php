<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MediaMedialistTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        \App\Models\MediaMedialist::create([
            'medialist_id' => 1,
            'media_id' => 1,
            'media_order_seq' => 1
        ]);

    }
}
