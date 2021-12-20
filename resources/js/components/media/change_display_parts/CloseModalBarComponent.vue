<template>
  <div @click="closeModal()" @touchstart="closeModal()" class="close-bar-wrapper">
    <div class="close-bar"></div>
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

  .close-bar-wrapper {
    position: absolute;
    top: 0;
    right: 7px;
    z-index: 10;
    height: 100%;
  }

  .close-bar {
    margin-right: 1px;
    border-radius: 4px;
    width: 8px;
    height: 40%;
    background-color: black;
  }

@media screen and (max-width:480px){
  .close-bar-wrapper {
    display: flex;
    align-items: center;
  }
}

</style>