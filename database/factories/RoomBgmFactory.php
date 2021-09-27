<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Room;
use App\Models\RoomBgm;
use App\Models\DefaultBgm;
use App\Models\UserOwnBgm;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomBgmFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RoomBgm::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(){
        $room_id = Room::max('id');
        $owner_user_id = User::max('id');
        $default_bgm_id = DefaultBgm::max('id');
        $room_audio_data = [
            'room_id' => $room_id,
            'audio_type' => 1,  // UserOwn
            'audio_id' => $default_bgm_id,
            'order_seq' => 1,
            'volume' => 1,
            'isLoop' => true,
            'owner_user_id' => $owner_user_id,
        ];
        return $room_audio_data;
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
                'audio_type' => 1,  // Default
                'audio_id' => DefaultBgm::max('id'),
            ];
        });
    }

    // ユーザが所持する音楽を登録する場合
    public function userown(): Factory
    {
        return $this->state(function() {
            return [
                'audio_type' => 2,  // userOwn
                'audio_id' => UserOwnBgm::max('id'),
            ];
        });
    }

}
