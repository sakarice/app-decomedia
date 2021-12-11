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
    <router-view name="imgSelect" 
    v-show="isShowModal['imgModal']" 
    v-on:close-modal="closeModal" 
    :transitionName="transitionName">
    </router-view>

    <!-- オーディオ選択コンポーネント -->
    <router-view name="audioSelect" 
    v-show="isShowModal['audioModal']" 
    v-on:close-modal="closeModal"
    :transitionName="transitionName">
    </router-view>

    <!-- 動画設定コンポーネント -->
    <router-view name="movieSetting"
    v-show="isShowModal['movieModal']"
    v-on:close-modal="closeModal"
    v-on:create-movie-frame="createMovieFrame"
    v-on:delete-movie-frame="deleteMovieFrame"
    :transitionName="transitionName">
    </router-view>

    <!-- Media設定コンポーネント -->
    <router-view name="mediaSetting"
    v-show="isShowModal['mediaSettingModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName">
    </router-view>

    <div v-show="isUploadingMedia">
      <router-view name="overlay"></router-view>
      <router-view name="loading"
      :message="'メディアを更新中です...'">
      </router-view>
    </div>

  </div>
</template>

<script>
import { mapGetters, mapMutations} from 'vuex';
import MediaHeader from './MediaHeaderComponent.vue';
import ImgSelect from './edit_parts/ImgSelectComponent.vue';
import AudioSelect from './edit_parts/AudioSelectComponent.vue';
import MovieSetting from './edit_parts/MovieSettingComponent.vue';
import MediaAudio from './media_contents/MediaAudioComponent.vue';
import MediaSetting from './edit_parts/MediaSettingComponent.vue';
import MediaMovie from './media_contents/MediaMovieComponent.vue';
import Overlay from '../common/OverlayComponent.vue'
import Loading from '../common/LoadingComponent.vue'

export default {
  components : {
    MediaHeader,
    ImgSelect,
    AudioSelect,
    MovieSetting,
    MediaAudio,
    MediaSetting,
    MediaMovie,
    Overlay,
    Loading,
  },
  props: [],
  data : () => {
    return {
      getReadyCreateMovieFrame : false,
      initStatus : 0,
      // ↑initStatusについて
      // 下記の加算で状態を表す(合計でmax31)
      // 1:画像,2:音楽,4:動画,8:設定,16:youtubePlayer
      // 例えば,28以上なら動画(4)、設定(8)、youtube(16)の初期化が完了していると見なせる

      transitionName : 'slide-in',
      isUploadingMedia : false,
      isShowModal : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'mediaSettingModal' : false,
      },
      autoplay : false,

    }
  },
  computed : {
    ...mapGetters('media', ['getMediaId']),
    ...mapGetters('mediaImg', ['getMediaImg']),
    ...mapGetters('mediaAudios', ['getMediaAudios']),
    ...mapGetters('mediaMovie', ['getMediaMovie']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
  },
  methods : {
    ...mapMutations('media', ['setMediaId']),
    ...mapMutations('mediaImg', ['updateMediaImgObjectItem']),
    ...mapMutations('mediaAudios', ['addMediaAudiosObjectItem']),
    ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
    ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
    // ●Media読み込み時の初期化処理
    extractMediaIdFromUrl(){
      const path = location.pathname;
      const mediaId = path.match(/\d+/)[0]; // 連続した数字を抜き出す
      return mediaId;
    },
    setMediaIdToStore(mediaId){this.setMediaId(mediaId)},
    getMediaDataFromDB(dataName){
      return new Promise((resolve, reject) => {
        const url = '/'+dataName+'/'+this.getMediaId;
        axios.get(url)
        .then(response=>{
          return resolve(response.data);
        })
        .catch(error=>{});
      })
    },
    initImg(){
      this.getMediaDataFromDB('mediaImg')
      .then(datas=>{
        for(let key in datas){
          this.updateMediaImgObjectItem({key:key, value:datas[key]});
        }
        this.initStatus += 1;
      });
    },
    initAudio(){
      this.getMediaDataFromDB('mediaAudios')
      .then(mediaAudioDatas=>{
        mediaAudioDatas.forEach(mediaAudioData=>{
          this.addMediaAudiosObjectItem(mediaAudioData);
        })
        this.initStatus += 2;
      });
    },
    initMovie(){
      this.getMediaDataFromDB('mediaMovie')
      .then(datas=>{
        for(let key in datas){
          this.updateMediaMovieObjectItem({key:key, value:datas[key]});
        }
        this.initStatus += 4;
      });
    },
    initSetting(){
      this.getMediaDataFromDB('mediaSetting')
      .then(datas=>{
        for(let key in datas){
          this.updateMediaSettingObjectItem({key:key, value:datas[key]});
        };
        this.initStatus += 8;
      });
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
    createMovieFrame(){
      this.$refs.mediaMovie.createYtPlayer();
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
      // this.getFinishTime();
      const url = '/media/update';
      let media_datas = {
        'img' : this.getMediaImg,
        'audios' : this.getMediaAudios,
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
    this.setMediaIdToStore(this.extractMediaIdFromUrl());
    this.initImg();
    this.initMovie();
    this.initAudio();
    this.initSetting();

    // 全ての子コンポーネントが描画されてから実行する処理
    this.$nextTick(function(){
      this.$refs.mediaAudio.validEditMode();
      window.onYouTubeIframeAPIReady = () => {
        this.getReadyCreateMovieFrame = true;
        this.initStatus += 16;
      }
    });
  },
  watch : {
    // getReadyCreateMovieFrame : function(newVal){
    initStatus : function(newVal){
      if(this.getMediaSetting['isShowMovie'] == true
      && this.getMediaMovie['videoId'] != ""
      && newVal >= 28){
        this.createMovieFrame();
      }
    },
  },


}
</script>

<style scoped>
@import "/resources/css/mediaCommon.css";
@import "/resources/css/mediaModals.css";

</style>