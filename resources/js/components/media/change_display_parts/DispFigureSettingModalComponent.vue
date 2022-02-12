<template>
  <div id="disp-figure-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('figureSettingModal')">
    <i class="fas fa-shapes fa-lg"></i>
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
@import "/resources/css/dispIconCommon.css";

  #disp-figure-setting-modal-wrapper {
    color: skyblue;
  }


</style>