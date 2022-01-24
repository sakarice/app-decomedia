<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\StereoPhonicArrange;
use App\Models\MediaAudio;
use Illuminate\Database\Eloquent\Factories\Factory;

class StereoPhonicArrangeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StereoPhonicArrange::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(){
        $media_audio_id = MediaAudio::max('id');
        
        $stereo_phonic_arrange_data = [
            'media_audio_id' => $media_audio_id,
            'panningFlag' => 0,
            'panningModel' => 'equalpower',
            'positionX' => 0,
            'positionY' => 0,
            'positionZ' => 0,
        ];
        return $stereo_phonic_arrange_data;
    }

    /**
     * Define the model's default state.
     *
     * @return Factory
     */

}
