<?php

namespace Database\Factories;

use App\Models\DefaultBgm;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DefaultBgmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DefaultBgm::class;

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
