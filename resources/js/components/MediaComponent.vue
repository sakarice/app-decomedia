<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : getMediaSetting['mediaBackgroundColor']}">

    <!-- Mediaヘッダ -->
    <media-header-component
    :isShowLinkToEdit="isMyMedia">
    </media-header-component>

    <!-- Media画像コンポーネント -->
    <media-img-component
      ref="mediaImg">
    </media-img-component>

    <!-- Mediaオーディオコンポーネント -->
    <media-audio-component
     :maxAudioNum="getMediaSetting['maxAudioNum']"
     :mediaAudios="mediaAudios"
     ref="mediaAudio">
    </media-audio-component>

    <!-- Media動画(=youtube)コンポーネント -->
    <media-movie-component
    v-show="getMediaSetting['isShowMovie']"
    ref="mediaMovie">
    </media-movie-component>

        <!-- 選択モーダル表示ボタン -->
    <div id="disp-modal-zone" @click="closeModal">
      <div id="disp-modal-wrapper">
        <!-- いいねアイコン -->
        <div id="disp-media-like-modal-wrapper" class="icon-wrapper" v-if="getIsLogin && !(isMyMedia)">
          <like-media-component></like-media-component>
        </div>

        <!-- Media作成者情報 -->
        <div id="disp-media-owner-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaOwnerInfo')">
          <i class="fas fa-user-circle fa-2x"></i>
          <!-- ユーザプロフィール -->
          <media-owner-info-component
          v-show="isShowModal['mediaOwnerInfo']">
          </media-owner-info-component>
        </div>
        <!-- Media情報 -->
        <div id="disp-media-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaInfoModal')">
          <i class="fas fa-file-alt fa-2x setting-icon"></i>
        </div>
        <!-- 音楽 -->
        <!-- <div id="disp-media-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaAudio')">
          <i class="fas fa-music fa-2x setting-icon"></i>
        </div> -->
      </div>
    </div>

    <media-info-component
    v-show="isShowModal['mediaInfoModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName"
    :name="getMediaSetting['name']"
    :description="getMediaSetting['description']">
    </media-info-component>


  </div>
</template>

<script>
import { mapGetters, mapMutations} from 'vuex';
import MediaHeader from './MediaHeaderComponent.vue';
import MediaAudio from './MediaAudioComponent.vue';
import MediaSetting from './MediaSettingComponent.vue';
import MediaImg from './MediaImgComponent.vue';
import MediaMovie from './MediaMovieComponent.vue';
import MediaInfo from './MediaInfoComponent.vue';
import MediaOwnerInfo from './MediaOwnerInfoComponent.vue';
import LikeMedia from './LikeMediaComponent.vue';


export default {
  components : {
    MediaHeader,
    MediaAudio,
    MediaSetting,
    MediaImg,
    MediaMovie,
    MediaInfo,
    MediaOwnerInfo,
    LikeMedia,
  },
  props: [
    'mediaImgData',
    'mediaAudiosData',
    'mediaMovieData',
    'mediaSettingData',
  ],
  data : () => {
    return {
      isMyMedia : false,
      getReadyCreateMovieFrame : false,
      getReadyPlayAudio : false,
      getReadyPlayMovie : false,
      autoPlay : true,

      transitionName : 'slide-in',
      isShowModal : {
        'mediaOwnerInfo' : false,
        'mediaInfoModal' : false,
      },
      mediaAudios : [],

    }
  },
  computed : {
    ...mapGetters('loginState', ['getIsLogin']),
    ...mapGetters('mediaImg', ['getMediaImg']),
    ...mapGetters('mediaMovie', ['getMediaMovie']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
  },
  methods : {
    ...mapMutations('mediaImg', ['updateMediaImgObjectItem']),
    ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
    ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
    judgeIsMyMedia(){
      let media_id = JSON.parse(this.mediaSettingData).id;
      let url = '/ajax/judgeIsMyMedia/' + media_id;
      console.log(url);
      axios.get(url)
        .then(response =>{
          this.isMyMedia = response.data.isMyMedia;
        })
        .catch(error => {
          console.log('あなたがmedia作成者か判別できませんでした');
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
    },    setAudioThumbnail(){
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

  },
  created() {
    if(this.getIsLogin){ this.judgeIsMyMedia(); }
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
      if(this.getMediaSetting['isShowMovie'] == true 
      && this.getMediaMovie['videoId'] != ""
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

@import "../../css/mediaCommon.css";
@import "../../css/mediaModals.css";
@import "../../css/modalAnimation.css";



  #disp-media-owner-modal-wrapper {
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