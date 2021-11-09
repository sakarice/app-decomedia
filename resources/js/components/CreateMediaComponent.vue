<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : mediaSetting['roomBackgroundColor']}">

    <!-- Roomヘッダ -->
    <room-header-component
    :isShowCreateButton=true
    @create-room="createRoom">
    </room-header-component>

    <!-- Room画像コンポーネント -->
    <media-img-component
     :mediaImgUrl="mediaImg['url']"
     :mediaImgWidth="mediaImg['width'] + 'px'"
     :mediaImgHeight="mediaImg['height'] + 'px'"
     :mediaImgLayer="mediaImg['layer']"
     :mediaImgOpacity="mediaImg['opacity']"
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
import RoomCreateButton from './RoomCreateButtonComponent.vue';

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
    RoomCreateButton,
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
      mediaSetting : {
        'isPublic' : true,  // 公開/非公開 デフォルトは公開
        'name' : "",
        'description' : "",
        'finish_time' : 0,
        'roomBackgroundColor' : "#F7F7F7", // ほぼ白
        'isShowImg' : true,
        'isShowMovie' : false,
        'maxAudioNum' : 5,
      },
      mediaImg : {
        'type' : 0,
        'id' : 0,
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
      // maxAudioNum : 5,
      mediaAudios : [],


    }
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
    setMediaImgUrl(type, id, url) {
      this.mediaImg['type'] = type;
      this.mediaImg['id'] = id;
      this.mediaImg['url'] = url;
      this.mediaSetting['isShowImg'] = true;
    },
    judgeDelImg(url) {
      if(this.mediaImg['url'] == url){
        this.mediaImg['type'] = 0;
        this.mediaImg['id'] = 0;
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
    createRoom() {
      this.getFinishTime();
      const url = '/room/store';
      const tmpThis = this;
      let media_datas = {
        'img' : this.mediaImg,
        'audios' : this.mediaAudios,
        'movie' : this.mediaMovie,
        'setting' : this.mediaSetting,
      }
      this.message = "room情報を保存中です...";
      axios.post(url, media_datas)
        .then(response =>{
          alert(response.data.message);
          this.message = "";
        })
        .catch(error => {            
          alert('failed!');
          this.message = "";
        })

    }

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
@import "../../css/roomCommon.css";
@import "../../css/roomModals.css";

</style>