<template>
  <transition name="flowup">
    <div id="overlay"></div>
  </transition>
</template>

<script>

  export default {
    props : [],
    data : () => {
      return {
      }
    },
    methods : {},

  }

</script>



<style scoped>

#overlay {
  z-index: 10;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);

  /* flex設定 */
  display: flex;
  align-items: center;
  justify-content: center;
}



/* モーダル表示アニメーション */
.flowup-enter-active, .flowup-leave-active {
  opacity: 1;
  transform: translate(0px, 0px);
  transition: all 150ms;
}

.flowup-enter, .flowup-leave-to {
  opacity: 0;
  transform: translateY(20px);
}


</style>