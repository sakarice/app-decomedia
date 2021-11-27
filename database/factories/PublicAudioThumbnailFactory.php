<?php

namespace Database\Factories;

use App\Models\PublicAudioThumbnail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PublicAudioThumbnailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PublicAudioThumbnail::class;

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
            'img_path' => Str::random(10),
            'img_url' => Str::random(10),
        ];
    }
}
