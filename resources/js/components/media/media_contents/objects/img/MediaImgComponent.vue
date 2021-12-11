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

    <img-rotate v-show="isActive"
    :index="index">
    </img-rotate>

  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import imgRotate from '../object_edit_parts/ImgRotateComponent.vue'
  export default {
    components : {
      imgRotate,
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
      imgWrapperWithIndex(){ return ('media-img-wrapper' + this.index) }
    },
    methods : {
      ...mapMutations('mediaImg', ['updateMediaImgObjectItem']),
      ...mapMutations('mediaImgs', ['setTargetObjectIndex']),
      getOneFigure(){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(this.index);
        return this.getMediaImg;
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
      this.mediaImg = this.getOneFigure();
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

</style>