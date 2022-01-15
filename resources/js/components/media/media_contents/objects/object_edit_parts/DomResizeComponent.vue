<template>
  <div id="resize-wrapper" class="resize-wrapper"
  v-show="isObjSelected"
  @mousedown="targetMoveStart($event)" @click.stop @touchstart="targetMoveStart($event)">

    <!-- // 横幅変更用 -->
    <div class="adjust-bar-wrapper x-size-adjust-bar-wrapper">
      <div class="adjust-bar left-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'left')"
       @mousedown.stop="resizeTrigger($event, 'left')">
        <div class="adjust-bar-outer x-outer left-outer tran02">
          <div class="adjust-bar-inner x-inner left-inner tran02"></div>
        </div>
      </div>
      <div class="adjust-bar right-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'right')"
       @mousedown.stop="resizeTrigger($event,'right')">
        <div class="adjust-bar-outer x-outer right-outer tran02">
          <div class="adjust-bar-inner x-inner right-inner tran02"></div>
        </div>
      </div>
    </div>

    <!-- // 縦幅変更用 -->
    <div class="adjust-bar-wrapper y-size-adjust-bar-wrapper">
      <div class="adjust-bar top-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'top')"
       @mousedown.stop="resizeTrigger($event, 'top')">
        <div class="adjust-bar-outer y-outer top-outer tran02">
          <div class="adjust-bar-inner y-inner top-inner tran02"></div>
        </div>
      </div>
      <div class="adjust-bar bottom-size-adjust-bar"
       @touchstart.stop="resizeTrigger($event, 'bottom')"
       @mousedown.stop="resizeTrigger($event, 'bottom')">
        <div class="adjust-bar-outer y-outer bottom-outer tran02">
          <div class="adjust-bar-inner y-inner bottom-inner tran02"></div>
        </div>
      </div>
    </div>

        <!-- 斜め幅変更用 -->
    <div class="adjust-point-wrapper">
      <div class="adjust-point-outer left top"
       @touchstart.stop="resizeTrigger($event,'leftTop')"
       @mousedown.stop="resizeTrigger($event,'leftTop')">
       <div class="adjust-point-inner"></div>
       </div>
      <div class="adjust-point-outer left bottom"
       @touchstart.stop="resizeTrigger($event,'leftBottom')"
       @mousedown.stop="resizeTrigger($event,'leftBottom')">
       <div class="adjust-point-inner"></div>
       </div>
      <div class="adjust-point-outer right top"
       @touchstart.stop="resizeTrigger($event,'rightTop')"
       @mousedown.stop="resizeTrigger($event,'rightTop')">
       <div class="adjust-point-inner"></div>
       </div>
      <div class="adjust-point-outer right bottom"
       @touchstart.stop="resizeTrigger($event,'rightBottom')"
       @mousedown.stop="resizeTrigger($event,'rightBottom')">
       <div class="adjust-point-inner"></div>
       </div>
    </div>

  </div>
</template>

<script>
  import {moveStart} from '../../../../../functions/moveHelper'
  import {resizeTargetInit,resizeBasePointInit,resizeStart} from '../../../../../functions/resizeDiffCulcHelper.js'
  import { mapGetters, mapMutations } from 'vuex';

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
      // 対象DOMも一緒に動かすためにイベントを作成
      targetMoveStart(e){ moveStart(e,this.target);},
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
        const observer = new MutationObserver((mutations)=>{
          // mutations.forEach((mutation)=>{})
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
.tran02 {
  transition: 0.1s;
}

#resize-wrapper {
  position: absolute;
  z-index: 9;
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

.adjust-bar:hover .adjust-bar-inner{
  /* background-color: lightgreen;
  box-shadow: 0 0 3px lightgreen; */
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

.adjust-bar-outer {
  display: flex;
  justify-content: center;
  align-items: center; 
  border-radius: 10px;
}
.adjust-bar-inner {
  background-color: white;
  box-shadow: 1px 1px 4px grey;
  border-radius: 10px;
}

.adjust-bar-outer:hover {
  box-shadow: 0 0 3px lightgreen;
}
.adjust-bar-outer:hover .adjust-bar-inner {
  background-color: lightgreen;
}

.x-outer {
  width: 15px;
  height: 100%;
}
.x-inner {
  width: 5px;
  height: 20px;
}

.y-outer {
  width: 100%;
  height: 15px;
}
.y-inner {
  width: 20px;
  height: 5px;
}




.adjust-point-wrapper {
  display: flex;
  justify-content: space-between;
}
.adjust-point-outer {
  position: absolute;
  padding: 10px;
  /* width: 20px;
  height: 20px; */
  border-radius: 50%;
}
.adjust-point-outer:hover {
  box-shadow: 0 0 8px lightgreen;
}
.adjust-point-outer:hover .adjust-point-inner {
  background-color: lightgreen;
}
.adjust-point-inner {
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
  left: -15px;
}
.right {
  right: -15px;
}
.top {
  top: -15px;
}
.bottom {
  bottom: -15px;
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