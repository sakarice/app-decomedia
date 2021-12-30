<template>
  <div id="scale-wrapper" class="scale-wrapper"
  v-show="isObjSelected" :style="style"
  @mousedown="move()" @touchstart="move()">

    <!-- // 横幅変更用 -->
    <div class="adjust-bar-wrapper x-size-adjust-bar-wrapper">
      <div class="adjust-bar left-size-adjust-bar"
       @touchstart.stop="scaleStart('left')"
       @mousedown.stop="scaleStart('left')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
      <div class="adjust-bar right-size-adjust-bar"
       @touchstart.stop="scaleStart('right')"
       @mousedown.stop="scaleStart('right')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
    </div>

    <!-- // 縦幅変更用 -->
    <div class="adjust-bar-wrapper y-size-adjust-bar-wrapper">
      <div class="adjust-bar top-size-adjust-bar"
       @touchstart.stop="scaleStart('top')"
       @mousedown.stop="scaleStart('top')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
      <div class="adjust-bar bottom-size-adjust-bar"
       @touchstart.stop="scaleStart('bottom')"
       @mousedown.stop="scaleStart('bottom')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
    </div>

    <!-- 斜め幅変更用 -->
    <div class="adjust-point-wrapper">
      <div class="adjust-point left top"
       @touchstart.stop="scaleStart('left'); scaleStart('top')"
       @mousedown.stop="scaleStart('left'); scaleStart('top')"></div>
      <div class="adjust-point left bottom"
       @touchstart.stop="scaleStart('left'); scaleStart('bottom')"
       @mousedown.stop="scaleStart('left'); scaleStart('bottom')"></div>
      <div class="adjust-point right top"
       @touchstart.stop="scaleStart('right'); scaleStart('top')"
       @mousedown.stop="scaleStart('right'); scaleStart('top')"></div>
      <div class="adjust-point right bottom"
       @touchstart.stop="scaleStart('right'); scaleStart('bottom')"
       @mousedown.stop="scaleStart('right'); scaleStart('bottom')"></div>
    </div>

  </div>
</template>

<script>
  import {targetInit,scaleInfoInit,scaleStart} from '../../../../../functions/resizeDiffCulcHelper.js'
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    props:[
      // 'index',
    ],
    data : ()=>{
      return {
        target : "",
        left : 100,
        top : 100,
        width : 100,
        height : 100,
      }
    },
    computed : {
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      isObjSelected:function(){ return (this.getSelectedObjects.length > 0 ) ? true : false },
      style:function(){
        const style = {
          "left" : this.left + "px",
          "top" : this.top + "px",
          "width":this.width + "px",
          "height":this.height + "px",
        }
        return style;
      },
    },
    methods : {
      move(){
        // 対象DOMも一緒に動かすためにイベントを作成
        const moveStartEvent = new CustomEvent('mousedown');
        this.target.dispatchEvent(moveStartEvent);
      },
      ObjectSelected(event){
        this.target = document.getElementById(event.detail.element_id);
        this.left = this.target.style.left.replace("px","");
        this.top = this.target.style.top.replace("px","");
        const scale_x = event.detail.scale_x;
        const scale_y = event.detail.scale_y;
        this.width = event.detail.width * scale_x;
        // this.width = this.target.clientWidth * scale_x;
        this.height = event.detail.height * scale_y;
        // this.height = this.target.clientHeight * scale_y;
        const sizeInfo = {"width":this.width, "height":this.height};
        targetInit(this.target);
        scaleInfoInit(sizeInfo);
      },
      scaleStart(type){
        scaleStart(type);
        this.target.addEventListener('scaleX',this.updateWidthAndLeft,false);
        this.target.addEventListener('scaleY',this.updateHeightAndTop,false);
        document.body.addEventListener("mouseleave", this.clearScaleEvent, false);
        document.body.addEventListener("touchleave", this.clearScaleEvent, false);
      },
      updateWidthAndLeft(event){
        this.width += event.detail.diff;
        this.left = this.target.style.left.replace("px","");
      },
      updateHeightAndTop(event){
        this.height += event.detail.diff;
        this.top = this.target.style.top.replace("px","");
      },
      clearScaleEvent(){
        this.target.removeEventListener('scaleX',this.updateWidthAndLeft,false);
        this.target.removeEventListener('scaleY',this.updateHeightAndTop,false);
      },
    },
    created(){
      document.body.addEventListener('objectSelected',this.ObjectSelected, false);
      
    },
  }

</script>

<style scoped>

.hidden {
  opacity: 0;
}

#scale-wrapper {
  position: absolute;
  z-index: 1;
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