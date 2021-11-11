<?php

namespace Database\Factories;

use App\Models\MediaSetting;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $media_id = Media::max('id');

        $media_setting_data = [
            'media_id' => $media_id,
            'open_state' => true,
            'name' => "test_media",
            'description' => "test_media_desu",
            'finish_time' => 100,
            'is_show_img' => true,
            'is_show_movie' => true,
            'max_audio_num' => 5,
            'background_color' => '#FFFFFF'
        ];

        return $media_setting_data;
    }
}
