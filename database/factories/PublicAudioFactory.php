<?php

namespace Database\Factories;

use App\Models\PublicAudio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublicAudioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PublicAudio::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_user_id' => NULL,
            'name' => $this->faker->name,
            'audio_path' => Str::random(10),
            'audio_url' => Str::random(10),
            'thumbnail_path' => Str::random(10),
            'thumbnail_url' => Str::random(10),
        ];
    }
}
