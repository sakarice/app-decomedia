<template>
  <!-- Media画像-->
  <div :id="imgWrapperWithIndex"
  v-bind:style="imgWrapperStyle()"
  @click.stop @touchstart.stop>

    <div id="media-img-frame"
      v-show="getMediaSetting['isShowImg']"
      v-on:click="$emit('parent-action', 'imgModal')"
      v-bind:style="imgStyle()">
      <p v-show="!(mediaImg['url'])"></p>
      <img id="media-img"
       :src="mediaImg['url']"
       v-show="mediaImg['url']" alt="画像が選択されていません"
      @mousedown="moveStart($event)" @touchstart="moveStart($event)"
       v-bind:style="imgStyle()">
    </div>

    <img-resize v-show="isEditMode" :index="index" :class="{hidden:!isActive}" :style="{width:addPxToTail(mediaImg['width']), height:addPxToTail(mediaImg['height'])}"
     v-on:resize="reRender"
     @move="moveStart($event)">
    </img-resize>

    <img-rotate v-show="isEditMode && isActive"
    :index="index">
    </img-rotate>

  </div>
</template>

<script>
  import {moveStart} from '../../../../../functions/moveHelper'
  import { mapGetters, mapMutations } from 'vuex';
  import imgRotate from '../object_edit_parts/ImgRotateComponent.vue'
  import imgResize from '../object_edit_parts/ImgResizeComponent.vue'
  
  const objectSelected = new CustomEvent('objectSelected');


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
        img_wrapper : "",
        mediaImg : "",
        isActive : false,
      }
    },
    computed : {
      start(){ return start },
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImgs']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
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
    watch : {
      isActive(val){
        if(val == true){
          this.$emit('add-active-index', this.index);
          const objectData = {type:1, index:this.index}
          this.addSelectedObjectItem(objectData);
        } else {
          this.$emit('del-active-index', this.index);
          const objects = this.getSelectedObjects;
          const judgeIsMyself = (obj) => (obj.type==1 && obj.index==this.index);
          const myIndex = objects.findIndex(obj=>judgeIsMyself);
          this.deleteSelectedObjectItem(myIndex);
        }
      },
    },
    methods : {
      ...mapMutations('mediaImgs', ['updateMediaImgsObjectItem']),
      ...mapMutations('mediaImgs', ['setTargetObjectIndex']),
      ...mapMutations('mediaImgs', ['deleteMediaImgsObjectItem']),
      ...mapMutations('selectedObjects', ['addSelectedObjectItem']),
      ...mapMutations('selectedObjects', ['deleteSelectedObjectItem']),

      getOneImg(){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(this.index);
        return this.getMediaImg;
      },
      selected(){
        document.body.dispatchEvent(objectSelected);
        this.isActive = true;
      },
      unSelected(){ this.isActive = false; },
      // 位置操作用
      moveStart(e){
        const move_target_dom = document.getElementById(this.imgWrapperWithIndex);
        moveStart(e, move_target_dom);
        move_target_dom.addEventListener('moveFinish', this.moveEnd, false);
      },
      moveEnd(e){
        e.target.removeEventListener('moveFinish', this.moveEnd, false);
        this.updateMediaImgsObjectItem({index:this.index,key:"left",value:e.detail.left});
        this.updateMediaImgsObjectItem({index:this.index,key:"top",value:e.detail.top});
      },
      updateDegree(new_degree){ this.mediaImg['degree'] = new_degree },
      reRender(){
        const keys = ["width","height","left","top"];
        const storeData = this.getOneImg(this.index);
        if(storeData){
          keys.forEach(key=>{ this.mediaImg[key] = storeData[key]});
        }
      },
      init(){ this.mediaImg = this.getOneImg(); },
      imgWrapperStyle(){
        const mi = this.mediaImg;
        const styleObject = {
          "position" : "absolute",
          "display" : "flex",
          "justify-content" : "center",
          "top" :  this.addPxToTail(mi['top']),
          "left" :  this.addPxToTail(mi['left']),
          "transform" : "rotate(" + this.mediaImg['degree'] + "deg)",
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
      this.init();
    },
    mounted(){
      this.img_wrapper = document.getElementById(this.imgWrapperWithIndex);
      // イベント登録
      this.img_wrapper.addEventListener('click',this.selected,false);
      this.img_wrapper.addEventListener('touchstart',this.selected,false);
      document.body.addEventListener('fieldClicked', this.unSelected, false);
      document.body.addEventListener('objectSelected', this.unSelected, false);

      // document.addEventListener('click', (e)=> {
      //   if(!e.target.closest("#"+this.imgWrapperWithIndex)){
      //     this.isActive = false;
      //   } else {
      //     this.isActive = true;
      //   }
      // });
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