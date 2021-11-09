<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : mediaSetting['roomBackgroundColor']}">

    <!-- Roomヘッダ -->
    <room-header-component
    :isShowUpdateButton=true
    :isShowLinkToShow=true
    :roomId="mediaSetting['id']"
    @update-room="updateRoom">
    </room-header-component>

    <!-- Room画像コンポーネント -->
    <room-img-component
     :roomImgUrl="roomImg['url']"
     :roomImgWidth="roomImg['width'] + 'px'"
     :roomImgHeight="roomImg['height'] + 'px'"
     :roomImgOpacity="roomImg['opacity']"
     :roomImgLayer="roomImg['layer']"
     :isShowRoomImg="mediaSetting['isShowImg']"
      v-on:parent-action="showModal"
      ref="roomImg">
    </room-img-component>

    <!-- Roomオーディオコンポーネント -->
    <room-audio-component
     :maxAudioNum="mediaSetting['maxAudioNum']"
     :roomAudios="roomAudios"
     ref="roomAudio">
    </room-audio-component>

    <!-- Room動画(=youtube)コンポーネント -->
    <room-movie-component
    v-show="mediaSetting['isShowMovie']"
    :isLoopYoutube="roomMovie['isLoop']"
    :roomMovieLayer="roomMovie['layer']"
     ref="roomMovie">
    </room-movie-component>


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
        <div id="disp-media-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaSettingModal')">
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
    :movieFrameWidth="roomMovie['width']"
    :movieFrameHeight="roomMovie['height']"
    :isLoopYoutube="roomMovie['isLoop']">
    </movie-setting-component>

    <!-- Room設定コンポーネント -->
    <media-setting-component
    v-show="isShowModal['mediaSettingModal']"
    v-on:close-modal="closeModal"
    v-on:delete-room-img="deleteRoomImg"
    :transitionName="transitionName"
    :isPublic="mediaSetting['isPublic']"
    :roomName="mediaSetting['name']"
    :roomDescription="mediaSetting['description']"
    :roomBackgroundColor="mediaSetting['roomBackgroundColor']"
    :isShowRoomImg="mediaSetting['isShowImg']"
    :roomImgWidth="roomImg['width']"
    :roomImgHeight="roomImg['height']"
    :roomImgOpacity="roomImg['opacity']">
    </media-setting-component>


  </div>
</template>

<script>
import RoomHeader from './RoomHeaderComponent.vue';
import ImgSelect from './ImgSelectComponent.vue';
import AudioSelect from './AudioSelectComponent.vue';
import MovieSetting from './MovieSettingComponent.vue';
import RoomAudio from './RoomAudioComponent.vue';
import MediaSetting from './MediaSettingComponent.vue';
import RoomImg from './RoomImgComponent.vue';
import RoomMovie from './RoomMovieComponent.vue';

export default {
  components : {
    RoomHeader,
    ImgSelect,
    AudioSelect,
    MovieSetting,
    RoomAudio,
    MediaSetting,
    RoomImg,
    RoomMovie,
  },
  props: [
    'roomImgData',
    'roomAudiosData',
    'roomMovieData',
    'mediaSettingData',
  ],
  data : () => {
    return {
      getReadyCreateMovieFrame : false,
      autoPlay : false,
      transitionName : 'slide-in',
      isShowModal : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'mediaSettingModal' : false,
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
    // ●Room読み込み時の初期化処理

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
    
    // ●Room作成用の処理
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
    setRoomImgUrl(type, id, url) {
      this.roomImg['type'] = type;
      this.roomImg['id'] = id;
      this.roomImg['url'] = url;
      this.mediaSetting['isShowImg'] = true;
    },
    judgeDelImg(url) {
      if(this.roomImg['url'] == url){
        this.roomImg['type'] = "";
        this.roomImg['id'] = "";
        this.roomImg['url'] = "";
      }
    }, 
    deleteRoomImg(){
      this.roomImg['type'] = 0;
      this.roomImg['id'] = 0;
      this.roomImg['url'] = "";
    },
    addAudio(audio) {
      this.$refs.roomAudio.addAudio(audio);
    },
    judgeDelAudio(url) {
      this.$refs.roomAudio.judgeDelAudio(url);
    },
    createMovieFrame(){
      let vars = {
        'videoId' : this.roomMovie['videoId'],
        'width' : this.roomMovie['width'],
        'height' : this.roomMovie['height'],
      };
      this.$refs.roomMovie.createYtPlayer(vars);
      this.mediaSetting['isShowMovie'] = true;
    },
    deleteMovieFrame(){
      this.$refs.roomMovie.deleteYtPlayer();
      this.mediaSetting['isShowMovie'] = false;
    },
    getFinishTime(){
      if(this.roomMovie['videoId'] != ""){
        this.$refs.roomMovie.setMovieDurationToFinishTime();
      } else {
        this.$refs.roomAudio.setLongestAudioDurationToFinishTime();
      }
    },
    updateRoom() {
      this.getFinishTime();
      const url = '/room/update';
      let room_datas = {
        'img' : this.roomImg,
        'audios' : this.roomAudios,
        'movie' : this.roomMovie,
        'setting' : this.mediaSetting,
      }
      this.message = "room情報を更新中です...";
      axios.post(url, room_datas)
        .then(response =>{
          alert(response.data.message);
          this.message = "";
        })
        .catch(error => {            
          alert('failed!');
          this.message = "";
        })
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
      this.$refs.roomAudio.validEditMode();
      window.onYouTubeIframeAPIReady = () => {
        this.getReadyCreateMovieFrame = true;
      }
    });
  },
  watch : {
    getReadyCreateMovieFrame : function(newVal){
      if(this.mediaSetting['isShowMovie'] == true 
      && this.roomMovie['videoId'] != ""
      && newVal == true){
        this.createMovieFrame();
      }
    },
  }


}
</script>

<style scoped>
@import "../../css/roomCommon.css";
@import "../../css/roomModals.css";

</style>