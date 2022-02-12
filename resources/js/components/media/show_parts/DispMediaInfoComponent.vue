<template>
  <div id="disp-media-info-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaInfoModal')">
    <i class="fas fa-file-alt fa-lg setting-icon"></i>
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
@import "/resources/css/dispIconCommon.css";

  #disp-media-info-wrapper {
    color: white;
  }


</style>