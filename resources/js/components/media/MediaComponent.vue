<template>
  <div id="field"
   v-on:click.self="closeModal()"
  :style="{'background-color' : getMediaSetting['mediaBackgroundColor']}">

    <judge-device-type></judge-device-type>

    <router-view name="tutorialFlow"></router-view>

    <!-- <router-view name="switchToEditMode"></router-view>
    <router-view name="switchToShowMode"></router-view> -->

        <media-contents-field @click="closeModal"
        ref="mediaContentsField">

          <router-view name="domResize"></router-view>
          <router-view name="objectRotate"></router-view>

          <media-img-mng
          ref="mediaImgMng">
          </media-img-mng>

          <!-- Media図形コンポーネント -->
          <media-figure-mng
          ref="mediaFigure">
          </media-figure-mng>

          <!-- Mediaテキストコンポーネント -->
          <media-text-mng
          ref="mediaTextMng">
          </media-text-mng>

          <!-- Media動画(=youtube)コンポーネント -->
          <media-movie
          v-show="getMediaSetting['isShowMovie']"
          ref="mediaMovie">
          </media-movie>
        </media-contents-field>

        <!-- Mediaオーディオコンポーネント -->
        <media-audio
        :maxAudioNum="getMediaSetting['maxAudioNum']"
        ref="mediaAudio">
        </media-audio>

        <!-- 立体音響の定位設定 -->
        <router-view name="mediaAudioMng"></router-view>



    <div id="disp-modal-zone" @click="closeModal">
      <div id="disp-modal-wrapper">
        <!-- いいねアイコン -->
        <router-view name="dispMediaLike">
        </router-view>
        <!-- Media情報 -->
        <router-view name="dispMediaInfo"
        v-on:show-modal="showModal">        
        </router-view>
        <!-- Mediaコメント -->
        <router-view name="dispMediaComment"
        v-on:show-modal="showModal">
        </router-view>
        <!-- オーディオ -->
        <router-view name="dispAudioModal"
        v-on:show-modal="showModal">
        </router-view>
        <!-- 画像 -->
        <router-view name="dispImgModal"
        v-on:show-modal="showModal">        
        </router-view>
        <!-- 図形設定 -->
        <router-view name="dispFigureSettingModal"
        v-on:show-modal="showModal">
        </router-view>
        <!-- テキスト -->
        <router-view  name="dispTextSettingModal"
        v-on:show-modal="showModal">
        </router-view>
        <!-- 動画 -->
        <router-view name="dispMovieModal"
        v-on:show-modal="showModal">
        </router-view>
        <!-- Mediaコンテンツフィールド設定 -->
        <router-view name="dispContentsFieldSettingModal"
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

    <!-- メディア図形作成コンポーネント -->
    <router-view name="mediaFigureFactory"
    v-show="isShowModal['figureSettingModal']"
    v-on:close-modal="closeModal">
    </router-view>

    <!-- メディアテキスト作成コンポーネント -->
    <router-view name="mediaTextFactory"
    v-show="isShowModal['textSettingModal']"
    v-on:close-modal="closeModal">
    </router-view>

    <!-- メディアコメントコンポーネント -->
    <router-view name="mediaCommentMng"
    v-show="isShowModal['mediaComment']"
    v-on:close-modal="closeModal">
    </router-view>

    <router-view name="imgProperty"></router-view>
    <router-view name="textProperty"></router-view>
    <router-view name="figureUpdate"></router-view>
    <router-view name="objectSelectMng"></router-view>

    <!-- 動画設定コンポーネント -->
    <router-view name="movieSetting"
    v-show="isShowModal['movieModal']"
    v-on:close-modal="closeModal"
    v-on:create-movie-frame="createMovieFrame"
    v-on:delete-movie-frame="deleteMovieFrame"
    :transitionName="transitionName">
    </router-view>

    <!-- コンテンツフィールド設定コンポーネント -->
    <router-view name="contentsFieldSetting"
    v-show="isShowModal['contentsFieldSettingModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName">
    </router-view>

    <!-- Media設定コンポーネント -->
    <router-view name="mediaSetting"
    v-show="isShowModal['mediaSettingModal']"
    v-on:close-modal="closeModal"
    :transitionName="transitionName">
    </router-view>

    <disp-audios></disp-audios>


    <!-- <router-view name="overlay"></router-view> -->
    <overlay></overlay>

    <div v-show="getIsCrudDoing">
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

    <media-object-controll-parts-wrapper>
      <object-delete></object-delete>
      <object-setting-open></object-setting-open>
      <object-copy></object-copy>
    </media-object-controll-parts-wrapper>


  </div>
