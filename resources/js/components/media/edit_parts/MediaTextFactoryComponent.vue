<template>
  <transition :name="transitionName">
    <div id="select-modal" @click.stop @touchstart.stop>
      <div id="area-wrapper">
        <div id="media-text-setting-area" class="flex column a-start">
          <h2 id="media-text-setting-title">メディアテキスト設定</h2>

          <!-- テキストプレビュー -->
          <div id="text-preview-wrapper" class="setting">
            <div class="flex a-center j-s-between">
              <h3 class="sub-title preview-title">プレビュー</h3>
              <!-- 追加 -->
              <div id="media-text-add-wraper" class="mt3 flex column" @click="addText()">
                <button class="add-text-button">+追加</button>
              </div>              
            </div>

            <input
            id="text-preview"
            :style="previewStyle" v-model="getTextData['text']">

          </div>

          <!-- フォントサイズ -->
          <div id="font-size-wrapper" class="setting flex j-s-between a-center">
            <h3 class="sub-title mr10 mb0">サイズ</h3>
            <input type="number" class="mr10 w60px" :value="getTextData['font_size']" @input="updateTextData({key:'font_size', value:$event.target.value})">
            <div class="flex a-center">
              <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('font_size')"></i>
              <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('font_size')"></i>
            </div>
          </div>

          <!-- 色 -->
          <div id="text-color-wrapper" class="setting flex column">
            <h3 class="sub-title">色</h3>
            <input type="color" :value="getTextData['color']" @input="updateTextData({key:'color',value:$event.target.value})">
          </div>

          <!-- フォントスタイル(font-family) -->
          <div id="font-style-wrapper" class="setting flex column">
            <h3 class="sub-title">フォント</h3>
            <div class="flex">
              <div class="flex column mr5">
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

          <!-- 透過度 -->
          <div id="opacity-wrapper" class="setting flex column">
            <h3 class="sub-title">透過度:</h3>
            <input type="range" v-model="getTextData['opacity']" @mousedown.stop name="opacity" min="0" max="1" step="0.05">
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
  computed :  {
    ...mapGetters('media', ['getMediaId']),
    ...mapGetters('mediaTextFactory', ['getTextData']),
    ...mapGetters('fontFamilyList', ['getFontFamilyList']),
    ...mapGetters('japaneseFontFamilyList', ['getJapaneseFontFamilyList']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
    font_options:function(){
      if(this.selected_category == "normal"){
        return this.font_list;
      } else if(this.selected_category == "japanese"){
        return this.font_list_japanese;
      }
    },
    previewStyle(){
      const style = {
        "color" : this.getTextData['color'],
        "font-family" : this.selected_font,
      }
      return style;
    },
  },
  watch : {
    selected_category(new_val){ this.updateTextData({key:"font_category", value:new_val})},
    selected_font(new_val){ this.updateTextData({key:"font_family", value:new_val})},
  },
  methods : {
    ...mapMutations('mediaTextFactory', ['updateTextData']),
    ...mapMutations('mediaTexts', ['addMediaTextsObjectItem']),
    closeModal() {
      this.$emit('close-modal');
    },
    minusOneValue(data_key){this.updateTextData({key:data_key, value:Number(this.getTextData[data_key]-1)})},
    plusOneValue(data_key){this.updateTextData({key:data_key, value:Number(this.getTextData[data_key]+1)})},
    addText(){
      const mediaText = Object.assign({},this.getTextData);
      this.addMediaTextsObjectItem(mediaText);
      this.updateTextData({key:"left",value:Number(this.getTextData["left"]+20)})
      this.updateTextData({key:"top",value:Number(this.getTextData["top"]+20)})
    },
  },
  created(){
    this.selected_category = this.getTextData['font_category'];
    this.selected_font = this.getTextData['font_family'];
    this.font_list = Object.assign({},this.getFontFamilyList);
    this.font_list_japanese = Object.assign({},this.getJapaneseFontFamilyList);
  },
}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/googleFontList.css";
@import "/resources/css/googleJapaneseFontList.css";

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

  .sub-title {
    font-size: 15px;
  }
  .sub-sub-title {
    font-size: 13px;
    color: darkgrey;
  }

  .preview-title {
    display: inline-block;
    font-size: 12px;
    color: grey;
  }


  .add-text-icon {
    padding: 5px;
    margin-right: 3px;
    color: orange;
  }
  .add-text-button{
    border: 1px solid white;
    border-radius: 3px;
    background-color: orange;
    color: white;
    padding: 0 12px;
    font-size: 16px;
  }
  .add-text-button:hover{
    background-color: darkorange;
  }
  .add-text-button:focus{
    background-color: darkorange;
  }

  #text-preview-wrapper {
    width: 90%;
  }

  #text-preview {
    width: 100%;
    font-size: 18px;
    margin-bottom: 3px;
  }

  #text-preview:hover {
    outline: 1px solid lightgreen;
  }

  .setting-title {
    margin-bottom: 5px;
    font-size: 15px;
  }

  #font-category {
    width : 100px;
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



@media screen and (max-width:480px) {
  
  #area-wrapper {
    padding: 20px;
  }
  
  #media-text-setting-area {
    margin : 0;
  }
}

</style>