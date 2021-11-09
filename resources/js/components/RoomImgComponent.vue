<template>
  <!-- Media画像-->
  <div id="media-img-wrapper"
  v-bind:style="{'z-index' : mediaImgLayer}">

    <div id="media-img-frame"
     v-on:click="$emit('parent-action', 'imgModal')"
     v-show="isShowMediaImg"
     v-bind:style="{width: mediaImgWidth, height: mediaImgHeight, opacity: mediaImgOpacity}">
      <p v-show="!(mediaImgUrl)"></p>
      <img id="media-img"
       :src="mediaImgUrl"
       v-show="mediaImgUrl" alt="画像が選択されていません"
       v-bind:style="{width: mediaImgWidth, height: mediaImgHeight, opacity: mediaImgOpacity}">
    </div>

  </div>
</template>

<script>
  export default {
    props : [
      'mediaImgUrl',
      'mediaImgWidth',
      'mediaImgHeight',
      'mediaImgLayer',
      'mediaImgOpacity',
      'isShowMediaImg'
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
  #media-img-wrapper {
    pointer-events: none;
  }
  #media-img-frame {
    display: flex;
    justify-content: center;
    align-items: center;
    /* border: 2px;
    border-style: dotted;
    border-color: cadetblue; */
  }

</style>