</template>

<script>
  import { mapGetters, mapMutations} from 'vuex';
  // import MediaImg from './media_contents/img/MediaImgComponent.vue';
  import MediaContentsField from './media_contents/MediaContentsField.vue';
  import judgeDeviceType from '../common/JudgeDeviceType.vue';
  import MediaImgMng from './media_contents/objects/img/MediaImgMngComponent.vue';
  import MediaAudio from './media_contents/audio/MediaAudioComponent.vue';
  import MediaMovie from './media_contents/objects/movie/MediaMovieComponent.vue';
  import MediaSetting from './edit_parts/MediaSettingComponent.vue';
  import MediaFigureMng from './media_contents/objects/figure/MediaFigureMngComponent.vue';
  import MediaTextMng from './media_contents/objects/text/MediaTextMngComponent.vue';
  import DispAudios from '../media/change_display_parts/DispAudiosComponent.vue'
  import MediaObjectControllPartsWrapper from './wrapper_parts/MediaObjectControllPartsWrapper.vue'
  import ObjectDelete from '../media/media_contents/objects/object_edit_parts/ObjectDeleteComponent.vue'
  import ObjectSettingOpen from '../../components/media/change_display_parts/ObjectSettingOpenComponent.vue'
  import ObjectCopy from '../../components/media/media_contents/objects/object_edit_parts/ObjectCopyComponent.vue';
  import overlay from '../../components/common/OverlayComponent.vue';


