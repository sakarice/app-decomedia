<template>
  <div id="disp-contents-field-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('contentsFieldSettingModal')">
    <i class="fas fa-square fa-lg"></i>
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

  .icon-wrapper {
    padding: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>