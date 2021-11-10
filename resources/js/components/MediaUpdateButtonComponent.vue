<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger update-icon-wrapper" @click="updateMedia">
        <i class="fas fa-check fa-2x update-icon"></i>
      </div>
      <span class="action-trigger-subtitle">更新</span>
      
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
      updateMedia() {
        this.$emit('update-media');
      }

    },

  }


</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

.update-icon-wrapper:hover {
  color: darkorange;
}

.update-trigger-subtitle {
  margin-left: 7px;
}

</style>