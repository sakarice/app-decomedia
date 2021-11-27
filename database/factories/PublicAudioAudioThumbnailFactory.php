<?php

namespace Database\Factories;

use App\Models\PublicAudioAudioThumbnail;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\PublicAudio;
use App\Models\PublicAudioThumbnail;

class PublicAudioAudioThumbnailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PublicAudioAudioThumbnail::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'audio_id' => PublicAudio::max('id'),
            'audio_thumbnail_id' => PublicAudioThumbnail::max('id'),
        ];
    }
}
