<?php

namespace Database\Factories;

use App\Models\MediaMovie;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaMovieFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaMovie::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $media_id = Media::max('id');
        $user_id = User::max('id');

        $media_movie_data = [
            'user_id' => $user_id, 
            'media_id' => $media_id,
            'video_id' => mt_rand(1, 2147483647),
            'width' => 500,
            'height' => 500,
            'isLoop' => true,
            'movie_layer' => 1,
        ];

        return $media_movie_data;
    }
}
