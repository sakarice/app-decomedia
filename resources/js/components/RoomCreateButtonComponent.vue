<template>

    <div class="action-button-wrapper">
      <button class="room-create-button" @click="createRoom">
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

  .action-button-wrapper {
    margin: 0 5px;
  }

  .room-create-button {
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