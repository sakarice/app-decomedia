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

    <!-- Media画像コンポーネント -->
    <media-img-component
     :mediaImgUrl="mediaImg['url']"
     :mediaImgWidth="mediaImg['width'] + 'px'"
     :mediaImgHeight="mediaImg['height'] + 'px'"
     :mediaImgOpacity="mediaImg['opacity']"
     :mediaImgLayer="mediaImg['layer']"
     :isShowMediaImg="mediaSetting['isShowImg']"
      v-on:parent-action="showModal"
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
    v-on:set-media-img="setMediaImgUrl"
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
    :movieFrameWidth="mediaMovie['width']"
    :movieFrameHeight="mediaMovie['height']"
    :isLoopYoutube="mediaMovie['isLoop']">
    </movie-setting-component>

    <!-- Room設定コンポーネント -->
    <media-setting-component
    v-show="isShowModal['mediaSettingModal']"
    v-on:close-modal="closeModal"
    v-on:delete-media-img="deleteMediaImg"
    :transitionName="transitionName"
    :isPublic="mediaSetting['isPublic']"
    :roomName="mediaSetting['name']"
    :roomDescription="mediaSetting['description']"
    :roomBackgroundColor="mediaSetting['roomBackgroundColor']"
    :isShowMediaImg="mediaSetting['isShowImg']"
    :mediaImgWidth="mediaImg['width']"
    :mediaImgHeight="mediaImg['height']"
    :mediaImgOpacity="mediaImg['opacity']">
    </media-setting-component>


  </div>
</template>

<script>
import RoomHeader from './RoomHeaderComponent.vue';
import ImgSelect from './ImgSelectComponent.vue';
import AudioSelect from './AudioSelectComponent.vue';
import MovieSetting from './MovieSettingComponent.vue';
import MediaAudio from './MediaAudioComponent.vue';
import MediaSetting from './MediaSettingComponent.vue';
import MediaImg from './MediaImgComponent.vue';
import MediaMovie from './MediaMovieComponent.vue';

export default {
  components : {
    RoomHeader,
    ImgSelect,
    AudioSelect,
    MovieSetting,
    MediaAudio,
    MediaSetting,
    MediaImg,
    MediaMovie,
  },
  props: [
    'mediaImgData',
    'mediaAudiosData',
    'mediaMovieData',
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
    // ●Room読み込み時の初期化処理

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
    setMediaImgUrl(type, id, url) {
      this.mediaImg['type'] = type;
      this.mediaImg['id'] = id;
      this.mediaImg['url'] = url;
      this.mediaSetting['isShowImg'] = true;
    },
    judgeDelImg(url) {
      if(this.mediaImg['url'] == url){
        this.mediaImg['type'] = "";
        this.mediaImg['id'] = "";
        this.mediaImg['url'] = "";
      }
    }, 
    deleteMediaImg(){
      this.mediaImg['type'] = 0;
      this.mediaImg['id'] = 0;
      this.mediaImg['url'] = "";
    },
    addAudio(audio) {
      this.$refs.mediaAudio.addAudio(audio);
    },
    judgeDelAudio(url) {
      this.$refs.mediaAudio.judgeDelAudio(url);
    },
    createMovieFrame(){
      let vars = {
        'videoId' : this.mediaMovie['videoId'],
        'width' : this.mediaMovie['width'],
        'height' : this.mediaMovie['height'],
      };
      this.$refs.mediaMovie.createYtPlayer(vars);
      this.mediaSetting['isShowMovie'] = true;
    },
    deleteMovieFrame(){
      this.$refs.mediaMovie.deleteYtPlayer();
      this.mediaSetting['isShowMovie'] = false;
    },
    getFinishTime(){
      if(this.mediaMovie['videoId'] != ""){
        this.$refs.mediaMovie.setMovieDurationToFinishTime();
      } else {
        this.$refs.mediaAudio.setLongestAudioDurationToFinishTime();
      }
    },
    updateRoom() {
      this.getFinishTime();
      const url = '/room/update';
      let room_datas = {
        'img' : this.mediaImg,
        'audios' : this.mediaAudios,
        'movie' : this.mediaMovie,
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
      this.$refs.mediaAudio.setPlayerInfo();
      this.$refs.mediaAudio.updateAudioThumbnail();
      this.$refs.mediaAudio.validEditMode();
      window.onYouTubeIframeAPIReady = () => {
        this.getReadyCreateMovieFrame = true;
      }
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
  }


}
</script>

<style scoped>
@import "../../css/roomCommon.css";
@import "../../css/roomModals.css";

</style>