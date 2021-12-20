<template>
  <div class="close-icon-wrapper">
    <i @click="closeModal()" @touchstart="closeModal()" class="fas fa-chevron-circle-left fa-3x"></i>
  </div>
</template>

<script>
  export default {
    methods : {
      closeModal(){
        const closeEvent = new CustomEvent('closeModal');
        document.body.dispatchEvent(closeEvent);
      }
    }
  }

</script>

<style scoped>

.close-icon-wrapper{
  position: absolute;
  right: -25px;
  top: 50%;
  padding: 5px;
  border-top-right-radius: 50%;
  border-bottom-right-radius: 50%;
}


</style>