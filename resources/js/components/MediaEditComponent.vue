<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : getMediaSetting['mediaBackgroundColor']}">

    <!-- Mediaヘッダ -->
    <media-header-component
    :isShowUpdateButton=true
    :isShowLinkToShow=true
    :mediaId="getMediaSetting['id']"
    @update-media="updateMedia">
    </media-header-component>

    <!-- Media画像コンポーネント -->
    <media-img-component
      v-on:parent-action="showModal"
      ref="mediaImg">
    </media-img-component>

    <!-- Mediaオーディオコンポーネント -->
    <media-audio-component
     :maxAudioNum="getMediaSetting['maxAudioNum']"
     ref="mediaAudio">
    </media-audio-component>

    <!-- Media動画(=youtube)コンポーネント -->
    <media-movie-component
    v-show="getMediaSetting['isShowMovie']"
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
        <!-- Media設定 -->
        <div id="disp-media-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaSettingModal')">
          <i class="fas fa-cog fa-2x"></i>
        </div>
      </div>
    </div>



    <!-- 画像選択コンポーネント -->
    <img-select-component 
    v-show="isShowModal['imgModal']" 
    v-on:close-modal="closeModal" 
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
    :transitionName="transitionName">
    </movie-setting-component>

    <!-- Media設定コンポーネント -->
    <media-setting-component
    v-show="isShowModal['mediaSettingModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName">
    </media-setting-component>

    <div v-show="isUploadingMedia">
      <overlay-component></overlay-component>
      <loading-component
      :message="'メディアを更新中です...'">
      </loading-component>
    </div>

  </div>
</template>

<script>
import { mapGetters, mapMutations} from 'vuex';
import MediaHeader from './MediaHeaderComponent.vue';
import ImgSelect from './ImgSelectComponent.vue';
import AudioSelect from './AudioSelectComponent.vue';
import MovieSetting from './MovieSettingComponent.vue';
import MediaAudio from './MediaAudioComponent.vue';
import MediaSetting from './MediaSettingComponent.vue';
import MediaImg from './MediaImgComponent.vue';
import MediaMovie from './MediaMovieComponent.vue';
import Overlay from './OverlayComponent.vue'
import Loading from './LoadingComponent.vue'

export default {
  components : {
    MediaHeader,
    ImgSelect,
    AudioSelect,
    MovieSetting,
    MediaAudio,
    MediaSetting,
    MediaImg,
    MediaMovie,
    Overlay,
    Loading,
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
      isUploadingMedia : false,
      isShowModal : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'mediaSettingModal' : false,
      },
      mediaAudios : [],

    }
  },
  computed : {
    ...mapGetters('mediaImg', ['getMediaImg']),
    ...mapGetters('mediaMovie', ['getMediaMovie']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
  },
  methods : {
    ...mapMutations('mediaImg', ['updateMediaImgObjectItem']),
    ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
    ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
    // ●Media読み込み時の初期化処理
    initImg(){
      let tmpImgData = JSON.parse(this.mediaImgData);
      const mediaImgKeys = [
        'type','id','url','width','height','opacity','layer'
      ];
      mediaImgKeys.forEach(mediaImgKey => {
        this.updateMediaImgObjectItem({key:mediaImgKey, value:tmpImgData[mediaImgKey]});
      });
    },
    initMovie(){
      let tmpMovieData = JSON.parse(this.mediaMovieData);
      const mediaMovieKeys = [
        'videoId','width','height','isLoop','layer'
      ];
      mediaMovieKeys.forEach(mediaMovieKey => {
        this.updateMediaMovieObjectItem({key:mediaMovieKey, value:tmpMovieData[mediaMovieKey]});
      });
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
      const mediaSettingKeys = [
        'id', 'isPublic','name','description','finish_time','mediaBackgroundColor','isShowImg','isShowMovie', 'maxAudioNum'
      ];
      mediaSettingKeys.forEach(mediaSettingKey => {
        this.updateMediaSettingObjectItem({key:mediaSettingKey, value:tmpSettingData[mediaSettingKey]});
      });
    },
    setAudioThumbnail(){
      this.$refs.mediaAudio.updateAudioThumbnail();
    },
    createMovieFrame(){
      let vars = {
        'videoId' : this.getMediaMovie['videoId'],
        'width' : this.getMediaMovie['width'],
        'height' : this.getMediaMovie['height'],
      };
      this.$refs.mediaMovie.createYtPlayer(vars);
    },
    
    // ●Media作成用の処理
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
    addAudio(audio) {
      this.$refs.mediaAudio.addAudio(audio);
    },
    judgeDelAudio(url) {
      this.$refs.mediaAudio.judgeDelAudio(url);
    },
    createMovieFrame(){
      let vars = {
        'videoId' : this.getMediaMovie['videoId'],
        'width' : this.getMediaMovie['width'],
        'height' : this.getMediaMovie['height'],
      };
      this.$refs.mediaMovie.createYtPlayer(vars);
      this.updateMediaSettingObjectItem({key:'isShowMovie', value:true});
    },
    deleteMovieFrame(){
      this.$refs.mediaMovie.deleteYtPlayer();
      this.updateMediaSettingObjectItem({key:'isShowMovie', value:false});

    },
    getFinishTime(){
      if(this.getMediaMovie['videoId'] != ""){
        this.$refs.mediaMovie.setMovieDurationToFinishTime();
      } else {
        this.$refs.mediaAudio.setLongestAudioDurationToFinishTime();
      }
    },
    updateMedia() {
      this.getFinishTime();
      const url = '/media/update';
      let media_datas = {
        'img' : this.getMediaImg,
        'audios' : this.mediaAudios,
        'movie' : this.getMediaMovie,
        'setting' : this.getMediaSetting,
      }
      this.message = "media情報を更新中です...";
      this.isUploadingMedia = true;
      axios.post(url, media_datas)
        .then(response =>{
          alert(response.data.message);
          this.message = "";
          this.isUploadingMedia = false;
        })
        .catch(error => {            
          alert('failed!');
          this.message = "";
          this.isUploadingMedia = false;
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
      this.$refs.mediaAudio.validEditMode();
      window.onYouTubeIframeAPIReady = () => {
        this.getReadyCreateMovieFrame = true;
      }
    });
  },
  watch : {
    getReadyCreateMovieFrame : function(newVal){
      if(this.getMediaSetting['isShowMovie'] == true 
      && this.getMediaMovie['videoId'] != ""
      && newVal == true){
        this.createMovieFrame();
      }
    },
  }


}
</script>

<style scoped>
@import "../../css/mediaCommon.css";
@import "../../css/mediaModals.css";

</style>