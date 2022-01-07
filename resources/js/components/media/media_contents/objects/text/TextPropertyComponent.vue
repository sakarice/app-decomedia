<template>
  <!-- Mediaテキスト-->
  <div id="media-text-update-wrapper" :class="{hidden:!isEditMode}"
  v-show="isShowEditor" @click.stop @touchstart.stop>
    <div class="item-frame">
      <!-- クローズアイコン -->
      <div class="close-icon-wrapper" @mousedown.stop :class="{hidden:isMobile}">
        <i class="fas fa-times fa-3x close-icon" @click="closeEditor"></i>
      </div>

      <!-- テキスト設定 -->
      <div class="media-text-settings">
        <!-- 数値系の設定 -->
        <div class="setting-wrapper setting-num">
          <div class="disp-space-between x-position-wrapper">
            <span>配置座標(x):</span>
            <input type="number" class="input-num" :value="textDatas['left']" @input="updateTextData('left', $event.target.value)" min="-1000" max="10000">
          </div>

          <div class="disp-space-between y-position-wrapper">
            <span>配置座標(y):</span>
            <input type="number" class="input-num" :value="textDatas['top']" @input="updateTextData('top', $event.target.value)" min="-1000" max="10000">
          </div>

          <div class="disp-space-between width-input-wrapper">
            <span>横幅[px]:</span>
            <input type="number" class="input-num" :value="textDatas['width']" @input="updateTextData('width', $event.target.value)" min="0" max="10000">
          </div>

          <div class="disp-space-between degree-wrapper">
            <span>回転:</span>
            <input type="number" class="input-num" :value="textDatas['degree']" @input="updateTextData('degree', $event.target.value)">
          </div>

          <div class="disp-space-between layer-input-wrapper">
            <span>重ね順:</span>
            <input type="number" class="input-num" :value="textDatas['layer']" @input="updateTextData('layer', $event.target.value)"  min="0" max="100">
          </div>
        </div>

        <!-- カラー系の設定 -->
        <div class="setting-wrapper setting-color">
          <div class="disp-space-between stroke-input-wrapper">
            <div class="text-color">
              <span>色:</span>
              <input type="color" @mousedown.stop :value="textDatas['color']" @input="updateTextData('color', $event.target.value)">
            </div>
          </div>
        </div>

        <div class="setting-wrapper disp-space-between type-input-wrapper">
          <!-- フォントスタイル(font-family) -->
          <div id="font-style-wrapper" class="setting flex column">
            <h3 class="sub-title">フォント</h3>
            <div class="flex column">
              <div class="flex column" style="margin-right:5px">
                <h4 class="sub-sub-title">カテゴリ</h4>
                <select id="font-category" v-model="selected_category">
                  <option v-for="category in font_category" :value="category" :key="category.id">
                    {{category}}
                  </option>
                </select>
              </div>
              <div class="flex column">
                <h4 class="sub-sub-title">スタイル</h4>
                <select id="" v-model="selected_font">
                  <option v-for="option in font_options" :value="option.value" :key="option.id">
                    {{option.name}}
                  </option>
                </select>
              </div>
            </div>
          </div>
        </div>


        <div class="setting-wrapper opacity-input-wrapper">
          <span>透過度:</span>
          <input type="range" :value="textDatas['opacity']" @mousedown.stop @input="updateTextData('opacity',$event.target.value)" name="opacity" id="" min="0" max="1" step="0.05">
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
       
        textDatas : {
          "type" : 1,
          "left" : 0,
          "top" : 0,
          "width" : 0,
          "height" : 0,
          "degree" : 0,
          "layer" : 0,
          "opacity" : 0,
          "color" : "",
          "font_category" : "",
          "font_family" : "",
        },
        selected_category : "",
        selected_font : "",
        font_category : [
          "normal",
          "japanese",
        ],
        font_list : "",
        font_list_japanese : "",
        options : "",
      }
    },
    computed : {
      ...mapGetters('mediaTexts', ['getMediaText']),
      ...mapGetters('deviceType', ['getDeviceType']),
      ...mapGetters('fontFamilyList', ['getFontFamilyList']),
      ...mapGetters('japaneseFontFamilyList', ['getJapaneseFontFamilyList']),
      font_options:function(){
        if(this.selected_category == "normal"){
          return this.font_list;
        } else if(this.selected_category == "japanese"){
          return this.font_list_japanese;
        }
      },
      isMobile(){ return (this.getDeviceType==2) ? true : false; },
      type:{
        get(){
          return this.textDatas['type'];
        },
        set(val){
          this.textDatas['type'] = val;
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
        this.updateTextData('type', this.textDatas['type']);
      },
      getDeviceType(){
        this.responsiveAction();
      },
      selected_category(new_val){ this.updateMediaTextsObjectItem({index:this.index,key:"font_category", value:new_val})},
      selected_font(new_val){ this.updateMediaTextsObjectItem({index:this.index,key:"font_family", value:new_val})},
    },
    methods : {
      ...mapMutations('mediaTexts', ['setTargetObjectIndex']),
      ...mapMutations('mediaTexts', ['updateMediaTextsObjectItem']),
      ...mapMutations('mediaTexts', ['deleteMediaTextsObjectItem']),
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
        const modal = document.getElementById('media-text-update-wrapper');
        modal.style.left = "";
        modal.style.top = ""; // topの指定を消す
      },
      registMoveEvent(){
        const target = document.getElementById('media-text-update-wrapper');
        target.addEventListener('mousedown', this.move, false);
        target.addEventListener('touchstart', this.move, false);
      },
      deleteMoveEvent(){
        const target = document.getElementById('media-text-update-wrapper');
        target.removeEventListener('mousedown', this.move, false);
        target.removeEventListener('touchstart', this.move, false);
      },
      getOneText(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaText;
      },
      init(index){
        const storeTextData = Object.assign({},this.getOneText(index));
        for(let key of Object.keys(storeTextData)){
          this.textDatas[key] = this.fixStrToNum(key, storeTextData[key]);
        }
        this.setCategoryAndFontFromStoreData();
      },
      updateTextData(key, value){
        this.textDatas[key] = this.fixStrToNum(key, value);
        this.updateMediaTextsObjectItem({index:this.index, key:key, value:this.fixStrToNum(key, value)});
        const targetDomId = 'text_wrapper'+this.index;
        const targetDom = document.getElementById(targetDomId);
        const event = new CustomEvent('textDataUpdated');
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
        const move_target_dom = document.getElementById('media-text-update-wrapper');
        moveStart(event, move_target_dom);
      },
      setCategoryAndFontFromStoreData(){
        this.selected_category = this.textDatas['font_category'];
        this.selected_font = this.textDatas['font_family'];
      }
    },
    created(){
      // イベントの登録
      document.body.addEventListener('showTextSetting', (e)=>{
        this.index = e.detail.index;
        this.init(this.index);
        this.isShowEditor = true;
      });

      document.body.addEventListener('closeTextSetting', (e)=>{
        this.isShowEditor = false;
      });

      document.body.addEventListener('objectStatusChanged', (e)=>{
        const objInfo = e.detail;
        if(objInfo.type==2 && objInfo.index==this.index){
          this.init(this.index);
        }
      })

      document.body.addEventListener('objectDeleted', (e)=> {
        const delObjs = e.detail.objs;
        let isDeleted = false;
        delObjs.forEach(obj=>{
          if(obj.type==2 && obj.index==this.index){
            isDeleted = true;
          }
        });
        if(isDeleted){ this.isShowEditor = false; }
      });

    },
    mounted(){
      this.responsiveAction();
      this.setCategoryAndFontFromStoreData();
      this.font_list = Object.assign({},this.getFontFamilyList);
      this.font_list_japanese = Object.assign({},this.getJapaneseFontFamilyList);
    },
  }

</script>

<style scoped>
#media-text-update-wrapper{
  position: absolute;
  z-index: 30;
  color: white;
}
#media-text-update-wrapper:hover{
  cursor: all-scroll;
}

.item-frame:hover{
  cursor: all-scroll;
}
.media-text-settings {
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

.setting-wrapper{
  margin-bottom: 15px;
}

.disp-space-between {
  display: flex;
  justify-content: space-between;
}

.input-num {
  width: 100px;
}

.sub-title {
  font-size: 15px;
}
.sub-sub-title {
  font-size: 13px;
  color: darkgrey;
}


.hidden {
  display: none;
}


@media screen and (min-width:481px) {
  #media-text-update-wrapper{
    left: 100px;
    top: 100px;
    width: 300px;
    padding: 5px;
    background-color: rgba(35,40,50,0.85);
    border-radius: 6px;
  }

}

@media screen and (max-width:480px) {
  #media-text-update-wrapper{
    bottom: 50px;  
    max-height: 50vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .media-text-settings {
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