<template>
  <!-- <transition name="right-slide"> -->
    <!-- Room画像-->
  <div id="room-img-wrapper"
  v-bind:style="{'z-index' : roomImgLayer}">

    <div id="room-img-frame"
     v-on:click="$emit('parent-action')"
     v-show="isShowRoomImg"
     v-bind:style="{width: roomImgWidth, height: roomImgHeight}">
      <p v-show="!(roomImgUrl)"></p>
      <img id="room-img"
       :src="roomImgUrl"
       v-show="roomImgUrl" alt="画像が選択されていません"
       v-bind:style="{width: roomImgWidth, height: roomImgHeight}">
    </div>

  </div>
  <!-- </transition> -->
</template>

<script>
  export default {
    props : [
      'roomImgUrl',
      'roomImgWidth',
      'roomImgHeight',
      'roomImgLayer',
      'isShowRoomImg'
    ],

    data : () => {
      return {
        frameSize : {
          width : "300px",
          height : "300px"
        },
      }
    },
    methods : {
      setFrameSize(){
        // console.log(this.roomImgWidth);
        // this.frameSize.width = this.roomImgWidth;
        // this.frameSize['height']=this.roomImgHeight;
      }
    },
    mounted: function(){
      // this.setFrameSize();
    }

  }

</script>



<style>
  /* img */
  #room-img-frame {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px;
    border-style: dotted;
    border-color: cadetblue;
  }

  #room-img {
    /* width: 400px; */
    /* height: 400px; */
  }

</style>