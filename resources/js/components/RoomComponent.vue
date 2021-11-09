<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : mediaSetting['roomBackgroundColor']}">

    <!-- Roomヘッダ -->
    <room-header-component
    :isShowLinkToEdit="isMyRoom"
    :roomId="mediaSetting['id']"
    :roomName="mediaSetting['name']">
    </room-header-component>

    <!-- Room画像コンポーネント -->
    <media-img-component
     :mediaImgUrl="mediaImg['url']"
     :mediaImgWidth="mediaImg['width'] + 'px'"
     :mediaImgHeight="mediaImg['height'] + 'px'"
     :mediaImgOpacity="mediaImg['opacity']"
     :mediaImgLayer="mediaImg['layer']"
     :isShowMediaImg="mediaSetting['isShowImg']"
      ref="mediaImg">
    </media-img-component>

    <!-- Roomオーディオコンポーネント -->
    <media-audio-component
     :maxAudioNum="mediaSetting['maxAudioNum']"
     :mediaAudios="mediaAudios"
     ref="mediaAudio">
    </media-audio-component>

    <!-- Room動画(=youtube)コンポーネント -->
    <media-movie-component
    v-show="mediaSetting['isShowMovie']"
    :isLoopYoutube="mediaMovie['isLoop']"
    :mediaMovieLayer="mediaMovie['layer']"
     ref="mediaMovie">
    </media-movie-component>

        <!-- 選択モーダル表示ボタン -->
    <div id="disp-modal-zone" @click="closeModal">
      <div id="disp-modal-wrapper">
        <!-- いいねアイコン -->
        <div id="disp-room-like-modal-wrapper" class="icon-wrapper" v-if="$store.getters.getIsLogin && !(isMyRoom)">
          <like-room-component
          :roomId="mediaSetting['id']">
          </like-room-component>
        </div>


        <!-- Room作成者情報 -->
        <div id="disp-room-owner-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('roomOwnerInfo')">
          <i class="fas fa-user-circle fa-2x"></i>
          <!-- ユーザプロフィール -->
          <room-owner-info-component
          v-show="isShowModal['roomOwnerInfo']"
          :roomId="mediaSetting['id']">
          </room-owner-info-component>
        </div>
        <!-- Room情報 -->
        <div id="disp-media-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('roomInfoModal')">
          <i class="fas fa-file-alt fa-2x setting-icon"></i>
        </div>
        <!-- 音楽 -->
        <!-- <div id="disp-media-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaAudio')">
          <i class="fas fa-music fa-2x setting-icon"></i>
        </div> -->
      </div>
    </div>

    <room-info-component
    v-show="isShowModal['roomInfoModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName"
    :name="mediaSetting['name']"
    :description="mediaSetting['description']">
    </room-info-component>


  </div>
</template>

<script>
import RoomHeader from './RoomHeaderComponent.vue';
import MediaAudio from './MediaAudioComponent.vue';
import MediaSetting from './MediaSettingComponent.vue';
import MediaImg from './MediaImgComponent.vue';
import MediaMovie from './MediaMovieComponent.vue';
import RoomInfo from './RoomInfoComponent.vue';
import RoomOwnerInfo from './RoomOwnerInfoComponent.vue';
import LikeRoom from './LikeRoomComponent.vue';


