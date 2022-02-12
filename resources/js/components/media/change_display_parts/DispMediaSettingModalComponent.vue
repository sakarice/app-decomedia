<template>
  <div id="disp-media-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaSettingModal')">
    <i class="fas fa-cog fa-lg"></i>
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

</style>