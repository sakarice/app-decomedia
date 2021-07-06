<?php

namespace Database\Factories;

use App\Models\UserOwnImg;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserOwnImgFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserOwnImg::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'onwer_user_id' => Str::random(1),
            'name' => $this->faker->name,
            'img_path' => Str::random(10),
            'img_url' => Str::random(10),        ];
        }
}
