<template>
  <div id="disp-text-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('textSettingModal')">
    <i class="fab fa-autoprefixer fa-lg"></i>
  </div>
</template>

<script>
  export default {
    methods : {
      showModal(target){
        this.$emit('show-modal',target);
      }
    }
  }

</script>

<style scoped>

  #disp-text-setting-modal-wrapper{
    color: white;
  }

  .icon-wrapper {
    padding: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>