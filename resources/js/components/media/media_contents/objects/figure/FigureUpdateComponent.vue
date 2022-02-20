<template>
  <!-- Media図形-->
  <div id="media-figure-update-wrapper" :class="{hidden:!isEditMode}"
  v-show="isShowEditor" @click.stop @touchstart.stop>
    <div class="item-frame">
      <!-- クローズアイコン -->
      <div class="close-icon-wrapper" @mousedown.stop :class="{hidden:isMobile}">
        <i class="fas fa-times fa-3x close-icon" @click="closeEditor"></i>
      </div>

      <!-- 図形設定 -->
      <div class="media-figure-settings">

        <!-- カラー系の設定 -->
        <div class="setting-type-color mt10">
          <div class="flex j-s-between a-center fill-input-wrapper mb15">
            <label class="fill-flag m0">
              <input type="checkbox" @mousedown.stop :checked="figureDatas['isDrawFill']" @input="updateFigureData('isDrawFill',$event.target.checked)">
              <span class="label">塗りつぶし</span>
            </label>
            <div class="fill-color">
              <span class="label grey">色</span>
              <input type="color" @mousedown.stop :value="figureDatas['fillColor']" @input="updateFigureData('fillColor', $event.target.value)">
            </div>
          </div>

          <div class="flex j-s-between a-center stroke-input-wrapper mb15">
            <label class="stroke-flag m0">
              <input type="checkbox" @mousedown.stop :checked="figureDatas['isDrawStroke']" @input="updateFigureData('isDrawStroke',$event.target.checked)">
              <span class="label">枠線</span>
            </label>
            <div class="stroke-color">
              <span class="label grey">色</span>
              <input type="color" @mousedown.stop :value="figureDatas['strokeColor']" @input="updateFigureData('strokeColor', $event.target.value)">
            </div>
          </div>
        </div>

        <!-- 透過度 -->
        <div class="opacity-input-wrapper mt25 mb25 j-s-between flex a-center">
          <span class="label w-auto">透過度</span>
          <input type="range" class="w100px" :value="figureDatas['globalAlpha']" @mousedown.stop @input="updateFigureData('globalAlpha',$event.target.value)" name="opacity" id="" min="0" max="1" step="0.05">
          <input type="number" class="input-num w50px font-12" :value="figureDatas['globalAlpha']" @input="updateFigureData('globalAlpha', $event.target.value)" name="opacity" min="0" max="1" step="0.05">
        </div>

        <!-- 図形の種類 -->
        <div class="flex mb10 j-s-between a-center type-input-wrapper">
          <span class="label">種類</span>
          <select id="update-figure-type" name="種類" class="input-num" v-model="type">
            <option v-for="(figureType) in figureTypeList" :key="figureType['code']" :value="figureType['code']">{{figureType['name']}}</option>
          </select>
        </div>

        <!-- 数値系の設定 -->
        <div class="setting-type-num">
          <div class="flex mb10 j-s-between a-center x-position-wrapper">
            <span class="label">位置(横)</span>
            <div class="flex a-center">
              <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('left')"></i>
              <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('left')"></i>
            </div>
            <input type="number" class="input-num" :value="figureDatas['left']" @input="updateFigureData('left', $event.target.value)" min="-1000" max="10000">
          </div>

          <div class="flex mb10 j-s-between a-center y-position-wrapper">
            <span class="label">位置(縦)</span>
            <div class="flex a-center">
              <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('top')"></i>
              <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('top')"></i>
            </div>
            <input type="number" class="input-num" :value="figureDatas['top']" @input="updateFigureData('top', $event.target.value)" min="-1000" max="10000">
          </div>

          <div class="flex mb10 j-s-between a-center degree-wrapper">
            <span class="label">回転</span>
            <div class="flex a-center">
              <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('degree')"></i>
              <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('degree')"></i>
            </div>
            <input type="number" class="input-num" :value="figureDatas['degree']" @input="updateFigureData('degree', $event.target.value)">
          </div>

          <div class="flex mb10 j-s-between a-center width-input-wrapper">
            <div class="w60px"><span class="label">横幅</span><span class="font-11 grey">[px]</span></div>
            <div class="flex a-center">
              <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('width')"></i>
              <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('width')"></i>
            </div>
            <input type="number" class="input-num" :value="figureDatas['width']" @input="updateFigureData('width', $event.target.value)" min="0" max="10000">
          </div>
          <div class="flex mb10 j-s-between a-center height-input-wrapper">
            <div class="w60px"><span class="label">縦幅</span><span class="font-11 grey">[px]</span></div>
            <div class="flex a-center">
              <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('height')"></i>
              <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('height')"></i>
            </div>
            <input type="number" class="input-num" :value="figureDatas['height']" @input="updateFigureData('height', $event.target.value)" min="0" max="10000">
          </div>

          <div class="flex mb10 j-s-between a-center layer-input-wrapper">
            <span class="label">重ね順</span>
            <div class="flex a-center">
              <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('layer')"></i>
              <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('layer')"></i>
            </div>
            <input type="number" class="input-num" :value="figureDatas['layer']" @input="updateFigureData('layer', $event.target.value)"  min="0" max="100">
          </div>

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

        figureTypeList : [
          {code : 1, name : "四角形"},
          {code : 2, name : "丸"},
        ],
        
        figureDatas : {
          "type" : 1,
          "left" : 0,
          "top" : 0,
          "width" : 0,
          "height" : 0,
          "degree" : 0,
          "layer" : 0,
          "globalAlpha" : 0,
          "isDrawFill" : false,
          "isDrawStroke" : false,
          "fillColor" : "",
          "strokeColor" : "",
        },
      }
    },
    computed : {
      ...mapGetters('mediaFigures', ['getMediaFigure']),
      ...mapGetters('deviceType', ['getDeviceType']),
      isMobile(){ return (this.getDeviceType==2) ? true : false; },
      type:{
        get(){
          return this.figureDatas['type'];
        },
        set(val){
          this.figureDatas['type'] = val;
        },
      },
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
      type(){
        this.updateFigureData('type', this.figureDatas['type']);
      },
      getDeviceType(){
        this.responsiveAction();
      },

    },
    methods : {
      ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      ...mapMutations('mediaFigures', ['updateMediaFiguresObjectItem']),
      ...mapMutations('mediaFigures', ['deleteMediaFiguresObjectItem']),
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
        const modal = document.getElementById('media-figure-update-wrapper');
        modal.style.left = "";
        modal.style.top = ""; // topの指定を消す
      },
      registMoveEvent(){
        const target = document.getElementById('media-figure-update-wrapper');
        target.addEventListener('mousedown', this.move, false);
        target.addEventListener('touchstart', this.move, false);
      },
      deleteMoveEvent(){
        const target = document.getElementById('media-figure-update-wrapper');
        target.removeEventListener('mousedown', this.move, false);
        target.removeEventListener('touchstart', this.move, false);
      },
      getOneFigure(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaFigure;
      },
      init(index){
        const storeFigureData = Object.assign({},this.getOneFigure(index));
        for(let key of Object.keys(storeFigureData)){
          this.figureDatas[key] = this.fixStrToNum(key, storeFigureData[key]);
        }
      },
      plusOneValue(data_key){
        const new_val = Number(this.figureDatas[data_key]) + 1;
        this.updateFigureData(data_key, new_val);
      },
      minusOneValue(data_key){
        const new_val = Number(this.figureDatas[data_key]) - 1;
        this.updateFigureData(data_key, new_val);
      },
      updateFigureData(key, value){
        this.updateMediaFiguresObjectItem({index:this.index, key:key, value:this.fixStrToNum(key, value)});
        this.figureDatas[key] = this.getOneFigure(this.index)[key];
        const targetDomId = 'canvas_wrapper'+this.index;
        const targetDom = document.getElementById(targetDomId);
        const event = new CustomEvent('figureDataUpdated');
        targetDom.dispatchEvent(event);
      },
      checkTypeNum(key){
        const num_type_keys = ["type", "width","height","degree","left","top","globalAlpha"];
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
        const move_target_dom = document.getElementById('media-figure-update-wrapper');
        moveStart(event, move_target_dom);
      },
    },
    created(){
      document.body.addEventListener('showFigureSetting', (e)=>{
        this.index = e.detail.index;
        this.init(this.index);
        this.isShowEditor = true;
      });

      document.body.addEventListener('closeFigureSetting', (e)=>{
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
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

#media-figure-update-wrapper{
  position: absolute;
  z-index: 30;
  color: white;
}
#media-figure-update-wrapper:hover{
  cursor: all-scroll;
}

.item-frame:hover{
  cursor: all-scroll;
}
.media-figure-settings {
  padding: 15px 25px;
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

.label {
  width: 60px;
  color: lightgrey;
  font-size: 13px;
}

.input-num {
  width: 60px;
  color: darkgray;
}

.btns {
  border-radius: 50%;
  padding: 5px 4px;
}
.btns:hover { cursor: pointer;}
.plus-btn {
  color: palevioletred;
  border: 1.5px solid palevioletred;
}
.minus-btn {
  color: deepskyblue;
  border: 1.5px solid deepskyblue;
}

.hidden {
  display: none;
}

.grey { color: grey;}



@media screen and (min-width:481px) {
  #media-figure-update-wrapper{
    left: 100px;
    top: 100px;
    width: 300px;
    padding: 5px;
    background-color: rgba(35,40,50,0.85);
    border-radius: 6px;
  }

}

@media screen and (max-width:480px) {
  #media-figure-update-wrapper{
    bottom: 50px;  
    max-height: 50vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .media-figure-settings {
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