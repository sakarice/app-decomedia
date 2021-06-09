<template>

    <div class="action-button-wrapper">
      <button class="room-create-button" @click="createRoom">
        作成
      </button>
      {{createRoomMessage}}
    </div>


</template>

<script>
  export default {
    props : [
      'roomImg',
      'roomAudios',
      'moviePlayerSettings',
      'roomSetting'
    ],
    data : () => {
      return {
        'createRoomMessage' : "",
      }
    },

    methods : {
      createRoom() {
        const url = '/ajax/room/create';
        let room_datas = {
          'img' : this.roomImg,
          'audios' : this.roomAudios,
          'movie' : this.moviePlayerSettings,
          'setting' : this.roomSetting,
        }
        axios.post(url, room_datas)
          .then(response =>{
            alert(response.data.message);
          })
          .catch(error => {
            
            alert('failed!');
          })

        // alert(room_datas['img']['url']);

      }

    },

  }


</script>


<style scoped>

  .action-button-wrapper {
    width: 50%;
    height: 50%;
    position: absolute;
    top: 0;
    right: 0;
  }

  .room-create-button {
    position: absolute;
    top : 50px;
    right: 60px;
    z-index: 1;
    font-family: Inter,Noto Sans JP;
  }

</style>