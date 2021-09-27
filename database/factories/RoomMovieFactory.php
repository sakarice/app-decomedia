<?php

namespace Database\Factories;

use App\Models\RoomMovie;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomMovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoomMovie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $room_id = Room::max('id');
        $user_id = User::max('id');

        $room_movie_data = [
            'user_id' => $user_id, 
            'room_id' => $room_id,
            'video_id' => mt_rand(1, 2147483647),
            'width' => 500,
            'height' => 500,
            'isLoop' => true,
            'movie_layer' => 1,
        ];

        return $room_movie_data;
    }
}
