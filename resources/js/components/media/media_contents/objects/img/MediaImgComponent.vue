<template>
  <!-- Media画像-->
  <div :id="imgWrapperWithIndex"
  v-bind:style="imgWrapperStyle()">

    <div id="media-img-frame"
      v-show="getMediaSetting['isShowImg']"
      v-on:click="$emit('parent-action', 'imgModal')"
      v-bind:style="imgStyle()">
      <p v-show="!(mediaImg['url'])"></p>
      <img id="media-img"
       :src="mediaImg['url']"
       v-show="mediaImg['url']" alt="画像が選択されていません"
       v-bind:style="imgStyle()">
    </div>

    <img-resize v-show="isEditMode" :index="index" :class="{hidden:!isActive}" :style="{width:addPxToTail(mediaImg['width']), height:addPxToTail(mediaImg['height'])}"
     v-on:resize="resize"
     v-on:move="moveStart($event)">
    </img-resize>

    <img-rotate v-show="isEditMode && isActive"
    :index="index">
    </img-rotate>

  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import imgRotate from '../object_edit_parts/ImgRotateComponent.vue'
  import imgResize from '../object_edit_parts/ImgResizeComponent.vue'
  export default {
    components : {
      imgRotate,
      imgResize,
    },
    props : [
      "index"
    ],
    data : ()=> {
      return {
        "mediaImg" : "",
        "isActive" : false,
      }
    },
    computed : {
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImgs']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      imgWrapperWithIndex(){ return ('media-img-wrapper' + this.index) },
      isEditMode : function(){
        const route_name = this.$route.name;
        if((route_name=="create") || (route_name=="edit")){
          return true;
        } else {
          return false;
        }
      },
    },
    methods : {
      ...mapMutations('mediaImgs', ['updateMediaImgsObjectItem']),
      ...mapMutations('mediaImgs', ['setTargetObjectIndex']),
      getOneImg(){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(this.index);
        return this.getMediaImg;
      },
      // 位置操作用
      moveStart(e){
        let event;
        if(e.type==="mousedown"){
          event = e;
        } else {
          event = e.changedTouches[0];
        }
        this.action_target = document.getElementById(this.imgWrapperWithIndex);
        this.x_in_element = event.clientX - this.action_target.offsetLeft;
        this.y_in_element = event.clientY - this.action_target.offsetTop;
        // ムーブイベントにコールバック
        document.body.addEventListener("mousemove", this.moving, false);
        this.action_target.addEventListener("mouseup", this.moveEnd, false);
        document.body.addEventListener("touchmove", this.moving, false);
        this.action_target.addEventListener("touchend", this.moveEnd, false);
      },
      moving(e){
        e.preventDefault();
        this.action_target.style.left = (e.clientX - this.x_in_element) + "px";
        this.action_target.style.top = (e.clientY - this.y_in_element) + "px";
        this.mediaImg['left'] = (e.clientX - this.x_in_element);
        this.mediaImg['top'] = (e.clientY - this.y_in_element);

        this.updateMediaImgsObjectItem({index:this.index,key:"left",value:this.mediaImg['left']});
        this.updateMediaImgsObjectItem({index:this.index,key:"top",value:this.mediaImg['top']});

        // マウス、タッチ解除時のイベントを設定
        document.body.addEventListener("mouseleave", this.moveEnd, false);
        document.body.addEventListener("touchleave", this.moveEnd, false);
      },
      moveEnd(e){
        document.body.removeEventListener("mousemove", this.moving, false);
        this.action_target.removeEventListener("mouseup", this.moveEnd, false);
        document.body.removeEventListener("touchmove", this.moving, false);
        this.action_target.removeEventListener("touchend", this.moveEnd, false);
      },
      updateDegree(new_degree){ this.mediaImg['degree'] = new_degree },
      resize(){
        const keys = ["width","height","left","top"];
        const storeData = this.getOneImg(this.index);
        keys.forEach(key=>{ this.mediaImg[key] = storeData[key]});
      },
      imgWrapperStyle(){
        const mi = this.mediaImg;
        const styleObject = {
          "position" : "absolute",
          "display" : "flex",
          "justify-content" : "center",
          "top" :  this.addPxToTail(mi['top']),
          "left" :  this.addPxToTail(mi['left']),
          'z-index' : this.mediaImg['layer'],
        }
        return styleObject;
      },
      imgStyle(){
        const mi = this.mediaImg;
        const styleObject = {
          "width" : this.addPxToTail(mi['width']),
          "height" : this.addPxToTail(mi['height']),
          "opacity" : mi['opacity'],
        }
        return styleObject;
      },
      addPxToTail(value){ return (value + "px") },
    },
    created(){
      this.mediaImg = this.getOneImg();
    },
    mounted(){
      // イベント登録
      document.addEventListener('click', (e)=> {
        if(!e.target.closest("#"+this.imgWrapperWithIndex)){
          this.isActive = false;
        } else {
          this.isActive = true;
        }
      });
    }
  }

</script>



<style scoped>

#media-img-frame {
    display: flex;
    justify-content: center;
    align-items: center;
  }

.hidden {
  /* display: none; */
  opacity: 0;
}

</style>