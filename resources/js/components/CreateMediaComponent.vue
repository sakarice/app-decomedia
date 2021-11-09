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
    :movieFrameWidth="roomMovie['width']"
    :movieFrameHeight="roomMovie['height']"
    :isLoopYoutube="roomMovie['isLoop']">
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
import RoomAudio from './RoomAudioComponent.vue';
import MediaSetting from './MediaSettingComponent.vue';
import MediaImg from './MediaImgComponent.vue';
import RoomMovie from './RoomMovieComponent.vue';
import RoomCreateButton from './RoomCreateButtonComponent.vue';

export default {
  components : {
    RoomHeader,
    ImgSelect,
    AudioSelect,
    MovieSetting,
    RoomAudio,
    MediaSetting,
    MediaImg,
    RoomMovie,
    RoomCreateButton,
  },
  props: [
    'mediaImgData',
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
      roomMovie : {
        'videoId' : "",
        'width' : "500",
        'height' : "420",
        'isLoop' : false,
        'layer' : 1,
      },
      // maxAudioNum : 5,
      roomAudios : [],


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
    createRoom() {
      this.getFinishTime();
      const url = '/room/store';
      const tmpThis = this;
      let room_datas = {
        'img' : this.mediaImg,
        'audios' : this.roomAudios,
        'movie' : this.roomMovie,
        'setting' : this.mediaSetting,
      }
      this.message = "room情報を保存中です...";
      axios.post(url, room_datas)
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
    this.$nextTick(function(){ // roomAudioを編集モードに設定
      this.$refs.roomAudio.validEditMode();
    });
  },

}
</script>

<style scoped>
@import "../../css/roomCommon.css";
@import "../../css/roomModals.css";

</style>