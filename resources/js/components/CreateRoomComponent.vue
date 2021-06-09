<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : roomSetting['roomBackgroundColor']}">

    <!-- Room画像コンポーネント -->
    <room-img-component
     :roomImgUrl="roomImg['url']"
     :roomImgWidth="roomImg['width'] + 'px'"
     :roomImgHeight="roomImg['height'] + 'px'"
     :roomImgLayer="roomImg['layer']"
     :isShowRoomImg="roomSetting['isShowImg']"
      v-on:parent-action="showModal"
      ref="roomImg">
    </room-img-component>

    <!-- Roomオーディオコンポーネント -->
    <room-audio-component
     :maxAudioNum="roomSetting['maxAudioNum']"
     :roomAudios="roomAudios"
     ref="roomAudio">
    </room-audio-component>

    <!-- Room動画(=youtube)コンポーネント -->
    <room-movie-component
    v-show="roomSetting['isShowMovie']"
    :isLoopYoutube="moviePlayerSettings['isLoop']"
    :roomMovieLayer="moviePlayerSettings['layer']"
     ref="roomMovie">
    </room-movie-component>

    <room-create-button
    :roomImg="roomImg"
    :roomAudios="roomAudios"
    :moviePlayerSettings="moviePlayerSettings"
    :roomSetting="roomSetting">
    </room-create-button>



    <!-- 画像&オーディオ 選択モーダル表示ボタン -->
    <div id="disp-modal-zone" @click="closeModal">
      <div id="disp-modal-wrapper">

        <!-- 画像 -->
        <div id="disp-img-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('imgModal')">
          <i class="fas fa-image fa-2x"></i>
        </div>
        <!-- オーディオ -->
        <div id="disp-audio-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('audioModal')">
          <i class="fas fa-music fa-2x"></i>
        </div>
        <!-- 動画 -->
        <div id="disp-movie-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('movieModal')">
          <i class="fab fa-youtube fa-2x"></i>
        </div>
        <!-- Room設定 -->
        <div id="disp-room-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('roomSettingModal')">
          <i class="fas fa-cog fa-2x"></i>
        </div>
      </div>
    </div>



    <!-- 画像選択コンポーネント -->
    <img-select-component 
    v-show="isShowModal['imgModal']" 
    v-on:close-modal="closeModal" 
    v-on:set-room-img="setRoomImgUrl"
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
    :isLoopYoutube="moviePlayerSettings['isLoop']">
    </movie-setting-component>

    <!-- Room設定コンポーネント -->
    <room-setting-component
    v-show="isShowModal['roomSettingModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName"
    :roomName="roomSetting['name']"
    :roomBackgroundColor="roomSetting['roomBackgroundColor']"
    :isShowRoomImg="roomSetting['isShowImg']"
    :roomImgWidth="roomImg['width']"
    :roomImgHeight="roomImg['height']">
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
import RoomCreateButton from './RoomCreateButtonComponent.vue';

export default {
  components : {
    ImgSelect,
    AudioSelect,
    MovieSetting,
    RoomAudio,
    RoomSetting,
    RoomImg,
    RoomMovie,
    RoomCreateButton,
  },
  data : () => {
    return {
      transitionName : 'right-slide',
      isShowModal : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'roomSettingModal' : false,
      },
      roomSetting : {
        'name' : "",
        'roomBackgroundColor' : "#333333", // 黒
        'isShowImg' : true,
        'isShowMovie' : false,
        'maxAudioNum' : 5,
      },
      roomImg : {
        'type' : "",
        'id' : "",
        'url' : "",
        'width' : 500,
        'height' : 500,
        'layer' : 0,
      },
      moviePlayerSettings : {
        'videoId' : "",
        'width' : "500",
        'height' : "420",
        'isLoop' : false,
        'layer' : 1,
      },
      // maxAudioNum : 5,
      roomAudios : [],


    }
  },
  methods : {
    showModal(target){
      // this.$refs.disp_modal_wrapper.stopPropagation(); // 親要素のcloseModalメソッドの発火を防ぐ
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
    setRoomImgUrl(type, id, url) {
      this.roomImg['type'] = type;
      this.roomImg['id'] = id;
      this.roomImg['url'] = url;
      this.roomSetting['isShowImg'] = true;
    },
    judgeDelImg(url) {
      if(this.roomImg['url'] == url){
        this.roomImg['type'] = "";
        this.roomImg['id'] = "";
        this.roomImg['url'] = "";
      }
    },
    addAudio(audio) {
      this.$refs.roomAudio.addAudio(audio);
    },
    judgeDelAudio(url) {
      this.$refs.roomAudio.judgeDelAudio(url);
    },
    createMovieFrame(){
      let vars = {
        'videoId' : this.moviePlayerSettings['videoId'],
        'width' : this.moviePlayerSettings['width'],
        'height' : this.moviePlayerSettings['height'],
      };
      this.$refs.roomMovie.createYtPlayer(vars);
      this.roomSetting['isShowMovie'] = true;
    },
    deleteMovieFrame(){
      this.$refs.roomMovie.deleteYtPlayer();
      this.roomSetting['isShowMovie'] = false;
    },
    
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
    background-color:black;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

  }

  #disp-modal-wrapper {
    z-index: 1;
    /* background-color: ghostwhite;
    box-shadow: -1px 1px 5px lightgrey; */
  }

  .icon-wrapper {
    padding: 12px;
  }
  .icon-wrapper:hover {
    background-color: rgba(255,255,255,0.2);
  }

  #disp-img-modal-wrapper {
    color:lightseagreen;
  }
  #disp-movie-modal-wrapper {
    color: orangered;
  }
  #disp-audio-modal-wrapper {
    color: gold;
  }
  #disp-room-setting-modal-wrapper {
    color: lightgray;
  }

  .hidden {
    display: none;
  }

  
  /* Modal表示アニメーション */

  /* .right-slide-enter-to, .right-slide-leave {
    transform: translate(0px, 0px);
  } */

  .right-slide-enter-active, .right-slide-leave-active {
    transform: translate(0px, 0px);
    transition: all 500ms
    /* cubic-bezier(0, 0, 0.2, 1) 0ms; */
  }

  .right-slide-enter, .right-slide-leave-to {
    transform: translateX(100vw) 
  }



</style>