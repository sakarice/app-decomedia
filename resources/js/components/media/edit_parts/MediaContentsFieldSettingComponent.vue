<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">

        <div id="media-contents-field-setting-area" class="flex column a-start">
          <h2 id="media-contents-field-setting-title">メディアエリア設定</h2>

          <!-- メディア背景色設定 -->
          <div id="media-bg-color-wraper" class="setting mb20">
            <h3 class="setting-title">背景色</h3>
            <label for="">
              <input :value="getMediaContentsField['color']" @input="updateMediaContentsFieldObjectItem({key:'color', value:$event.target.value})" type="color" id="media-bg-color">
              カラー選択
            </label>
          </div>
          <!-- メディアエリアサイズ設定 -->
          <div id="media-size-wraper" class="setting w90">
            <h3 class="setting-title">サイズ</h3>
            <div class="flex column">
              <div class="setting-width mb15 flex j-s-between a-center">
                <h4 class="sub-sub-title mb0 w50px">
                  <i class="fas fa-arrows-alt-h icon"></i>
                  横幅
                </h4>
                <div class="flex a-center">
                  <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('width')"></i>
                  <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('width')"></i>
                </div>
                <input id="set-contents-area-frame-width" class="setting" :value="getMediaContentsField['width']" @input="updateStoreData('width',$event.target.value)" type="number" placeholder="横幅">
              </div>
              <div class="setting-height mb15 flex j-s-between a-center">
                <h4 class="sub-sub-title mb0 w50px">
                  <i class="fas fa-arrows-alt-v icon"></i>
                  縦幅
                </h4>
                <div class="flex a-center">
                  <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('height')"></i>
                  <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('height')"></i>
                </div>
                <input id="set-contents-area-height" class="setting" :value="getMediaContentsField['height']" @input="updateStoreData('height',$event.target.value)" type="number" placeholder="縦幅">
              </div>
            </div>
          </div>

          <!-- 背景画像設定 -->
          <div id="media-bg-img-wraper" class="setting mb20">
            <h3 class="setting-title">背景画像</h3>
            <span class="clear-bg-img cursor-p" @mousedown="clearBgImg()" @touchstart="clearBgImg()">削除する</span>
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
    return {}
  },
  computed :  {
    ...mapGetters('mediaContentsField',['getMediaContentsField']),
  },
  methods : {
    ...mapMutations('mediaContentsField', ['updateMediaContentsFieldObjectItem']),
    closeModal() {
      this.$emit('close-modal');
    },
    clearBgImg(){
      const listener_elem = document.getElementById('media-contents-field-wrapper');      
      const clearBgImgEvent = new CustomEvent('clearBgImg');
      listener_elem.dispatchEvent(clearBgImgEvent);
    },
    minusOneValue(data_key){this.updateMediaContentsFieldObjectItem({key:data_key, value:Number(this.getMediaContentsField[data_key]-1)})},
    plusOneValue(data_key){this.updateMediaContentsFieldObjectItem({key:data_key, value:Number(this.getMediaContentsField[data_key]+1)})},
    updateStoreData(key,value){
      this.updateMediaContentsFieldObjectItem({key:key, value:value});
    },
  },
  mounted : function() {
  },
}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";

  #media-contents-field-setting-area {
    margin: 20px 0;
    width: 95%;
    overflow-y: scroll;
  }

  #media-contents-field-setting-title{
    font-weight: bold;
    font-size: 14px;
    margin: 10px 0 30px 0;
    background-color: rgb(40,40,40);
    border-radius: 5px;
    padding: 3px 10px;
  }

  #media-size-wraper input {
    width: 60px;
  }

  .sub-sub-title {
    font-size: 15px;
  }

  .public-state-icon {
    margin-left: 10px;
    opacity: 0.5;
  }
  .public-state-icon:hover {
    opacity: 1;
  }
  .open-icon {
    color: lawngreen;
  }
  .lock-icon {
    color: yellow;
  }

  .clear-bg-img {
    color: blue;
    text-decoration-line: underline;
  }

  .message-label {
    font-size: 10px;
  }

  .setting-title {
    margin-bottom: 5px;
    /* font-weight: bold; */
    font-size: 15px;
  }

  .img-config-input {
    margin-bottom : 5px;
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

  .is-public-outer {
    background-color: lawngreen;
  }
  .is-public-inner {
    margin-left: 19px;
  }

  .cursor-p:hover {
    cursor: pointer;
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
  
  #media-contents-field-setting-area {
    margin : 0;
  }
}

</style>