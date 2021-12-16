<template>
  <div id="resize-wrapper" class="resize-wrapper" @mousedown="move($event)" @touchstart="move($event)">

    <!-- // 横幅変更用 -->
    <div class="adjust-bar-wrapper x-size-adjust-bar-wrapper">
      <div class="adjust-bar left-size-adjust-bar" @mousedown.stop="resizeStart('left')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
      <div class="adjust-bar right-size-adjust-bar" @mousedown.stop="resizeStart('right')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
    </div>

    <!-- // 縦幅変更用 -->
    <div class="adjust-bar-wrapper y-size-adjust-bar-wrapper">
      <div class="adjust-bar top-size-adjust-bar" @mousedown.stop="resizeStart('top')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
      <div class="adjust-bar bottom-size-adjust-bar" @mousedown.stop="resizeStart('bottom')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
    </div>

    <!-- 斜め幅変更用 -->
    <div class="adjust-point-wrapper">
      <div class="adjust-point left top" @mousedown.stop="resizeStart('left'); resizeStart('top')"></div>
      <div class="adjust-point left bottom" @mousedown.stop="resizeStart('left'); resizeStart('bottom')"></div>
      <div class="adjust-point right top" @mousedown.stop="resizeStart('right'); resizeStart('top')"></div>
      <div class="adjust-point right bottom" @mousedown.stop="resizeStart('right'); resizeStart('bottom')"></div>
    </div>

  </div>
</template>

<script>
  import {resizeInfoInit,resizeStart} from '../../../../../functions/resizeHelper'
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    props:[
      'index',
    ],
    data : ()=>{
      return {
        "isResizing" :false,
        "mouse_x" : 0,
        "mouse_y" : 0,
        "width" : "10px",
        "height" : "10px",
      }
    },
    computed : {
      // ...mapGetters('mediaFigures', ['getMediaFigure']),
      ...mapGetters('mediaImgs', ['getMediaImg']),
      imgWrapperWithIndex(){ return ('media-img-wrapper' + this.index) },
    },
    watch : {},
    methods : {
      ...mapMutations('mediaImgs', ['setTargetObjectIndex']),
      ...mapMutations('mediaImgs', ['updateMediaImgsObjectItem']),

      getOneImg(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaImg;
      },
      move(event){ this.$emit('move', event) },
      resizeStart(type){
        console.log('resize Start');
        const target = document.getElementById(this.imgWrapperWithIndex);
        const mediaImg = this.getOneImg(this.index);
        const sizeAndPositionInfos = [];
        const keys = ['width','height','left','top'];
        keys.forEach(key=>{
          sizeAndPositionInfos[key] = mediaImg[key];
        })
        resizeInfoInit(target,sizeAndPositionInfos);
        resizeStart(type);
        target.addEventListener('resizingWidth',this.updateWidthAndLeft,false);
        target.addEventListener('resizingHeight',this.updateHeighthAndTop,false);
      },
      updateWidthAndLeft(e){
        const new_width = e.detail.width;
        const new_left  = e.detail.left;
        this.updateMediaImgsObjectItem({index:this.index,key:"width",value:new_width});
        this.updateMediaImgsObjectItem({index:this.index,key:"left",value:new_left});
      },
      updateHeighthAndTop(e){
        const new_height = e.detail.height;
        const new_top = e.detail.top;
        this.updateMediaImgsObjectItem({index:this.index,key:"height",value:new_height});
        this.updateMediaImgsObjectItem({index:this.index,key:"top",value:new_top});
      },

    },
    created(){},
    mounted(){},
  }

</script>

<style scoped>

.hidden {
  opacity: 0;
}

#resize-wrapper {
  position: absolute;
}

.adjust-bar-wrapper {
  width: 100%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 0;
  display: flex;
  justify-content: space-between;
  align-items: center; 
}
.adjust-bar-wrapper:hover {
  cursor: all-scroll;
}

.adjust-bar { 
  border-radius: 5px;
  padding: 5px;
  display: flex;
  justify-content: center;
  align-items: center;
  /* background-color: lightgrey; */
}
.adjust-bar:hover {
  /* background-color: aqua; */
  cursor: auto;
}



.y-size-adjust-bar-wrapper{
  flex-direction: column;
}

.right-size-adjust-bar,
.left-size-adjust-bar {
  width: 30px;
  height: 100%;
}
.top-size-adjust-bar,
.bottom-size-adjust-bar {
  width: 100%;
  height: 30px;
}


/* 調整バーを枠内から枠上にずらすためのmargin */
.right-size-adjust-bar{
  margin-right: -15px;
}
.left-size-adjust-bar{
  margin-left: -15px;
}
.top-size-adjust-bar {
  margin-top: -15px;
}
.bottom-size-adjust-bar {
  margin-bottom: -15px;
}

.adjust-inner-bar {
  background-color: white;
  box-shadow: 1px 1px 4px grey;
  border-radius: 10px;
}
.x-inner {
  width: 5px;
  height: 20px;
}
.y-inner {
  width: 20px;
  height: 5px;
}


.adjust-point-wrapper {
  display: flex;
  justify-content: space-between;
}
.adjust-point {
  position: absolute;
  background-color: white;
  width: 10px;
  height: 10px;
  box-shadow: 1px 1px 4px grey;
  border-radius: 50%;
}
.left {
  left: -5px;
}
.right {
  right: -5px;
}
.top {
  top: -5px;
}
.bottom {
  bottom: -5px;
}

.mouse-position {
  position : absolute;
  /* bottom: -20px; */
  right: -90px;
}
.mouse-x {
  bottom: -20px;
}
.mosue-y {
  bottom: -40px;
}


</style>