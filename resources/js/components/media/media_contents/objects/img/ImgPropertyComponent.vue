<template>
  <!-- Media画像-->
  <div id="media-img-update-wrapper" :class="{hidden:!isEditMode}"
  v-show="isShowEditor" @click.stop @touchstart.stop>
    <div class="item-frame">
      <!-- クローズアイコン -->
      <div class="close-icon-wrapper" @mousedown.stop :class="{hidden:isMobile}">
        <i class="fas fa-times fa-3x close-icon" @click="closeEditor"></i>
      </div>

      <!-- 画像設定 -->
      <div class="media-img-settings">
        <!-- 数値系の設定 -->
        <div class="setting-type-num">
          <div class="disp-space-between x-position-wrapper">
            <span>配置座標(x):</span>
            <input type="number" class="input-num" :value="imgDatas['left']" @input="updateImgData('left', $event.target.value)" min="-1000" max="10000">
          </div>

          <div class="disp-space-between y-position-wrapper">
            <span>配置座標(y):</span>
            <input type="number" class="input-num" :value="imgDatas['top']" @input="updateImgData('top', $event.target.value)" min="-1000" max="10000">
          </div>

          <div class="disp-space-between degree-wrapper">
            <span>回転:</span>
            <input type="number" class="input-num" :value="imgDatas['degree']" @input="updateImgData('degree', $event.target.value)">
          </div>

          <div class="disp-space-between width-input-wrapper">
            <span>横幅[px]:</span>
            <input type="number" class="input-num" :value="imgDatas['width']" @input="updateImgData('width', $event.target.value)" min="0" max="10000">
          </div>
          <div class="disp-space-between height-input-wrapper">
            <span>縦幅[px]:</span>
            <input type="number" class="input-num" :value="imgDatas['height']" @input="updateImgData('height', $event.target.value)" min="0" max="10000">
          </div>

          <div class="disp-space-between layer-input-wrapper">
            <span>重ね順:</span>
            <input type="number" class="input-num" :value="imgDatas['layer']" @input="updateImgData('layer', $event.target.value)"  min="0" max="100">
          </div>

        </div>

        <div class="opacity-input-wrapper">
          <span>透過度:</span>
          <input type="range" :value="imgDatas['globalAlpha']" @mousedown.stop @input="updateImgData('opacity',$event.target.value)" name="opacity" id="" min="0" max="1" step="0.05">
        </div>
      </div>
    </div>

    <close-modal-bar class="for-mobile"></close-modal-bar>

  </div>
  
</template>

