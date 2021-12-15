<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : getMediaSetting['mediaBackgroundColor']}">

    <!-- Mediaヘッダ -->
    <media-header-component
    :isShowCreateButton=true>
    </media-header-component>

    <!-- Media画像コンポーネント -->
    <!-- <media-img-component
     :isShowMediaImg="getMediaSetting['isShowImg']"
      v-on:parent-action="showModal"
      ref="mediaImg">
    </media-img-component> -->

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

    <div v-show="isCreatingMedia">
      <overlay-component></overlay-component>
      <loading-component
      :message="'メディアを保存中です...'">
      </loading-component>
    </div>

  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import MediaHeader from './MediaHeaderComponent.vue';
import ImgSelect from './edit_parts/ImgSelectComponent.vue';
import AudioSelect from './edit_parts/AudioSelectComponent.vue';
import MovieSetting from './edit_parts/MovieSettingComponent.vue';
import MediaAudio from './media_contents/MediaAudioComponent.vue';
import MediaSetting from './edit_parts/MediaSettingComponent.vue';
import MediaMovie from './media_contents/objects/movie/MediaMovieComponent.vue';
import MediaCreateButton from './action_parts/MediaCreateButtonComponent.vue';
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
    // MediaImg,
    MediaMovie,
    MediaCreateButton,
    Overlay,
    Loading,
  },
  props: [],
  data : () => {
    return {
      getReadyCreateMovieFrame : false,
      transitionName : 'slide-in',
      isCreatingMedia : false,
      isShowModal : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'mediaSettingModal' : false,
      },

    }
  },
  computed : {
    ...mapGetters('mediaAudios', ['getMediaAudios']),
    ...mapGetters('mediaMovie', ['getMediaMovie']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
  },
  methods : {
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
      this.getMediaSetting['isShowMovie'] = true;
    },
    deleteMovieFrame(){
      this.$refs.mediaMovie.deleteYtPlayer();
      this.getMediaSetting['isShowMovie'] = false;
    },
    getFinishTime(){
      if(this.getMediaMovie['videoId'] != ""){
        this.$refs.mediaMovie.setMovieDurationToFinishTime();
      } 
    },

  },
  created() {},
  mounted() {
    this.$nextTick(function(){ // mediaAudioを編集モードに設定
      this.$refs.mediaAudio.validEditMode();
    });
  },

}
</script>

<style scoped>
@import "/resources/css/mediaCommon.css";
@import "/resources/css/mediaModals.css";

</style>