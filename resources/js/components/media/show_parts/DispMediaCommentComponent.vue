<template>
  <div id="disp-media-comment-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaComment')">
    <i class="fas fa-comment fa-lg"></i>
  </div>
</template>

<script>

  export default {
    components : {},
    computed : {
    },
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

  #disp-media-comment-modal-wrapper {
    color: white;
  }
  
</style>