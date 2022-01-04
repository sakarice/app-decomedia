<template>
  <div id="resize-wrapper" class="resize-wrapper"
  v-show="isObjSelected"
  @mousedown="move" @touchstart="move">

    <!-- // 横幅変更用 -->
    <div class="adjust-bar-wrapper x-size-adjust-bar-wrapper">
      <div class="adjust-bar left-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'left')"
       @mousedown.stop="resizeTrigger($event, 'left')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
      <div class="adjust-bar right-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'right')"
       @mousedown.stop="resizeTrigger($event,'right')">
        <div class="adjust-inner-bar x-inner"></div>
      </div>
    </div>

    <!-- // 縦幅変更用 -->
    <div class="adjust-bar-wrapper y-size-adjust-bar-wrapper">
      <div class="adjust-bar top-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'top')"
       @mousedown.stop="resizeTrigger($event, 'top')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
      <div class="adjust-bar bottom-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'bottom')"
       @mousedown.stop="resizeTrigger($event, 'bottom')">
        <div class="adjust-inner-bar y-inner"></div>
      </div>
    </div>

        <!-- 斜め幅変更用 -->
    <div class="adjust-point-wrapper">
      <div class="adjust-point left top"
       @touchstart.stop="resizeTrigger($event,'leftTop')"
       @mousedown.stop="resizeTrigger($event,'leftTop')"></div>
      <div class="adjust-point left bottom"
       @touchstart.stop="resizeTrigger($event,'leftBottom')"
       @mousedown.stop="resizeTrigger($event,'leftBottom')"></div>
      <div class="adjust-point right top"
       @touchstart.stop="resizeTrigger($event,'rightTop')"
       @mousedown.stop="resizeTrigger($event,'rightTop')"></div>
      <div class="adjust-point right bottom"
       @touchstart.stop="resizeTrigger($event,'rightBottom')"
       @mousedown.stop="resizeTrigger($event,'rightBottom')"></div>
    </div>

  </div>
</template>

<script>
  import {moveStart} from '../../../../../functions/moveHelper'
  import {resizeTargetInit,resizeBasePointInit,resizeStart} from '../../../../../functions/resizeDiffCulcHelper.js'
  import { mapGetters, mapMutations } from 'vuex';

  const mousedownEvent = new CustomEvent('mousedown');
  const touchstartEvent = new CustomEvent('touchstart');

  export default {
    props:[],
    data : ()=>{
      return {
        target : "",
        resize_wrapper : "",
      }
    },
    computed : {
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      isObjSelected:function(){ return (this.getSelectedObjects.length > 0 ) ? true : false },
    },
    methods : {
      move(){
        // 対象DOMも一緒に動かすためにイベントを作成
        this.target.dispatchEvent(mousedownEvent);
        this.target.dispatchEvent(touchstartEvent);
      },
      ObjectSelected(event){
        const e = event.detail;
        this.target = document.getElementById(e.element_id);
        this.SynchronizeStyleWithTarget();
        const {observer,config} = this.getMutationObserver();
        observer.observe(this.target, config);
      },
      resizeTrigger(event,type){
        resizeBasePointInit(event);
        resizeTargetInit(this.target);
        resizeStart(type);
        this.target.addEventListener('resize',this.resize,false);
        document.body.addEventListener("mouseleave", this.clearResizeEvent, false);
        document.body.addEventListener("touchleave", this.clearResizeEvent, false);
      },
      SynchronizeStyleWithTarget(){
        const myStyle = this.resize_wrapper.style;
        const targetStyle = this.target.style;
        const properties = ["width","height","left","top","transform"];
        properties.forEach((property)=>{
          myStyle[property] = targetStyle[property];
        })
      },
      clearResizeEvent(){
        this.target.removeEventListener('resize',this.resize,false);
      },
      getMutationObserver(){
        const observer = new MutationObserver(()=>{
          this.SynchronizeStyleWithTarget();
        })
        const config = {
          attribute : true,
          attributeFilter : ['style'],
        }
        return {"observer":observer, "config":config};
      }
    },
    created(){
      document.body.addEventListener('objectSelected',this.ObjectSelected, false);     
    },
    mounted(){
      this.resize_wrapper = document.getElementById('resize-wrapper');
    }
  }

</script>

<style scoped>

.hidden {
  opacity: 0;
}

#resize-wrapper {
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

.test-point {
  position: absolute;
  background-color: red;
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