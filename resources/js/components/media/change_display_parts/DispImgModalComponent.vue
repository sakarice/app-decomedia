<template>
  <div id="disp-img-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('imgModal')">
    <i class="fas fa-image fa-2x"></i>
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