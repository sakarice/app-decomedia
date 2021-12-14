<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\MediaImg;
use App\Models\MediaImgSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaImgSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaImgSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $media_img_id = MediaImg::max('id');

        $media_img_setting_data = [
            'media_img_id' => $media_img_id,
            'type' => 99,
            'user_selected_item_group_no' => null,
            'left' => 999,
            'top' => 999,
            'width' => 999,
            'height' => 999,
            'degree' => 0,
            'global_alpha' => 1,
            'layer' => 1,
        ];

        return $media_img_setting_data;
    }
}
