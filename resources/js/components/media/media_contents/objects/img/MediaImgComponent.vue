<template>
  <!-- Media画像-->
  <div :id="imgWrapperWithIndex" class="obj img-wrapper"
  v-bind:style="imgWrapperStyle" :class="{'is_active' : isActive, 'hover-blue' : getMode!=3}"
  @dblclick="showEditor" @click.stop @touchstart.stop>

    <div id="media-img-frame"
      v-show="getMediaSetting['isShowImg']"
      v-bind:style="imgStyle">
      <p v-show="!(mediaImg['url'])"></p>
      <img id="media-img"
       :src="mediaImg['url']"
       v-show="mediaImg['url']" alt="画像が選択されていません"
      @mousedown="moveStart($event)" @touchstart="moveStart($event)"
       v-bind:style="imgStyle">
    </div>

  </div>
</template>

<script>
  import {moveStart} from '../../../../../functions/moveHelper'
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    props : [
      "index"
    ],
    data : ()=> {
      return {        
        img_wrapper : "",
        mediaImg : "",
      }
    },
    computed : {
      start(){ return start },
      ...mapGetters('media', ['getMode']),
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImgs']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      imgWrapperWithIndex(){ return ('media-img-wrapper' + this.index) },
      imgWrapperStyle(){
        const mi = this.mediaImg;
        const styleObject = {
          "top" :  mi['top'] + "px",
          "left" :  mi['left'] + "px",
          "width" : mi['width'] + "px",
          "height" : mi['height'] + "px",
          "transform" : "rotate(" + mi['degree'] + "deg)",
          'z-index' : mi['layer'],
        }
        return styleObject;
      },
      imgStyle(){
        const mi = this.mediaImg;
        const styleObject = {
          "width" : mi['width'] + "px",
          "height" : mi['height'] + "px",
          "opacity" : mi['opacity'],
        }
        return styleObject;
      },      
      isEditMode : function(){
        const route_name = this.$route.name;
        if((route_name=="create") || (route_name=="edit")){
          return true;
        } else {
          return false;
        }
      },
      isActive:function(){
        return this.getSelectedObjects.some((obj)=>obj.type==1 && obj.index==this.index)
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
      init(){ this.mediaImg = this.getOneImg(); },
      showEditor(){
        const showSetting = new CustomEvent('showImgSetting', {detail:{index:this.index}});
        document.body.dispatchEvent(showSetting);
      },
      selected(){
        const objectSelected = new CustomEvent('objectSelected',
        {detail:{
          type:1
          ,index:this.index
          ,degree:this.mediaImg['degree']
          ,element_id:this.imgWrapperWithIndex
        }});
        document.body.dispatchEvent(objectSelected);
      },
      // 位置操作用
      moveStart(e){
        if(this.getMode != 3){
          const move_target_dom = document.getElementById(this.imgWrapperWithIndex);
          moveStart(e, move_target_dom);
          move_target_dom.addEventListener('moveFinish', this.moveEnd, false);
        }
      },
      moveEnd(e){
        e.target.removeEventListener('moveFinish', this.moveEnd, false);
        this.updateMediaImgsObjectItem({index:this.index,key:"left",value:e.detail.left});
        this.updateMediaImgsObjectItem({index:this.index,key:"top",value:e.detail.top});
      },
      updateSizeAndPosition(event){
        const e = event.detail;
        const start_value = event.detail.resize_start_infos;
        let new_values = {};
        new_values['width'] = start_value['width'] + e.diff_x;
        new_values['height'] = start_value['height'] + e.diff_y;
        const left_diff = e.resize_side['x'] == -1 ? e.diff_x : 0;
        const top_diff = e.resize_side['y'] == -1 ? e.diff_y : 0;
        new_values['left'] = start_value['left'] - left_diff;
        new_values['top'] = start_value['top'] - top_diff;
        Object.keys(new_values).forEach((key)=>{
          if(new_values[key]){
            const new_val = Math.floor(new_values[key]);
            this.updateStoreAndMyData(key,new_val);
          }
        })
      },
      updateStoreAndMyData(key,value){
        this.updateMediaImgsObjectItem({index:this.index,key:key,value:value});
        this.mediaImg[key] = value;
      },
      onChangeDegree(e){
        this.updateStoreAndMyData("degree", e.detail.degree);
      },
    },

    created(){
      this.init();
    },
    mounted(){
      this.img_wrapper = document.getElementById(this.imgWrapperWithIndex);
      // イベント登録
      this.img_wrapper.addEventListener('imgDataUpdated',this.init,false);
      this.img_wrapper.addEventListener('resize',this.updateSizeAndPosition,false);
      this.img_wrapper.addEventListener('rotateObject',this.onChangeDegree,false);
      this.img_wrapper.addEventListener('click',this.selected,false);
      this.img_wrapper.addEventListener('touchstart',this.selected,false);
    }
  }

</script>



<style scoped>
@import "/resources/css/mediaObjectCommon.css";
@import "/resources/css/flexSetting.css";

.img-wrapper {
  position : absolute;
  display : flex;
  justify-content : center;
}

#media-img-frame {
  display: flex;
  justify-content: center;
  align-items: center;
}

/* .hover-blue:hover {
  outline: 1px solid blue;
} */

</style>