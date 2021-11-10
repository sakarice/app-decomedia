<?php

namespace Database\Factories;

use App\Models\UserOwnAudio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserOwnAudioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserOwnAudio::class;

    /**
     * Define the model's userownbgm state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_user_id' => mt_rand(1, 2147483647),
            'name' => $this->faker->name,
            'audio_path' => Str::random(10),
            'audio_url' => Str::random(10),
            'thumbnail_path' => Str::random(10),
            'thumbnail_url' => Str::random(10),
        ];
    }
}
