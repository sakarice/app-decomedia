<?php

namespace Database\Factories;

use App\Models\RoomSetting;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomSettingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoomSetting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $room_id = Room::max('id');

        $room_setting_data = [
            'room_id' => $room_id,
            'open_state' => true,
            'name' => "test_room",
            'description' => "test_room_desu",
            'finish_time' => 100,
            'is_show_img' => true,
            'is_show_movie' => true,
            'max_audio_num' => 5,
            'background_color' => '#FFFFFF'
        ];

        return $room_setting_data;
    }
}
