<template>
  <div id="disp-figure-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('figureSettingModal')">
    <i class="fas fa-shapes fa-2x"></i>
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

  #disp-figure-setting-modal-wrapper {
    color: skyblue;
  }

  .icon-wrapper {
    padding: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>