export default {
  components : {
    RoomHeader,
    MediaAudio,
    MediaSetting,
    MediaImg,
    MediaMovie,
    RoomInfo,
    RoomOwnerInfo,
    LikeRoom,
  },
  props: [
    'mediaImgData',
    'mediaAudiosData',
    'mediaMovieData',
    'mediaSettingData',
  ],
  data : () => {
    return {
      isMyRoom : false,
      getReadyCreateMovieFrame : false,
      getReadyPlayAudio : false,
      getReadyPlayMovie : false,
      autoPlay : true,

      transitionName : 'slide-in',
      isShowModal : {
        'roomOwnerInfo' : false,
        'roomInfoModal' : false,
      },
      mediaImg : {
        'type' : "",
        'id' : "",
        'url' : "",
        'width' : 500,
        'height' : 500,
        'opacity' : 1,
        'layer' : 0,
      },
      mediaMovie : {
        'videoId' : "",
        'width' : "500",
        'height' : "420",
        'isLoop' : false,
        'layer' : 1,
      },
      mediaAudios : [],

      mediaSetting : {
        'id' : 0,
        'isPublic' : true,  // 公開/非公開 デフォルトは公開
        'name' : "",
        'description' : "",
        'finish_time' : 0,
        'roomBackgroundColor' : "#333333", // 黒
        'isShowImg' : true,
        'isShowMovie' : false,
        'maxAudioNum' : 5,
      },


    }
  },
  methods : {
    judgeIsMyRoom(){
      let room_id = JSON.parse(this.mediaSettingData).id;
      let url = '/ajax/judgeIsMyRoom/' + room_id;
      console.log(url);
      axios.get(url)
        .then(response =>{
          this.isMyRoom = response.data.isMyRoom;
        })
        .catch(error => {
          console.log('あなたがroom作成者か判別できませんでした');
        })
    },
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
      this.transitionName = 'slide-in';
      for(let key in this.isShowModal){
        this.isShowModal[key] = false;
      }
    },
    initImg(){
      // this.mediaImg['url'] = this.mediaImgData.url;
      let tmpImgData = JSON.parse(this.mediaImgData);
      this.mediaImg['type'] = tmpImgData.type;
      this.mediaImg['id'] = tmpImgData.id;
      this.mediaImg['url'] = tmpImgData.url;
      this.mediaImg['width'] = tmpImgData.width;
      this.mediaImg['height'] = tmpImgData.height;
      this.mediaImg['opacity'] = tmpImgData.opacity;
      this.mediaImg['layer'] = tmpImgData.layer;
    },
    initMovie(){
      let tmpMovieData = JSON.parse(this.mediaMovieData);
      this.mediaMovie['videoId'] = tmpMovieData.videoId;
      this.mediaMovie['width'] = tmpMovieData.width;
      this.mediaMovie['height'] = tmpMovieData.height;
      this.mediaMovie['isLoop'] = tmpMovieData.isLoop;
      this.mediaMovie['layer'] = tmpMovieData.layer;
    },
    initAudio(){
      let tmpMediaAudios = JSON.parse(this.mediaAudiosData);
      let audioNum = tmpMediaAudios.length;
      for(let i=0; i < audioNum; i++){
        tmpMediaAudios[i]['player_index'] = i; //再生プレイヤーを割り当て
        tmpMediaAudios[i]['isPlay'] = false;
        this.mediaAudios.push(tmpMediaAudios[i]);
      }
    },
    initSetting(){
      let tmpSettingData = JSON.parse(this.mediaSettingData);
      this.mediaSetting['id'] = tmpSettingData.id;
      this.mediaSetting['isPublic'] = tmpSettingData.isPublic;
      this.mediaSetting['name'] = tmpSettingData.name;
      this.mediaSetting['description'] = tmpSettingData.description;
      this.mediaSetting['finish_time'] = tmpSettingData.finish_time;
      this.mediaSetting['roomBackgroundColor'] = tmpSettingData.background_color;
      this.mediaSetting['isShowImg'] = tmpSettingData.is_show_img;
      this.mediaSetting['isShowMovie'] = tmpSettingData.is_show_movie;
      this.mediaSetting['maxAudioNum'] = tmpSettingData.max_audio_num;
    },
    setAudioThumbnail(){
      this.$refs.mediaAudio.updateAudioThumbnail();
    },
    createMovieFrame(){
      let vars = {
        'videoId' : this.mediaMovie['videoId'],
        'width' : this.mediaMovie['width'],
        'height' : this.mediaMovie['height'],
      };
      this.$refs.mediaMovie.createYtPlayer(vars);
    },

  },
  created() {
    if(this.$store.getters.getIsLogin){ this.judgeIsMyRoom(); }
  },
  mounted() {
    this.initImg();
    this.initMovie();
    this.initAudio();
    this.initSetting();

    // 全ての子コンポーネントが描画されてから実行する処理
    this.$nextTick(function(){
      this.$refs.mediaAudio.setPlayerInfo();
      this.$refs.mediaAudio.updateAudioThumbnail();
      window.onYouTubeIframeAPIReady = () => {
        this.getReadyCreateMovieFrame = true;
      }
      // 全オーディオの再生開始
      this.$refs.mediaAudio.playAllAudio();
    });

  },
  watch : {
    getReadyCreateMovieFrame : function(newVal){
      if(this.mediaSetting['isShowMovie'] == true 
      && this.mediaMovie['videoId'] != ""
      && newVal == true){
        this.createMovieFrame();
      }
    },
    getReadyPlayMovie : function(newVal){
      
    }
  },

}
</script>

<style scoped>

@import "../../css/roomCommon.css";
@import "../../css/roomModals.css";
@import "../../css/modalAnimation.css";



  #disp-room-owner-modal-wrapper {
    color: white;
  }

  .icon-wrapper {
    padding: 12px;

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .setting-icon {
    color : lightgrey;
  }
 



@media screen and (min-width: 481px) {
  #disp-modal-zone {
    left: 0;
  }
  
}

@media screen and (max-width: 480px) {
  #disp-modal-zone {
    right: 0;
  }
  
}


</style>