<?php

namespace Database\Factories;

use App\Models\MediaImg;
use App\Models\Media;
use App\Models\PublicImg;
use App\Models\UserOwnImg;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaImgFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaImg::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $media_id = Media::max('id');
        $img_type = 1;
        $img_id = PublicImg::max('id');

        $media_img_data = [
            'media_id' => $media_id,
            'img_type' => $img_type,
            'img_id' => $img_id,
            'width' => 500,
            'height' => 500,
            'opacity' => 1,
            'owner_user_id' => NULL,
            'img_layer' => 1,
        ];

        return $media_img_data;
    }
}
