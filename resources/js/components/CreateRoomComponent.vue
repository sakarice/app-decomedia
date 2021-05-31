<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : roomBackgroundColor}">

    <!-- Room画像コンポーネント -->
    <room-img-component
     :roomImgUrl="roomImg['url']"
     :roomImgWidth="roomImgWidth + 'px'"
     :roomImgHeight="roomImgHeight + 'px'"
     :roomImgLayer="roomImgLayer"
     :isShowRoomImg="isShowContent['roomImg']"
      v-on:parent-action="showImgModal"
      ref="roomImg">
    </room-img-component>

    <!-- Roomオーディオコンポーネント -->
    <room-audio-component
     :roomAudios="roomAudios"
     ref="roomAudio">
    </room-audio-component>

    <!-- Room動画(=youtube)コンポーネント -->
    <room-movie-component
    v-show="isShowYoutube"
    :isShowYoutube="isShowYoutube"
    :isLoopYoutube="isLoopYoutube"
    :youtubePlayerVars="youtubePlayerVars"
    :roomMovieLayer="roomMovieLayer"
     ref="roomMovie">
    </room-movie-component>

    <!-- 画像&オーディオ 選択モーダル表示ボタン -->
    <div id="disp-modal-zone">
      <div id="disp-modal-wrapper">

        <!-- 画像 -->
        <div id="disp-img-modal-wrapper" class="icon-wrapper" v-on:click="showImgModal()">
          <i class="fas fa-image fa-2x"></i>
        </div>
        <!-- オーディオ -->
        <div id="disp-audio-modal-wrapper" class="icon-wrapper" v-on:click="showAudioModal()">
          <i class="fas fa-music fa-2x"></i>
        </div>
        <!-- 動画 -->
        <div id="disp-movie-modal-wrapper" class="icon-wrapper" v-on:click="showMovieModal()">
          <i class="fab fa-youtube fa-2x"></i>
        </div>
        <!-- Room設定 -->
        <div id="disp-room-setting-modal-wrapper" class="icon-wrapper" v-on:click="showRoomSettingModal()">
          <i class="fas fa-cog fa-2x"></i>
        </div>
      </div>
    </div>



    <!-- 画像選択コンポーネント -->
    <img-select-component 
    v-show="isShowModal['imgModal']" 
    v-on:close-modal="closeModal" 
    v-on:set-img-url="setRoomImgUrl"
    v-on:img-del-notice="judgeDelImg"
    :transitionName="transitionName">

    </img-select-component>

    <!-- オーディオ選択コンポーネント -->
    <audio-select-component 
    v-show="isShowModal['audioModal']" 
    v-on:close-modal="closeModal"
    v-on:add-audio="addAudio"
    v-on:audio-del-notice="judgeDelAudio"
    :transitionName="transitionName">
    </audio-select-component>

    <!-- 動画設定コンポーネント -->
    <movie-setting-component
    v-show="isShowModal['movieModal']"
    v-on:close-modal="closeModal"
    v-on:create-movie-frame="createMovieFrame"
    v-on:delete-movie-frame="deleteMovieFrame"
    :transitionName="transitionName"
    :isLoopYoutube="isLoopYoutube">
    </movie-setting-component>

    <!-- Room設定コンポーネント -->
    <room-setting-component
    v-show="isShowModal['roomSettingModal']"
    v-on:close-modal="closeModal"
    v-on:toggle-room-img="toggleRoomImg"
    :transitionName="transitionName"
    :roomBackgroundColor="roomBackgroundColor"
    :isShowRoomImg="isShowContent['roomImg']"
    :roomImgWidth="roomImgWidth"
    :roomImgHeight="roomImgHeight">
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
      transitionName : 'right-slide',
      isShowModal : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'roomSettingModal' : false,
      },
      isShowContent : {
        'roomImg' : true,
        'roomMovie' : false,
      },
      roomImgUrl : "",
      roomImgWidth : "300", // 画像コンポーネントに渡す際、単位[px]を付与
      roomImgHeight : "300", // 画像コンポーネントに渡す際、単位[px]を付与
      roomImg : {
        'url' : "",
        'width' : "500px",
        'height' : "400px",
      },
      maxAudioNum : 5,
      roomAudios : [],
      isShowYoutube : false,
      youtubePlayerVars : {
        'videoId' : "",
        'width' : "500",
        'height' : "420",
      },
      youtubePlaySettings : {},
      isLoopYoutube : false,
      roomImgLayer : 1,
      roomMovieLayer : 2,

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
      for(let key in this.isShowModal){
        if(key != target){
          this.isShowModal[key] = false;
        } else if( key == target){
          this.isShowModal[key] = true;
        }
      }
      this.transitionName = '';
    },
    closeModal() {
      this.transitionName = 'right-slide';
      for(let key in this.isShowModal){
        this.isShowModal[key] = false;
      }
    },
    setRoomImgUrl(url) {
      this.roomImg['url'] = url;
      this.isShowContent['roomImg'] = true;
    },
    judgeDelImg(url) {
      if(this.roomImg['url'] == url){
        this.roomImg['url'] = "";
      }
    },
    toggleRoomImg(){ // room画像の表示/非表示を切り替え
      if(this.isShowContent['roomImg']){
        this.isShowContent['roomImg'] = false;
      } else if(!(this.isShowContent['roomImg'])){
        this.isShowContent['roomImg'] = true;
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
      this.isShowYoutube = true;
    },
    deleteMovieFrame(){
      this.$refs.roomMovie.deleteYtPlayer();
      this.isShowYoutube = false;
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
    z-index: 3;
    height: 100%;
    background-color:aliceblue;

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