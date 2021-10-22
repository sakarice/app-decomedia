<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : roomSetting['roomBackgroundColor']}">

    <!-- Roomヘッダ -->
    <room-header-component
    :isShowLinkToEdit="isMyRoom"
    :roomId="roomSetting['id']"
    :roomName="roomSetting['name']">
    </room-header-component>

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

        <!-- 選択モーダル表示ボタン -->
    <div id="disp-modal-zone" @click="closeModal">
      <div id="disp-modal-wrapper">
        <!-- いいねアイコン -->
        <div id="disp-room-like-modal-wrapper" class="icon-wrapper" v-if="isLogin && !(isMyRoom)">
          <like-room-component
          :roomId="roomSetting['id']">
          </like-room-component>
        </div>


        <!-- Room作成者情報 -->
        <div id="disp-room-owner-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('roomOwnerInfo')">
          <i class="fas fa-user-circle fa-2x"></i>
          <!-- ユーザプロフィール -->
          <room-owner-info-component
          v-show="isShowModal['roomOwnerInfo']"
          :isLogin="isLogin"
          :roomId="roomSetting['id']">
          </room-owner-info-component>
        </div>
        <!-- Room情報 -->
        <div id="disp-room-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('roomInfoModal')">
          <i class="fas fa-file-alt fa-2x setting-icon"></i>
        </div>
        <!-- 音楽 -->
        <!-- <div id="disp-room-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('roomAudio')">
          <i class="fas fa-music fa-2x setting-icon"></i>
        </div> -->
      </div>
    </div>

    <room-info-component
    v-show="isShowModal['roomInfoModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName"
    :name="roomSetting['name']"
    :description="roomSetting['description']">
    </room-info-component>


  </div>
</template>

<script>
import RoomHeader from './RoomHeaderComponent.vue';
import RoomAudio from './RoomAudioComponent.vue';
import RoomSetting from './RoomSettingComponent.vue';
import RoomImg from './RoomImgComponent.vue';
import RoomMovie from './RoomMovieComponent.vue';
import RoomInfo from './RoomInfoComponent.vue';
import RoomOwnerInfo from './RoomOwnerInfoComponent.vue';
import LikeRoom from './LikeRoomComponent.vue';


export default {
  components : {
    RoomHeader,
    RoomAudio,
    RoomSetting,
    RoomImg,
    RoomMovie,
    RoomInfo,
    RoomOwnerInfo,
    LikeRoom,
  },
  props: [
    'isLogin',
    'roomImgData',
    'roomAudiosData',
    'roomMovieData',
    'roomSettingData',
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
      let room_id = JSON.parse(this.roomSettingData).id;
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
      this.roomSetting['isPublic'] = tmpSettingData.isPublic;
      this.roomSetting['name'] = tmpSettingData.name;
      this.roomSetting['description'] = tmpSettingData.description;
      this.roomSetting['finish_time'] = tmpSettingData.finish_time;
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
  created() {
    if(this.isLogin){ this.judgeIsMyRoom(); }
  },
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
      // 全オーディオの再生開始
      this.$refs.roomAudio.playAllAudio();
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