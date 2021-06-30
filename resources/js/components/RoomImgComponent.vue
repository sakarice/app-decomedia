<template>
  <!-- Room画像-->
  <div id="room-img-wrapper"
  v-bind:style="{'z-index' : roomImgLayer}">

    <div id="room-img-frame"
     v-on:click="$emit('parent-action', 'imgModal')"
     v-show="isShowRoomImg"
     v-bind:style="{width: roomImgWidth, height: roomImgHeight, opacity: roomImgOpacity}">
      <p v-show="!(roomImgUrl)"></p>
      <img id="room-img"
       :src="roomImgUrl"
       v-show="roomImgUrl" alt="画像が選択されていません"
       v-bind:style="{width: roomImgWidth, height: roomImgHeight, opacity: roomImgOpacity}">
    </div>

  </div>
</template>

<script>
  export default {
    props : [
      'roomImgUrl',
      'roomImgWidth',
      'roomImgHeight',
      'roomImgLayer',
      'roomImgOpacity',
      'isShowRoomImg'
    ],

    data : () => {
      return {
        // frameSize : {
        //   width : "300px",
        //   height : "300px"
        // },
      }
    },
    methods : {},
    mounted: function(){}

  }

</script>



<style scoped>
  /* img */
  #room-img-frame {
    display: flex;
    justify-content: center;
    align-items: center;
    border: 2px;
    border-style: dotted;
    border-color: cadetblue;
  }

</style>