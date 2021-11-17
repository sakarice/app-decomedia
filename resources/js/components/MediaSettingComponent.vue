<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">

        <div id="setting-wrapper">

          <div id="media-is-public-wraper" class="setting">
            <p class="setting-title">公開/非公開</p>
            <i v-show="isPublic" @click="changePublicState" class="fas fa-door-open fa-lg public-state-icon open-icon"></i>
            <i v-show="!(isPublic)" @click="changePublicState" class="fas fa-lock fa-lg public-state-icon lock-icon"></i>
            <span class="state-description">{{showPublicState}}</span>
          </div>

          <div id="media-name-wraper" class="setting">
            <p class="setting-title">Media名</p>
            <label for="">
              <input :value="mediaName" @input="updateMediaName" type="text" id="media-name" placeholder="Media名">
            </label>
          </div>

          <div id="media-description-wrapper" class="setting">
            <p class="setting-title">説明</p>
            <label for="">
              <!-- <input :value="mediaDescription" @input="updateMediaDescription" type="text"> -->
              <textarea :value="mediaDescription" @input="updateMediaDescription" type="text" id="media-description" rows="4" cols="30" maxlength="120" placeholder="説明文"></textarea>
            </label>
          </div>

          <div id="media-bg-color-wraper" class="setting">
            <p class="setting-title">Media背景</p>
            <label for="">
              <input :value="mediaBackgroundColor" @input="updateMediaBgColor" type="color" id="media-bg-color">
              Media背景色
            </label>
          </div>

          <div id="media-img-setting-wrapper" class="setting">
            <p class="setting-title">Media画像</p>
            <input data-input-type="width" class="img-config-input" :value="getMediaImg['width']" @input="updateMediaImgObjectItem({ key:'width' ,value:$event.target.value})" type="text" size="5" placeholder="横幅">
            <span>[px] 横幅</span><span class="message-label"> (ブラウザの横幅：{{window_width}})</span><br>
            <input data-input-type="height" class="img-config-input" :value="getMediaImg['height']" @input="updateMediaImgObjectItem({ key:'height' ,value:$event.target.value})" type="text" size="5" placeholder="縦幅">
            <span>[px] 縦幅</span><span class="message-label"> (ブラウザの縦幅：{{window_height}})</span><br>
            <input data-input-type="opacity" class="img-config-input" :value="getMediaImg['opacity']" @input="updateMediaImgObjectItem({ key:'opacity' ,value:$event.target.value})" type="text" size="5" placeholder="透明度">
            <span>透明度(0～1)</span><br>
            <button v-on:click="toggleMediaImg">
              <span v-show="isShowMediaImg">非表示</span>
              <span v-show="!(isShowMediaImg)">表示</span>
            </button>
            <button v-on:click="deleteMediaImg">
              <span>削除</span>
            </button>
          </div>

        </div>

      </div>
      <i v-on:click="closeModal()" id="change-disp-modal" class="fas fa-times-circle fa-2x for-mobile"></i>
      <div class="close-icon-wrapper for-pc-tablet">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-left fa-3x"></i>
      </div>
    </div>
  </transition>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
export default {
  props : [
    'transitionName',
    'isPublic',
    'mediaName',
    'mediaDescription',
    'mediaBackgroundColor',
    'isShowMediaImg',
    'mediaImgWidth',
    'mediaImgHeight',
    'mediaImgOpacity',
  ],
  data : () => {
    return {
      window_width : "",
      window_height : "",
    }
  },
  computed :  {
    ...mapGetters('mediaImg',['getMediaImg']),
    showPublicState : function(){
      if(this.isPublic){
        return '公開（他のユーザも検索・閲覧できます）'
      } else if(!(this.isPublic)){
        return '非公開（他のユーザは検索・閲覧できません）'
      }
    }
  },  
  methods : {
    ...mapMutations('mediaImg', ['updateMediaImgContent']),
    ...mapMutations('mediaImg', ['updateMediaImgObjectItem']),
    closeModal() {
      this.$emit('close-modal');
    },
    updateMediaName(event) {
      this.$parent.mediaSetting['name'] = event.target.value;
    },
    updateMediaDescription(event) {
      this.$parent.mediaSetting['description'] = event.target.value;
    },
    updateMediaBgColor(event){ // カラーピッカーの変更に、Media背景色を同期させて即反映
      let value = event.target.value;
      this.$parent.mediaSetting['mediaBackgroundColor'] = value;
    },
    toggleMediaImg() { // media画像の表示/非表示を切り替え
      if(this.isShowMediaImg){
        this.$parent.mediaSetting['isShowImg'] = false;
      } else if(!(this.isShowMediaImg)){
        this.$parent.mediaSetting['isShowImg'] = true;
      }
    },
    deleteMediaImg(){
      this.updateMediaImgContent({type:0, id:0, url:""});
    },
    changePublicState() {
      if(this.isPublic){
        this.$parent.mediaSetting['isPublic'] = false;
      } else if(!(this.isPublic)){
        this.$parent.mediaSetting['isPublic'] = true;
      }
    }

  },
  mounted : function() {
    this.window_width = window.innerWidth;
    this.window_height = window.innerHeight;
  },
  watch : {
  }

}
</script>


<style>

@import "../../css/mediaEditModals.css";

  /* コンテンツのCSS */
  #setting-wrapper {
    margin: 20px 0;
    width: 75%;

    display: flex;
    flex-direction: column;
    align-items: flex-start;
    overflow-y: scroll;
  }

  .setting {
    margin-bottom : 20px;
  }

  .state-description {
    font-size: 12px;
  }

  .public-state-icon {
    margin-right: 5px;
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
  

  .message-label {
    font-size: 10px;
  }

  .setting-title {
    margin-bottom: 5px;
    font-weight: bold;
  }

  .img-config-input {
    margin-bottom : 5px;
  }


</style>