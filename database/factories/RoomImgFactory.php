<?php

namespace Database\Factories;

use App\Models\RoomImg;
use App\Models\Room;
use App\Models\DefaultImg;
use App\Models\UserOwnImg;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomImgFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoomImg::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $room_id = Room::max('id');
        $img_type = 1;
        $img_id = DefaultImg::max('id');

        $room_img_data = [
            'room_id' => $room_id,
            'img_type' => $img_type,
            'img_id' => $img_id,
            'width' => 500,
            'height' => 500,
            'opacity' => 1,
            'owner_user_id' => NULL,
            'img_layer' => 1,
        ];

        return $room_img_data;
    }
}
