<template>

    <div class="action-button-wrapper">
      <button class="action-button room-update-button" @click="updateRoom">
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
        // const url = '/room/update';
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

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

</style>