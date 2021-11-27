<?php

namespace Database\Factories;

use App\Models\UserOwnAudioThumbnail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserOwnAudioThumbnailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserOwnAudioThumbnail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_user_id' => mt_rand(1, 2147483647),
            'name' => $this->faker->name,
            'img_path' => Str::random(10),
            'img_url' => Str::random(10),
        ];
    }
}