<script>
  import {moveStart} from '../../../../../functions/moveHelper'
  import { mapGetters, mapMutations } from 'vuex';
  import closeModalBar from '../../../change_display_parts/CloseModalBarComponent.vue'


  export default {
    components : {closeModalBar},
    data : ()=>{
      return {
        isShowEditor : false,
        index: 0,        
        imgDatas : {
          // "type" : 99,
          "top" : 0,
          "left" : 0,
          "width" : 0,
          "height" : 0,
          "degree" : 0,
          "opacity" : 0,
          "layer" : 0,
        },
      }
    },
    computed : {
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('deviceType', ['getDeviceType']),
      isMobile(){ return (this.getDeviceType==2) ? true : false; },
      isEditMode : function(){
        const route_name = this.$route.name;
        let isEdit = false;
        if((route_name=="create") || (route_name=="edit")){
          isEdit = true;
        }
        return isEdit;
      },      
    },
    watch : {
      getDeviceType(){
        this.responsiveAction();
      },
    },
    methods : {
      ...mapMutations('mediaImgs', ['setTargetObjectIndex']),
      ...mapMutations('mediaImgs', ['updateMediaImgsObjectItem']),
      ...mapMutations('mediaImgs', ['deleteMediaImgsObjectItem']),
      closeEditor(){
        this.isShowEditor = false;
      },
      responsiveAction(){
        if(this.getDeviceType==2){ // モバイルの時
          this.deleteMoveEvent();
          this.setModalAtMobilePosition();
        } else {
          this.registMoveEvent();
        }
      },
      setModalAtMobilePosition(){
        const modal = document.getElementById('media-img-update-wrapper');
        modal.style.left = "";
        modal.style.top = ""; // topの指定を消す
      },
      registMoveEvent(){
        const target = document.getElementById('media-img-update-wrapper');
        target.addEventListener('mousedown', this.move, false);
        target.addEventListener('touchstart', this.move, false);
      },
      deleteMoveEvent(){
        const target = document.getElementById('media-img-update-wrapper');
        target.removeEventListener('mousedown', this.move, false);
        target.removeEventListener('touchstart', this.move, false);
      },
      getOneImg(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaImg;
      },
      init(index){
        const storeImgData = Object.assign({},this.getOneImg(index));
        for(let key of Object.keys(storeImgData)){
          this.imgDatas[key] = this.fixStrToNum(key, storeImgData[key]);
        }
      },
      updateImgData(key, value){
        this.updateMediaImgsObjectItem({index:this.index, key:key, value:this.fixStrToNum(key, value)});
        this.imgDatas[key] = this.getOneImg(this.index)[key];
        const targetDomId = 'media-img-wrapper'+this.index;
        const targetDom = document.getElementById(targetDomId);
        const event = new CustomEvent('imgDataUpdated');
        targetDom.dispatchEvent(event);
      },
      checkTypeNum(key){
        const num_type_keys = ["type", "width","height","degree","left","top","opacity"];
        return num_type_keys.includes(key);
      },
      fixStrToNum(key, value){
        let reTypedValue;
        if(this.checkTypeNum(key)){
          reTypedValue = Number(value);
        } else {
          reTypedValue = value;
        }
        return reTypedValue;
      },
      getStyleSheetValue(element,property){ // ↑でcssの値を取得するための関数
        if (!element || !property) {
          return null;
        }
        var style = window.getComputedStyle(element);
        var value = style.getPropertyValue(property);
        return value;
      },
      // 位置操作用
      move(event){
        const move_target_dom = document.getElementById('media-img-update-wrapper');
        moveStart(event, move_target_dom);
      },
    },
    created(){
      document.body.addEventListener('showImgSetting', (e)=>{
        this.index = e.detail.index;
        this.init(this.index);
        this.isShowEditor = true;
      });

      document.body.addEventListener('closeImgSetting', (e)=>{
        this.isShowEditor = false;
      });

      document.body.addEventListener('objectStatusChanged', (e)=>{
        const objInfo = e.detail;
        if(objInfo.type==0 && objInfo.index==this.index){
          this.init(this.index);
        }
      })

      document.body.addEventListener('objectDeleted', (e)=> {
        const delObjs = e.detail.objs;
        let isDeleted = false;
        delObjs.forEach(obj=>{
          if(obj.type==0 && obj.index==this.index){
            isDeleted = true;
          }
        });
        if(isDeleted){ this.isShowEditor = false; }
      });

    },
    mounted(){
      this.responsiveAction();
    },
  }

</script>

<style scoped>
#media-img-update-wrapper{
  position: absolute;
  z-index: 30;
  color: white;
}
#media-img-update-wrapper:hover{
  cursor: all-scroll;
}

.item-frame:hover{
  cursor: all-scroll;
}
.media-img-settings {
  padding: 15px 45px;
}


.close-icon-wrapper {
  display: inline-block;
  position: absolute;
  top: 0px;
  right: 0px;
  z-index: 3;
  padding: 5px;
}
.close-icon:hover {
  cursor: pointer;
}

.setting-type-num,
.setting-type-color {
  margin-bottom: 15px;
}

.disp-space-between {
  display: flex;
  justify-content: space-between;
}

.input-num {
  width: 100px;
}

.hidden {
  display: none;
}


@media screen and (min-width:481px) {
  #media-img-update-wrapper{
    left: 100px;
    top: 100px;
    width: 300px;
    padding: 5px;
    background-color: rgba(35,40,50,0.85);
    border-radius: 6px;
  }

}

@media screen and (max-width:480px) {
  #media-img-update-wrapper{
    bottom: 50px;  
    max-height: 50vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .media-img-settings {
    max-height: 200px;
    overflow-y: scroll;
  }


  .item-frame {
    width:92%;
    background-color: rgba(35,40,50,0.85);
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
  }
}

</style>