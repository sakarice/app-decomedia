<template>
  <transition name="right-slide">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="area-wrapper">

        <div id="setting-wrapper">
          <p>Room設定</p>
          <label for="">
            <input v-model="roomBackgroundColor" type="color" id="room-bg-color">
            Room背景色
          </label>
        </div>

      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props : ['roomBackgroundColor'],
  data : () => {
    return {
      // roomBackgroundColor : "",

    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },

  },
  mounted : function() {
  },
  watch : {
    // カラーピッカーの変更に、Room背景色を同期させて即反映
    roomBackgroundColor : function(newColor){
      this.$parent.roomBackgroundColor = newColor;
    }
  }

}
</script>


<style>

  /* コンテンツのCSS */
  #setting-wrapper {
    margin: 20px 0;
    width: 80%;

    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }



  /* アニメーション */

  /* .right-slide-enter-to, .right-slide-leave {
    transform: translate(0px, 0px);
  } */

  .right-slide-enter-active, .right-slide-leave-active {
    transform: translate(0px, 0px);
    transition: all 500ms
    /* cubic-bezier(0, 0, 0.2, 1) 0ms; */
  }

  .right-slide-enter, .right-slide-leave-to {
    transform: translateX(100vw) 
  }

</style>