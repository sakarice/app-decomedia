<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger create-icon-wrapper" @click="createRoom">
        <i class="fas fa-check fa-2x create-icon"></i>
      </div>
      <span class="action-trigger-subtitle">作成</span>

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

.create-icon-wrapper:hover {
  color: darkorange;
}

.action-trigger-subtitle {
  margin-left: 7px;
}

</style>