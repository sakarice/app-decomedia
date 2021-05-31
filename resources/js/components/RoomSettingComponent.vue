<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="area-wrapper">

        <div id="setting-wrapper">

          <div id="room-bg-color-wraper" class="setting">
            <p>Room設定</p>
            <label for="">
              <input v-model="roomBackgroundColor" type="color" id="room-bg-color">
              Room背景色
            </label>
          </div>

          <div id="room-img-setting-wrapper" class="setting">
            <p>Room画像</p>
            <input v-model="roomImgWidth" type="text" size="5" placeholder="横幅">
            <span>[px] 横幅</span><span class="message-label"> (ブラウザの横幅：{{window_width}})</span><br>
            <input v-model="roomImgHeight" type="text" size="5" placeholder="縦幅">
            <span>[px] 縦幅</span><span class="message-label"> (ブラウザの縦幅：{{window_height}})</span><br>
            <button v-on:click="toggleRoomImg">
              <span v-show="isShowRoomImg">非表示</span>
              <span v-show="!(isShowRoomImg)">表示</span>
            </button>
          </div>

        </div>

      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props : [
    'transitionName',
    'roomBackgroundColor',
    'isShowRoomImg',
    'roomImgWidth',
    'roomImgHeight',
  ],
  data : () => {
    return {
      window_width : "",
      window_height : "",
      // roomBackgroundColor : "",
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
    resizeImg(){
      this.$emit('resize-img');
    },
    toggleRoomImg() {
      this.$emit('toggle-room-img');  // room画像を削除(=URLを空に)
    },

  },
  mounted : function() {
    this.window_width = window.innerWidth;
    this.window_height = window.innerHeight;
  },
  watch : {
    // カラーピッカーの変更に、Room背景色を同期させて即反映
    roomBackgroundColor : function(newColor){
      this.$parent.roomBackgroundColor = newColor;
    },
    roomImgWidth : function(newWidth){
      this.$parent.roomImgWidth = newWidth;
    },
    roomImgHeight : function(newHeight){
      this.$parent.roomImgHeight = newHeight;
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

  .setting {
    margin-bottom : 30px;
  }

  .message-label {
    font-size: 10px;
  }


  /* アニメーション */

  /* .right-slide-enter-to, .right-slide-leave {
    transform: translate(0px, 0px);
  } */

  .right-slide-enter-active, .right-slide-leave-active {
    transform: translate(0px, 0px);
    transition: all 400ms
    /* cubic-bezier(0, 0, 0.2, 1) 0ms; */
  }

  .right-slide-enter, .right-slide-leave-to {
    transform: translateX(100vw) 
  }

</style>