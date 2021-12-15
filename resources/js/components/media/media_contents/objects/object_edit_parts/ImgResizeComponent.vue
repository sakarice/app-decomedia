<template>
  <div id="resize-wrapper" class="resize-wrapper" @mousedown="move($event)" @touchstart="move($event)">

    <!-- // 横幅変更用 -->
    <div class="adjust-bar-wrapper x-size-adjust-bar-wrapper">
      <div class="adjust-bar left-size-adjust-bar" @mousedown.stop="resizeLeftStart($event)">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
      <div class="adjust-bar right-size-adjust-bar" @mousedown.stop="resizeRightStart($event)">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
    </div>

    <!-- // 縦幅変更用 -->
    <div class="adjust-bar-wrapper y-size-adjust-bar-wrapper">
      <div class="adjust-bar top-size-adjust-bar" @mousedown.stop="resizeTopStart($event)">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
      <div class="adjust-bar bottom-size-adjust-bar" @mousedown.stop="resizeBottomStart($event)">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
    </div>

    <!-- 斜め幅変更用 -->
    <div class="adjust-point-wrapper">
      <div class="adjust-point left top" @mousedown.stop="resizeLeftStart($event); resizeTopStart($event)"></div>
      <div class="adjust-point left bottom" @mousedown.stop="resizeLeftStart($event); resizeBottomStart($event)"></div>
      <div class="adjust-point right top" @mousedown.stop="resizeRightStart($event); resizeTopStart($event)"></div>
      <div class="adjust-point right bottom" @mousedown.stop="resizeRightStart($event); resizeBottomStart($event)"></div>
    </div>

  </div>
</template>

<script>
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
    },
    watch : {},
    methods : {
      // ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      // ...mapMutations('mediaFigures', ['updateMediaFiguresObjectItem']),
      ...mapMutations('mediaImgs', ['setTargetObjectIndex']),
      ...mapMutations('mediaImgs', ['updateMediaImgsObjectItem']),

      getOneImg(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaImg;
      },

      move(event){ this.$emit('move', event) },
      // リサイズ開始メソッドからフックする終了イベントの登録
      registEventStartToEnd(){
        this.isResizing = true;
        document.body.addEventListener("mouseup", this.resizeEnd, false);
        document.body.addEventListener("touchend", this.resizeEnd, false);
      },
      // リサイズ中メソッドからフックする終了イベントの登録
      registEventMiddleToEnd(){ // マウス、タッチ解除時のイベントを設定
        document.body.addEventListener("mouseleave", this.resizeEnd, false);
        document.body.addEventListener("touchleave", this.resizeEnd, false);
      },

      // 1. リサイズ開始メソッド
      resizeRightStart(e){
        document.body.addEventListener("mousemove", this.resizeRight, false);
        document.body.addEventListener("touchmove", this.resizeRight, false);
        this.registEventStartToEnd();
      },
      resizeLeftStart(e){
        document.body.addEventListener("mousemove", this.resizeLeft, false);
        document.body.addEventListener("touchmove", this.resizeLeft, false);
        this.registEventStartToEnd();
      },
      resizeBottomStart(e){
        document.body.addEventListener("mousemove", this.resizeBottom, false);
        document.body.addEventListener("touchmove", this.resizeBottom, false);
        this.registEventStartToEnd();
      },
      resizeTopStart(e){
        document.body.addEventListener("mousemove", this.resizeTop, false);
        document.body.addEventListener("touchmove", this.resizeTop, false);
        this.registEventStartToEnd();
      },

      // 2. リサイズ中メソッド
      resizeMiddleLast(){
        this.$emit('resize');
        this.registEventMiddleToEnd();
      },
      resizeRight(e){
        const left = this.getOneImg(this.index)['left'];
        this.width = e.clientX - left;
        this.updateMediaImgsObjectItem({index:this.index,key:"width",value:this.width});
        this.resizeMiddleLast();
      },
      resizeLeft(e){
        const x = this.getOneImg(this.index)['left'];
        const diff = x - e.clientX;
        const width_before = this.getOneImg(this.index)['width'];
        const width_new = width_before + diff;
        this.updateMediaImgsObjectItem({index:this.index,key:"width",value:width_new});
        this.width = width_new;
        const new_x = x - diff;
        this.updateMediaImgsObjectItem({index:this.index,key:"left",value:new_x});
        this.resizeMiddleLast();
      },
      resizeBottom(e){
        const top = this.getOneImg(this.index)['top'];
        this.height = e.clientY - top;
        this.updateMediaImgsObjectItem({index:this.index,key:"height",value:this.height});
        this.resizeMiddleLast();
      },
      resizeTop(e){
        const y = this.getOneImg(this.index)['top'];
        const diff = y - e.clientY;
        const height_before = this.getOneImg(this.index)['height'];
        const height_new = height_before + diff;
        this.updateMediaImgsObjectItem({index:this.index,key:"height",value:height_new});
        this.height = height_new;
        const new_y = y - diff;
        this.updateMediaImgsObjectItem({index:this.index,key:"top",value:new_y});
        this.resizeMiddleLast();
      },

      // 3. リサイズ終了メソッド。登録したイベントを解除する。
      resizeEnd(e){
        this.isResizing = false;
        document.body.removeEventListener("mousemove", this.resizeRight, false);
        document.body.removeEventListener("mousemove", this.resizeLeft, false);
        document.body.removeEventListener("mousemove", this.resizeBottom, false);
        document.body.removeEventListener("mousemove", this.resizeTop, false);
        document.body.removeEventListener("touchmove", this.resizeRight, false);
        document.body.removeEventListener("touchmove", this.resizeLeft, false);
        document.body.removeEventListener("touchmove", this.resizeBottom, false);
        document.body.removeEventListener("touchmove", this.resizeTop, false);
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