<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">
        <div id="media-text-setting-area" class="flex column a-start">
          <h2 id="media-text-setting-title">メディアテキスト設定</h2>

          <!-- テキストプレビュー -->
          <div id="text-preview-wrapper" class="setting">
            <input
            id="text-preview"
            :style="previewStyle" v-model="getTextData['text']">
          </div>
          <!-- 追加 -->
          <div id="media-text-add-wraper" class="setting flex column" @click="addText()">
            <div class="flex a-center">
              <i class="fas fa-plus add-text-icon"></i>
              <button class="add-text-button">追加</button>
            </div>
          </div>

          <!-- フォントサイズ -->
          <div id="font-size-wrapper" class="setting flex column">
            <span style="margin-right:5px">フォントサイズ</span>
            <div>
              <input type="number" id="font-size" :value="getTextData['font_size']" @input="updateTextData({key:'font_size', value:$event.target.value})">
              <span>[px]</span>
            </div>
          </div>

          <!-- 色 -->
          <div id="text-color-wrapper" class="setting flex column">
            <span>色</span>
            <input type="color" :value="getTextData['color']" @input="updateTextData({key:'color',value:$event.target.value})">
          </div>

          <!-- 透過度 -->
          <div id="opacity-wrapper" class="setting flex column">
            <span>透過度:</span>
            <input type="range" v-model="getTextData['opacity']" @mousedown.stop name="opacity" min="0" max="1" step="0.05">
            <!-- <input type="range" :value="getTextData['opacity']" @mousedown.stop @input="updateTextData({key:'opacity',value:$event.target.value})" name="opacity" id="" min="0" max="1" step="0.05"> -->
          </div>

          <!-- フォントスタイル(font-family) -->
          <div id="font-style-wrapper" class="setting flex column">
            <span>フォントスタイル</span>
            <select id="" v-model="selected">
              <option v-for="option in options" :value="option.value" :key="option.id">
                {{option.name}}
              </option>
            </select>
          </div>

        </div>
      </div>
      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../change_display_parts/CloseModalIconComponent.vue'

export default {
  components : {
    closeModalBar,
    closeModalIcon,
  },
  props : [
    'transitionName',
  ],
  data : () => {
    return {
      selected : "",
      options : "",
    }
  },
  computed :  {
    ...mapGetters('media', ['getMediaId']),
    ...mapGetters('mediaTextFactory', ['getTextData']),
    ...mapGetters('fontFamilyList', ['getFontFamilyList']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
    previewStyle(){
      const style = {
        "color" : this.getTextData['color'],
        "font-family" : this.selected,
      }
      return style;
    },
  },
  watch : {
    selected(new_val){ this.updateTextData({key:"font_family", value:new_val})},
  },
  methods : {
    ...mapMutations('mediaTextFactory', ['updateTextData']),
    ...mapMutations('mediaTexts', ['addMediaTextsObjectItem']),
    closeModal() {
      this.$emit('close-modal');
    },
    addText(){
      const mediaText = Object.assign({},this.getTextData);
      this.addMediaTextsObjectItem(mediaText);
      this.updateTextData({key:"left",value:Number(this.getTextData["left"]+20)})
      this.updateTextData({key:"top",value:Number(this.getTextData["top"]+20)})
    },
  },
  created(){
    this.selected = this.getTextData['font_family'];
    this.options = Object.assign({},this.getFontFamilyList);
  },
}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";
@import "/resources/css/googleFontList.css";

  #media-text-setting-area {
    margin: 20px 0;
    width: 95%;
    overflow-y: scroll;
  }

  #media-text-setting-title{
    font-weight: bold;
    font-size: 14px;
    margin: 10px 0 30px 0;
    background-color: lightslategrey;
    border-radius: 5px;
    padding: 3px 10px;
  }

  .setting {
    margin-bottom : 20px;
  }

  .add-text-icon {
    padding: 5px;
    margin-right: 3px;
    color: orange;
  }
  .add-text-button{
    border: 1px solid white;
    border-radius: 4px;
    background-color: transparent;
    color: white;
    font-size: 12px;
  }
  .add-text-button:hover{
    background-color: orange;
  }
  .add-text-button:focus{
    background-color: orange;
  }

  #text-preview-wrapper {
    width: 90%;
    margin-bottom: 5px;
  }

  #text-preview {
    width: 100%;
    font-size: 18px;
  }

  #text-preview:hover {
    outline: 1px solid lightgreen;
  }


  #font-size {
    width: 70px;
  }

  .setting-title {
    margin-bottom: 5px;
    font-size: 15px;
  }


    /* トグル */
  .toggle-outer{
    width: 38px;
    height: 17px;
    padding: 2px;
    border-radius: 20px;
    background-color: grey;
    transition-duration: 0.4s;
  }

  .toggle-inner {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: white;
  }



@media screen and (max-width:480px) {
  
  #area-wrapper {
    padding: 20px;
  }
  
  #media-text-setting-area {
    margin : 0;
  }
}

</style>