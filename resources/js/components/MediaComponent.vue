<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : getMediaSetting['mediaBackgroundColor']}">

    <router-view name="switchToEditMode"></router-view>
    <router-view name="switchToShowMode"></router-view>

    <!-- Media画像コンポーネント -->
    <media-img
    ref="mediaImg">
    </media-img>

    <!-- Mediaオーディオコンポーネント -->
    <media-audio
     :maxAudioNum="getMediaSetting['maxAudioNum']"
     ref="mediaAudio">
    </media-audio>

    <!-- Media動画(=youtube)コンポーネント -->
    <media-movie
    v-show="getMediaSetting['isShowMovie']"
    ref="mediaMovie">
    </media-movie>

        <!-- 選択モーダル表示ボタン -->
    <div id="disp-modal-zone" @click="closeModal">
      <div id="disp-modal-wrapper">
        <!-- いいねアイコン -->
        <router-view name="dispMediaLike">
        </router-view>
        <!-- Media作成者情報 -->
        <router-view name="dispMediaOwnerInfo"
        v-on:show-modal="showModal">
        </router-view>
        <!-- ユーザプロフィール -->
        <router-view name="mediaOwnerInfo"
          v-show="isShowModal['mediaOwnerInfo']">
        </router-view>
        <!-- Media情報 -->
        <router-view name="dispMediaInfo"
        v-on:show-modal="showModal">        
        </router-view>
        <!-- 画像 -->
        <router-view name="dispImgModal"
        v-on:show-modal="showModal">        
        </router-view>
        <!-- オーディオ -->
        <router-view name="dispAudioModal"
        v-on:show-modal="showModal">        
        </router-view>
        <!-- 動画 -->
        <router-view name="dispMovieModal"
        v-on:show-modal="showModal">        
        </router-view>
        <!-- Media設定 -->
        <router-view name="dispMediaSettingModal"
        v-on:show-modal="showModal">        
        </router-view>

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

    <div v-show="getIsWaiting">
      <router-view name="overlay"></router-view>
      <router-view name="loading"
      :message="waitingMsg">
      </router-view>
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

export default {
  components : {},
  props: [],
  data : () => {
    return {
      isMyMedia : false,
      getReadyCreateMovieFrame : false,
      initStatus : 0,
      // getReadyPlayAudio : false,
      transitionName : 'slide-in',
      isShowModal : {
        'mediaOwnerInfo' : false,
        'mediaInfoModal' : false,
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'mediaSettingModal' : false,
      },
      autoPlay : true,

    }
  },
  computed : {
    ...mapGetters('loginState', ['getIsLogin']),
    ...mapGetters('media', ['getMediaId']),
    ...mapGetters('media', ['getMode']),
    ...mapGetters('media', ['getIsWaiting']),
    ...mapGetters('mediaImg', ['getMediaImg']),
    ...mapGetters('mediaAudios', ['getMediaAudios']),
    ...mapGetters('mediaMovie', ['getMediaMovie']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),

    waitingMsg:function(){
      if(this.getMode==1){
        return '作成中です...'
      } else if(this.getMode==2){
        return '更新中です...'
      }
    },
    
  },
  methods : {
    ...mapMutations('media', ['setMediaId']),
    ...mapMutations('media', ['setIsMyMedia']),
    ...mapMutations('media', ['setMode']),
    ...mapMutations('mediaImg', ['updateMediaImgObjectItem']),
    ...mapMutations('mediaAudios', ['addMediaAudiosObjectItem']),
    ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
    ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
    judgeIsMyMedia(){
      let url = '/ajax/judgeIsMyMedia/' + this.getMediaId;
      axios.get(url)
        .then(response =>{
          this.setIsMyMedia(response.data.isMyMedia);
        })
        .catch(error => {
          console.log('あなたがmedia作成者か判別できませんでした');
        })
    },
    checkMode(){
      const path = this.$route.path;
      if(path.match(/create/)){
          this.setMode(1); // create
      } else if(path.match(/edit/)){
          this.setMode(2); // edit
      } else {
          this.setMode(3); // show
      }
    },
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

  },
  created() {
    console.log('パス'+this.$route.path);
    console.log('フルパス'+this.$route.fullPath);
    // if(this.getIsLogin){ this.judgeIsMyMedia(); }
  },
  mounted() {
    this.setMediaIdToStore(this.extractMediaIdFromUrl());
    this.judgeIsMyMedia();
    this.checkMode()
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
      // 全オーディオの再生開始
    });

  },
  watch : {
    initStatus : function(newVal){
      // オーディオ情報の読み込みが完了したらオーディオ再生開始
      if(newVal >= 2){ 
        const play = ()=>{this.$refs.mediaAudio.playAllAudio();}
        setTimeout(play, 10000); 
      }
      // youtube情報取得と再生準備が完了したら再生開始
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
/* @import "../../css/button.css"; */
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