export default {
  components : {
    judgeDeviceType,
    MediaContentsField,
    MediaImgMng,
    MediaAudio,
    MediaSetting,
    MediaMovie,
    MediaFigureMng,
    MediaTextMng,
    DispAudios,
    MediaObjectControllPartsWrapper,
    ObjectDelete,
    ObjectSettingOpen,
    ObjectCopy,
    overlay,
  },
  props: [],
  data : () => {
    return {
      // デバッグ用後で消す
      center_x : 0,
      center_y : 0,

      isMyMedia : false,
      getReadyCreateMovieFrame : false,
      transitionName : 'slide-in',
      isShowModal : {
        'mediaOwnerInfo' : false,
        'mediaInfoModal' : false,
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'textSettingModal' : false,
        'figureSettingModal' : false,
        'contentsFieldSettingModal': false,
        'mediaSettingModal' : false,
        'mediaComment' : false,
      },
      autoPlay : true,

    }
  },
  computed : {
    ...mapGetters('loginState', ['getIsLogin']),
    ...mapGetters('media', ['getMediaId']),
    ...mapGetters('media', ['getMode']),
    ...mapGetters('media', ['getIsCrudDoing']),
    ...mapGetters('mediaFigures', ['getIsInitializedFigures']),
    ...mapGetters('mediaAudios', ['getMediaAudios']),
    ...mapGetters('mediaAudios', ['getIsInitializedAudios']),
    ...mapGetters('mediaMovie', ['getMediaMovie']),
    ...mapGetters('mediaMovie', ['getIsInitializedMovie']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
    ...mapGetters('mediaSetting', ['getIsInitializedSetting']),
    ...mapGetters('mediaContentsField',['getMediaContentsField']),
    ...mapGetters('mediaFigures', ['getMediaFigures']),


    waitingMsg:function(){
      if(this.getMode==1){
        return '作成中です...'
      } else if(this.getMode==2){
        return '更新中です...'
      }
    },
    initStatus : function(){
      let initCountStack = 0;
      if(this.getIsInitializedImg){initCountStack += 1}
      if(this.getIsInitializedFigures){initCountStack += 2}
      if(this.getIsInitializedAudios){initCountStack += 4}
      if(this.getIsInitializedMovie){initCountStack += 8}
      if(this.getIsInitializedSetting){initCountStack += 16}
      if(this.getReadyCreateMovieFrame){initCountStack += 32}
      return initCountStack;
    },
    
  },
  methods : {
    ...mapMutations('media', ['setMediaId']),
    ...mapMutations('media', ['setIsMyMedia']),
    ...mapMutations('media', ['setMode']),
    ...mapMutations('mediaAudios', ['addMediaAudiosObjectItem']),
    ...mapMutations('mediaAudios', ['updateIsInitializedAudios']),
    ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
    ...mapMutations('mediaSetting', ['updateIsInitializedSetting']),
    ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
    judgeIsMyMedia(){
      let url = '/ajax/judgeIsMyMedia/' + this.getMediaId;
      axios.get(url)
        .then(response =>{
          this.setIsMyMedia(response.data.isMyMedia);
          const event = new CustomEvent('setIsMyMedia');
          document.dispatchEvent(event);
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
    initSetting(){
      this.getMediaDataFromDB('mediaSetting')
      .then(datas=>{
        for(let key in datas){
          this.updateMediaSettingObjectItem({key:key, value:datas[key]});
        };
        this.updateIsInitializedSetting(true);
        const event = new CustomEvent('initMediaSettingFinish');
        document.body.dispatchEvent(event);
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
    fieldClicked(){
      const fieldClicked = new CustomEvent('fieldClicked');
      document.body.dispatchEvent(fieldClicked);
      this.closeModal();
    },

  },
  created() {
    document.body.addEventListener('closeModal', (e)=> {
      this.closeModal();
    });

  },
  mounted() {
    this.checkMode();
    if(this.getMode!=1){ // 1:create以外(=showかedit)なら初期化処理を実行
      this.setMediaIdToStore(this.extractMediaIdFromUrl());
      this.judgeIsMyMedia();
      // this.$refs.mediaImg.initImg();
      this.$refs.mediaContentsField.initContentsField();
      this.$refs.mediaImgMng.initImg();
      this.$refs.mediaTextMng.initText();
      this.$refs.mediaFigure.initFigure();
      this.$refs.mediaMovie.initMovie();
      this.$refs.mediaAudio.initAudio();
      this.initSetting();
    }
    if(this.getMode!=3){ // 3:show以外(=createかeditなら)編集モードに設定
      // this.$refs.mediaAudio.validEditMode();
    }

    // 全ての子コンポーネントが描画されてから実行する処理
    this.$nextTick(function(){
      window.onYouTubeIframeAPIReady = () => {
        this.getReadyCreateMovieFrame = true;
      }
    });

    // 指定の領域がクリックされたら、選択中オブジェクトの選択を解除する
    const field = document.getElementById('field');
    field.addEventListener('click',this.fieldClicked, false);
    field.addEventListener('touchstart',this.fieldClicked, false);
  },
  watch : {
    $route:function(){this.checkMode();},
    initStatus : function(newVal){
      console.log('initStatus:'+newVal)
      // オーディオ情報の読み込みが完了したらオーディオ再生開始
      if(newVal >= 4){ 
        const event = new CustomEvent('finishInitAudioData');
        const finishInitAudioData = ()=>{document.body.dispatchEvent(event);}
        setTimeout(finishInitAudioData, 10000);
      }

      if(this.getMode==3 && newVal >= 62){ // =showモード
          this.$refs.mediaContentsField.scaleFieldSizeToWindow();
        }
      // youtube情報取得と再生準備が完了したら再生開始
      if(this.getMediaSetting['isShowMovie'] == true
      && this.getMediaMovie['videoId'] != ""
      && newVal >= 56){ // (56 = 32 + 16 + 8)
        this.createMovieFrame();
      }
    },
  },


}
</script>

<style scoped>
/* @import "../../css/button.css"; */
@import "/resources/css/mediaCommon.css";
@import "/resources/css/mediaModals.css";
@import "/resources/css/modalAnimation.css";
@import "/resources/css/dispIconCommon.css";

  .setting-icon {
    color : lightgrey;
  }

</style>