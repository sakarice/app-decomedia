<template>

    <div class="action-button-wrapper">
      <button class="room-create-button" @click="createRoom">
        作成
      </button>
      <p>
        {{createRoomMessage}}
      </p>
    </div>


</template>

<script>
  export default {
    props : [],
    data : () => {
      return {
        'createRoomMessage' : "",
      }
    },

    methods : {
      createRoom() {
        const url = '/home/room/store';
        let room_datas = {
          'img' : this.$parent.roomImg,
          'audios' : this.$parent.roomAudios,
          'movie' : this.$parent.roomMovie,
          'setting' : this.$parent.roomSetting,
        }
        this.createRoomMessage = "room情報を保存中です...";
        axios.post(url, room_datas)
          .then(response =>{
            alert(response.data.message);
            this.createRoomMessage = "";
          })
          .catch(error => {            
            alert('failed!');
            this.createRoomMessage = "";
          })

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
    top : 20px;
    right: 150px;
    z-index: 1;
    font-family: Inter,Noto Sans JP;
    border-radius: 4px;
    border: solid 1px grey;
    box-shadow: 0.5px 0.5px 1px lightslategrey;
  }

  .room-create-button:hover {
    background-color: aqua;
  }

</style>