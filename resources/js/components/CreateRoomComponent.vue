<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : roomBackgroundColor}">

    <!-- Room画像コンポーネント -->
    <room-img-component
     :roomImgUrl="roomImgUrl"
     :isShowYoutube="isShowYoutube"
      v-on:parent-action="showImgModal">
    </room-img-component>

    <!-- Room動画(=youtube)コンポーネント -->
    <room-movie-component
    v-show="isShowYoutube"
    :isShowYoutube="isShowYoutube"
    :youtubePlayerVars="youtubePlayerVars"
     ref="roomMovie">
    </room-movie-component>

    <!-- Roomオーディオコンポーネント -->
    <room-audio-component
     :roomAudios="roomAudios"
     ref="roomAudio">
    </room-audio-component>


    <!-- 画像&オーディオ 選択モーダル表示ボタン -->
    <div id="disp-modal-zone">
      <div id="disp-modal-wrapper">

        <!-- 画像 -->
        <div id="disp-img-modal-wrapper" class="icon-wrapper" v-on:click="showImgModal()">
          <i class="fas fa-image fa-2x"></i>
        </div>
        <!-- 動画 -->
        <div id="disp-movie-modal-wrapper" class="icon-wrapper" v-on:click="showMovieModal()">
          <i class="fab fa-youtube fa-2x"></i>
        </div>
        <!-- オーディオ -->
        <div id="disp-audio-modal-wrapper" class="icon-wrapper" v-on:click="showAudioModal()">
          <i class="fas fa-music fa-2x"></i>
        </div>
        <!-- Room設定 -->
        <div id="disp-room-setting-modal-wrapper" class="icon-wrapper" v-on:click="showRoomSettingModal()">
          <i class="fas fa-cog fa-2x"></i>
        </div>
      </div>
    </div>


    <!-- 画像選択コンポーネント -->
    <img-select-component 
    v-show="isShow['imgModal']" 
    v-on:close-modal="closeModal" 
    v-on:set-img-url="setRoomImgUrl"
    v-on:img-del-notice="judgeDelImg">
    </img-select-component>

    <!-- 動画設定コンポーネント -->
    <movie-setting-component
    v-show="isShow['movieModal']"
    v-on:close-modal="closeModal"
    v-on:create-movie-frame="createMovieFrame">
    </movie-setting-component>

    <!-- オーディオ選択コンポーネント -->
    <audio-select-component 
    v-show="isShow['audioModal']" 
    v-on:close-modal="closeModal" 
    v-on:add-audio="addAudio"
    v-on:audio-del-notice="judgeDelAudio">
    </audio-select-component>

    <!-- Room設定コンポーネント -->
    <room-setting-component
    v-show="isShow['roomSettingModal']"
    v-on:close-modal="closeModal"
    :roomBackgroundColor="roomBackgroundColor">
    </room-setting-component>


  </div>
</template>

<script>
import ImgSelect from './ImgSelectComponent.vue';
import AudioSelect from './AudioSelectComponent.vue';
import MovieSetting from './MovieSettingComponent.vue';
import RoomAudio from './RoomAudioComponent.vue';
import RoomSetting from './RoomSettingComponent.vue';
import RoomImg from './RoomImgComponent.vue';
import RoomMovie from './RoomMovieComponent.vue';
export default {
  components : {
    ImgSelect,
    AudioSelect,
    MovieSetting,
    RoomAudio,
    RoomSetting,
    RoomImg,
    RoomMovie,
  },
  data : () => {
    return {
      roomBackgroundColor : "#ffffff", // 黒
      button_text : "画像選択",
      isShow : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'roomSettingModal' : false,
      },
      roomImgUrl : "",
      maxAudioNum : 5,
      roomAudios : [],
      isShowYoutube : false,
      youtubePlayerVars : {
        'videoId' : "",
        'width' : "500",
        'height' : "320",
      },
      youtubePlaySettings : {},
      isLoopYoutube : false,
    }
  },
  methods : {
    showImgModal() {
      this.showModal('imgModal');
    },
    showAudioModal() {
      this.showModal('audioModal');
    },
    showMovieModal() {
      this.showModal('movieModal');
    },
    showRoomSettingModal() {
      this.showModal('roomSettingModal');
    },
    showModal(target){
      for(let key in this.isShow){
        if(key != target){
          this.isShow[key] = false;
        } else if( key == target){
          this.isShow[key] = true;
        }
      }
    },
    closeModal() {
      for(let key in this.isShow){
        this.isShow[key] = false;
      }
    },
    setRoomImgUrl(url) {
      this.roomImgUrl = url;
    },
    judgeDelImg(url) {
      if(this.roomImgUrl == url){
        this.roomImgUrl = "";
      }
    },
    addAudio(audio) {
      this.$refs.roomAudio.addAudio(audio);
    },
    judgeDelAudio(url) {
      this.$refs.roomAudio.judgeDelAudio(url);
    },
    createMovieFrame(){
      let vars = this.youtubePlayerVars;
      this.$refs.roomMovie.createYtPlayer(vars);
    }
    
  },
  created() {},
  mounted() {},

}
</script>

<style>
  #field {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 2;
    width: 100%;
    height: 100%;

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  #disp-modal-zone {
    position: absolute;
    top: 0;
    right: 0;

    height: 100%;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

  }

  #disp-modal-wrapper {
    background-color: ghostwhite;
    box-shadow: -1px 1px 5px lightgrey;
  }

  .icon-wrapper {
    padding: 20px;
  }

  #disp-img-modal-wrapper {
    background-color:lightseagreen;
  }

  #disp-movie-modal-wrapper {
    color: orangered;
  }

  #disp-audio-modal-wrapper {
    background-color: gold;
  }

  #disp-room-setting-modal-wrapper {
    background-color: lightslategray;
    color: white;
  }

  .hidden {
    display: none;
  }

</style>