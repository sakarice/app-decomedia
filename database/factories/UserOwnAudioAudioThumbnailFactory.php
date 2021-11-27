<?php

namespace Database\Factories;

use App\Models\UserOwnAudioAudioThumbnail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\UserOwnAudio;
use App\Models\UserOwnAudioThumbnail;

class UserOwnAudioAudioThumbnailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserOwnAudioAudioThumbnail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'audio_id' => UserOwnAudio::max('id'),
            'audio_thumbnail_id' => UserOwnAudioThumbnail::max('id'),
        ];
    }
}
