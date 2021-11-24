<template>
  <div id="disp-media-info-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaInfoModal')">
    <i class="fas fa-file-alt fa-2x setting-icon"></i>
  </div>
</template>

<script>
  export default {
    components : {
      // MediaOwnerInfoComponent,
    },
    computed : {},
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