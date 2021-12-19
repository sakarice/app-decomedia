<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">

        <div id="media-setting-area" class="flex column a-start">
          <h2 id="media-setting-title">メディア設定</h2>

          <!-- メディア名設定 -->
          <div id="media-name-wraper" class="setting">
            <h3 class="setting-title">Media名</h3>
            <label for="">
              <input :value="getMediaSetting['name']" @input="updateMediaSettingObjectItem({key:'name', value:$event.target.value})" type="text" id="media-name" placeholder="Media名">
            </label>
          </div>

          <!-- メディアの説明文設定 -->
          <div id="media-description-wrapper" class="setting">
            <h3 class="setting-title">説明</h3>
            <label for="">
              <!-- <input :value="mediaDescription" @input="updateMediaDescription" type="text"> -->
              <textarea :value="getMediaSetting['description']" @input="updateMediaSettingObjectItem({key:'description', value:$event.target.value})" type="text" id="media-description" rows="4" cols="30" maxlength="120" placeholder="説明文"></textarea>
            </label>
          </div>

          <!-- メディア背景色設定 -->
          <div id="media-bg-color-wraper" class="setting">
            <h3 class="setting-title">Media背景</h3>
            <label for="">
              <input :value="getMediaSetting['mediaBackgroundColor']" @input="updateMediaSettingObjectItem({key:'mediaBackgroundColor', value:$event.target.value})" type="color" id="media-bg-color">
              カラー選択
            </label>
          </div>

          <!-- 公開/非公開設定 -->
          <div id="media-is-public-wraper" class="setting">
            <div class="flex">
              <h3 class="setting-title">公開 / 非公開</h3>
              <i v-show="getMediaSetting['isPublic']" class="fas fa-door-open fa-lg public-state-icon open-icon"></i>
              <i v-show="!(getMediaSetting['isPublic'])" class="fas fa-lock fa-lg public-state-icon lock-icon"></i>
            </div>
            <div class="flex a-center">
              <div class="toggle-outer flex a-center" @click="changePublicState" :class="{'is-public-outer' : getMediaSetting['isPublic']}">
                <div class="toggle-inner" :class="{'is-public-inner' : getMediaSetting['isPublic']}"></div>
              </div>
              <span style="margin-left:10px;opacity:0.7">{{openState}}</span>
            </div>
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
      window_width : "",
      window_height : "",
    }
  },
  computed :  {
    ...mapGetters('mediaSetting',['getMediaSetting']),
    openState(){ return (this.getMediaSetting['isPublic'] ? "公開":"非公開") },
  },  
  methods : {
    ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
    closeModal() {
      this.$emit('close-modal');
    },
    toggleIsShowMediaImg() { // media画像の表示/非表示を切り替え
      this.updateMediaSettingObjectItem({key:'isShowImg', value:!this.getMediaSetting['isShowImg']});
    },
    deleteMediaImg(){
      this.updateMediaSettingObjectItem({type:0, id:0, url:""});
    },
    changePublicState() {
      this.updateMediaSettingObjectItem({key:'isPublic', value:!this.getMediaSetting['isPublic']});
    },
  },
  mounted : function() {
    this.window_width = window.innerWidth;
    this.window_height = window.innerHeight;
  },
}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";

  #media-setting-area {
    margin: 20px 0;
    width: 95%;
    overflow-y: scroll;
  }

  #media-setting-title{
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



@media screen and (max-width:480px) {
  
  #area-wrapper {
    padding: 20px;
  }
  
  #media-setting-area {
    margin : 0;
  }
}

</style>