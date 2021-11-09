<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Media;
use App\Models\MediaAudio;
use App\Models\PublicAudio;
use App\Models\UserOwnAudio;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaAudioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MediaAudio::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(){
        $room_id = Media::max('id');
        $owner_user_id = User::max('id');
        $default_bgm_id = PublicAudio::max('id');
        $media_audio_data = [
            'room_id' => $room_id,
            'audio_type' => 1,  // UserOwn
            'audio_id' => $default_bgm_id,
            'order_seq' => 1,
            'volume' => 1,
            'isLoop' => true,
            'owner_user_id' => $owner_user_id,
        ];
        return $media_audio_data;
    }


    /**
     * Define the model's default state.
     *
     * @return Factory
     */
    // デフォルトの音楽を登録する場合
    public function default(): Factory
    {
        return $this->state(function(){
            return [
                'audio_type' => 1,  // Public
                'audio_id' => PublicAudio::max('id'),
            ];
        });
    }

    // ユーザが所持する音楽を登録する場合
    public function userown(): Factory
    {
        return $this->state(function() {
            return [
                'audio_type' => 2,  // userOwn
                'audio_id' => UserOwnAudio::max('id'),
            ];
        });
    }

}
