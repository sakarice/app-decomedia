<template>
  <div id="resize-wrapper" class="resize-wrapper" @mousedown="move($event)" @touchstart="move($event)">

    <!-- // 横幅変更用 -->
    <div class="adjust-bar-wrapper x-size-adjust-bar-wrapper">
      <div class="adjust-bar left-size-adjust-bar"
       @touchstart.stop="resizeStart('left')"
       @mousedown.stop="resizeStart('left')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
      <div class="adjust-bar right-size-adjust-bar"
       @touchstart.stop="resizeStart('right')"
       @mousedown.stop="resizeStart('right')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
    </div>

    <!-- // 縦幅変更用 -->
    <div class="adjust-bar-wrapper y-size-adjust-bar-wrapper">
      <div class="adjust-bar top-size-adjust-bar"
       @touchstart.stop="resizeStart('top')"
       @mousedown.stop="resizeStart('top')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
      <div class="adjust-bar bottom-size-adjust-bar"
       @touchstart.stop="resizeStart('bottom')"
       @mousedown.stop="resizeStart('bottom')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
    </div>

    <!-- 斜め幅変更用 -->
    <div class="adjust-point-wrapper">
      <div class="adjust-point left top"
       @touchstart.stop="resizeStart('left'); resizeStart('top')"
       @mousedown.stop="resizeStart('left'); resizeStart('top')"></div>
      <div class="adjust-point left bottom"
       @touchstart.stop="resizeStart('left'); resizeStart('bottom')"
       @mousedown.stop="resizeStart('left'); resizeStart('bottom')"></div>
      <div class="adjust-point right top"
       @touchstart.stop="resizeStart('right'); resizeStart('top')"
       @mousedown.stop="resizeStart('right'); resizeStart('top')"></div>
      <div class="adjust-point right bottom"
       @touchstart.stop="resizeStart('right'); resizeStart('bottom')"
       @mousedown.stop="resizeStart('right'); resizeStart('bottom')"></div>
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
        target : "",
      }
    },
    computed : {
    },
    methods : {
      move(event){ this.$emit('move', event) },
      resizeInit(event){
        this.target = document.getElementById(event.detail.element_id);
        const sizeAndPositionInfos = [];
        sizeAndPositionInfos['width'] = this.target.clientWidth;
        sizeAndPositionInfos['height'] = this.target.clientHeight;
        sizeAndPositionInfos['left'] = this.target.offsetLeft;
        sizeAndPositionInfos['top'] = this.target.offsetTop;
        resizeInfoInit(this.target,sizeAndPositionInfos);
      },
      resizeStart(type){
        resizeStart(type);
      },
    },
    created(){
      document.body.addEventListener('objectSelected',this.resizeInit, false);
    },
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