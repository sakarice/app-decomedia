<template>

    <div class="room-create-trigger-wrapper">
      <div class="room-create-trigger" @click="createRoom">
        <i class="fas fa-check fa-2x create-icon"></i>
      </div>
      <span class="create-trigger-subtitle">作成</span>
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

.room-create-trigger-wrapper {
  display: flex;
  align-items: center;
  margin: 0 20px;
  color: white;
}
.room-create-trigger {
  background-color: greenyellow;
  padding: 9px 10px;
  border-radius: 50%;
  box-shadow: 1px 1px 3px green;
  transition: 0.13s;
}
.room-create-trigger:hover {
  background-color: aquamarine;
  color: black;
  cursor: pointer;
}

.create-trigger-subtitle {
  font-size:12px;
  color: grey;
  margin-left: 7px;
}

</style>