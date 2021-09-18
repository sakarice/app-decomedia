<template>

    <div class="action-button-wrapper">
      <button class="action-button room-create-button" @click="createRoom">
        作成
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
      createRoom() {
        this.$emit('create-room');
      }

    },

  }


</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

</style>