<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger create-icon-wrapper" @click="createMedia">
        <i class="fas fa-check fa-lg create-icon"></i>
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
      createMedia() {
        this.$emit('create-media');
      }
    },
  }


</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

.create-icon-wrapper:hover {
  color: darkorange;
}

.action-trigger-subtitle {
  margin-left: 7px;
}

</style>