<template>
  <div id="field"
   :style="{'background-color' : roomSetting['roomBackgroundColor']}">

    <!-- Room画像コンポーネント -->
    <room-img-component
     :roomImgUrl="roomImg['url']"
     :roomImgWidth="roomImg['width'] + 'px'"
     :roomImgHeight="roomImg['height'] + 'px'"
     :roomImgOpacity="roomImg['opacity']"
     :roomImgLayer="roomImg['layer']"
     :isShowRoomImg="roomSetting['isShowImg']"
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
    :isLoopYoutube="roomMovie['isLoop']"
    :roomMovieLayer="roomMovie['layer']"
     ref="roomMovie">
    </room-movie-component>

    <cancel-button></cancel-button>

  </div>
</template>

<script>
import RoomAudio from './RoomAudioComponent.vue';
import RoomSetting from './RoomSettingComponent.vue';
import RoomImg from './RoomImgComponent.vue';
import RoomMovie from './RoomMovieComponent.vue';
import CancelButton from './CancelButtonComponent.vue';

export default {
  components : {
    RoomAudio,
    RoomSetting,
    RoomImg,
    RoomMovie,
    CancelButton,
  },
  props: [
    'roomImgData',
    'roomAudiosData',
    'roomMovieData',
    'roomSettingData',
  ],
  data : () => {
    return {
      getReadyCreateMovieFrame : false,
      getReadyPlayAudio : false,
      getReadyPlayMovie : false,
      autoPlay : true,

      roomImg : {
        'type' : "",
        'id' : "",
        'url' : "",
        'width' : 500,
        'height' : 500,
        'opacity' : 1,
        'layer' : 0,
      },
      roomMovie : {
        'videoId' : "",
        'width' : "500",
        'height' : "420",
        'isLoop' : false,
        'layer' : 1,
      },
      roomAudios : [],

      roomSetting : {
        'id' : 0,
        'name' : "",
        'roomBackgroundColor' : "#333333", // 黒
        'isShowImg' : true,
        'isShowMovie' : false,
        'maxAudioNum' : 5,
      },


    }
  },
  methods : {
    initImg(){
      // this.roomImg['url'] = this.roomImgData.url;
      let tmpImgData = JSON.parse(this.roomImgData);
      this.roomImg['type'] = tmpImgData.type;
      this.roomImg['id'] = tmpImgData.id;
      this.roomImg['url'] = tmpImgData.url;
      this.roomImg['width'] = tmpImgData.width;
      this.roomImg['height'] = tmpImgData.height;
      this.roomImg['opacity'] = tmpImgData.opacity;
      this.roomImg['layer'] = tmpImgData.layer;
    },
    initMovie(){
      let tmpMovieData = JSON.parse(this.roomMovieData);
      this.roomMovie['videoId'] = tmpMovieData.videoId;
      this.roomMovie['width'] = tmpMovieData.width;
      this.roomMovie['height'] = tmpMovieData.height;
      this.roomMovie['isLoop'] = tmpMovieData.isLoop;
      this.roomMovie['layer'] = tmpMovieData.layer;
    },
    initAudio(){
      let tmpRoomAudios = JSON.parse(this.roomAudiosData);
      let audioNum = tmpRoomAudios.length;
      for(let i=0; i < audioNum; i++){
        tmpRoomAudios[i]['player_index'] = i; //再生プレイヤーを割り当て
        tmpRoomAudios[i]['isPlay'] = false;
        this.roomAudios.push(tmpRoomAudios[i]);
      }      
    },
    initSetting(){
      let tmpSettingData = JSON.parse(this.roomSettingData);
      this.roomSetting['id'] = tmpSettingData.id;
      this.roomSetting['name'] = tmpSettingData.name;
      this.roomSetting['roomBackgroundColor'] = tmpSettingData.background_color;
      this.roomSetting['isShowImg'] = tmpSettingData.is_show_img;
      this.roomSetting['isShowMovie'] = tmpSettingData.is_show_movie;
      this.roomSetting['maxAudioNum'] = tmpSettingData.max_audio_num;
    },
    setAudioThumbnail(){
      this.$refs.roomAudio.updateAudioThumbnail();
    },
    createMovieFrame(){
      let vars = {
        'videoId' : this.roomMovie['videoId'],
        'width' : this.roomMovie['width'],
        'height' : this.roomMovie['height'],
      };
      this.$refs.roomMovie.createYtPlayer(vars);
      
    },

  },
  created() {},
  mounted() {
    this.initImg();
    this.initMovie();
    this.initAudio();
    this.initSetting();

    // 全ての子コンポーネントが描画されてから実行する処理
    this.$nextTick(function(){
      this.$refs.roomAudio.setPlayerInfo();
      this.$refs.roomAudio.updateAudioThumbnail();
      window.onYouTubeIframeAPIReady = () => {
        this.getReadyCreateMovieFrame = true;
      }
    });

  },
  watch : {
    getReadyCreateMovieFrame : function(newVal){
      if(this.roomSetting['isShowMovie'] == true 
      && this.roomMovie['videoId'] != ""
      && newVal == true){
        this.createMovieFrame();
      }
    },
    getReadyPlayMovie : function(newVal){
      
    }
  },

}
</script>

<style>
  #field {
    /* opacity : 0.2; */

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

  .icon-wrapper {
    padding: 12px;
  }
  .icon-wrapper:hover {
    background-color: rgba(255,255,255,0.2);
  }

  .hidden {
    display: none;
  }



</style>