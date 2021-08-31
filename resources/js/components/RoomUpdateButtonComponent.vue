<template>

    <div class="action-button-wrapper">
      <button class="room-update-button" @click="updateRoom">
        更新
      </button>
      <p v-show="message != ''">
        {{message}}
      </p>
    </div>


</template>

<script>
  export default {
    props : [],
    data : () => {
      return {
        'message' : "",
      }
    },

    methods : {
      updateRoom() {
        this.$emit('update-room');
        // this.$emit('getFinishTime');
        // const url = '/home/room/update';
        // let room_datas = {
        //   'img' : this.$parent.roomImg,
        //   'audios' : this.$parent.roomAudios,
        //   'movie' : this.$parent.roomMovie,
        //   'setting' : this.$parent.roomSetting,
        // }
        // this.message = "room情報を更新中です...";
        // axios.post(url, room_datas)
        //   .then(response =>{
        //     alert(response.data.message);
        //     this.message = "";
        //   })
        //   .catch(error => {            
        //     alert('failed!');
        //     this.message = "";
        //   })
      }

    },

  }


</script>


<style scoped>

  .action-button-wrapper {
    margin: 0 5px;
  }

  .room-update-button {
    z-index: 1;
    font-family: Inter,Noto Sans JP;
    border-radius: 4px;
    border: solid 1px grey;
    box-shadow: 0.5px 0.5px 1px lightslategrey;
  }

  .room-update-button:hover {
    background-color: aqua;
  }

</style>