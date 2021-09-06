<template>
  <div id="field"
   v-on:click.self="closeModal()"
   :style="{'background-color' : roomSetting['roomBackgroundColor']}">

    <!-- Roomヘッダ -->
    <room-header-component
    :isShowUpdateButton=true
    @update-room="updateRoom">
    </room-header-component>

    <!-- Room画像コンポーネント -->
    <room-img-component
     :roomImgUrl="roomImg['url']"
     :roomImgWidth="roomImg['width'] + 'px'"
     :roomImgHeight="roomImg['height'] + 'px'"
     :roomImgOpacity="roomImg['opacity']"
     :roomImgLayer="roomImg['layer']"
     :isShowRoomImg="roomSetting['isShowImg']"
      v-on:parent-action="showModal"
      ref="roomImg">
    </room-img-component>

    <!-- Roomオーディオコンポーネント -->
    <room-audio-component
     :maxAudioNum="roomSetting['maxAudioNum']"
     :roomAudios="roomAudios"
     ref="roomAudio">
    </room-audio-component>

    <!-- Room動画(=youtube)コンポーネント -->
    <room-movie-component
    v-show="roomSetting['isShowMovie']"
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
        <div id="disp-room-setting-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('roomSettingModal')">
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
    <room-setting-component
    v-show="isShowModal['roomSettingModal']"
    v-on:close-modal="closeModal"
    v-on:delete-room-img="deleteRoomImg"
    :transitionName="transitionName"
    :isPublic="roomSetting['isPublic']"
    :roomName="roomSetting['name']"
    :roomDescription="roomSetting['description']"
    :roomBackgroundColor="roomSetting['roomBackgroundColor']"
    :isShowRoomImg="roomSetting['isShowImg']"
    :roomImgWidth="roomImg['width']"
    :roomImgHeight="roomImg['height']"
    :roomImgOpacity="roomImg['opacity']">
    </room-setting-component>


  </div>
</template>

<script>
import RoomHeader from './RoomHeaderComponent.vue';
import ImgSelect from './ImgSelectComponent.vue';
import AudioSelect from './AudioSelectComponent.vue';
import MovieSetting from './MovieSettingComponent.vue';
import RoomAudio from './RoomAudioComponent.vue';
import RoomSetting from './RoomSettingComponent.vue';
import RoomImg from './RoomImgComponent.vue';
import RoomMovie from './RoomMovieComponent.vue';

export default {
  components : {
    RoomHeader,
    ImgSelect,
    AudioSelect,
    MovieSetting,
    RoomAudio,
    RoomSetting,
    RoomImg,
    RoomMovie,
  },
  props: [
    'roomImgData',
    'roomAudiosData',
    'roomMovieData',
    'roomSettingData',
  ],
  data : () => {
    return {
      getReadyCreateMovieFrame : false,
      autoPlay : false,
      transitionName : 'right-slide',
      isShowModal : {
        'imgModal' : false,
        'audioModal' : false,
        'movieModal' : false,
        'roomSettingModal' : false,
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

      roomSetting : {
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
      let tmpSettingData = JSON.parse(this.roomSettingData);
      this.roomSetting['id'] = tmpSettingData.id;
      this.roomSetting['isPublic'] = tmpSettingData.isPublic;
      this.roomSetting['name'] = tmpSettingData.name;
      this.roomSetting['description'] = tmpSettingData.description;
      this.roomSetting['finish_time'] = tmpSettingData.finish_time;
      this.roomSetting['roomBackgroundColor'] = tmpSettingData.background_color;
      this.roomSetting['isShowImg'] = tmpSettingData.is_show_img;
      this.roomSetting['isShowMovie'] = tmpSettingData.is_show_movie;
      this.roomSetting['maxAudioNum'] = tmpSettingData.max_audio_num;
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
      this.transitionName = 'right-slide';
      for(let key in this.isShowModal){
        this.isShowModal[key] = false;
      }
    },
    setRoomImgUrl(type, id, url) {
      this.roomImg['type'] = type;
      this.roomImg['id'] = id;
      this.roomImg['url'] = url;
      this.roomSetting['isShowImg'] = true;
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
      this.roomSetting['isShowMovie'] = true;
    },
    deleteMovieFrame(){
      this.$refs.roomMovie.deleteYtPlayer();
      this.roomSetting['isShowMovie'] = false;
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
        'setting' : this.roomSetting,
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
      if(this.roomSetting['isShowMovie'] == true 
      && this.roomMovie['videoId'] != ""
      && newVal == true){
        this.createMovieFrame();
      }
    },
  }


}
</script>

<style scoped>
  #field {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 2;
    width: 100%;
    height: 100%;

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }

  #disp-modal-zone {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 3;
    width: 55px;
    height: 100%;
    /* background-color:black; */

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

  }

  #disp-modal-wrapper {
    z-index: 1;
    background-color:black;
    padding: 20px 0;
    border-radius: 30px;

    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    /* background-color: ghostwhite;
    box-shadow: -1px 1px 5px lightgrey; */
  }

  .icon-wrapper {
    padding: 12px;
  }
  .icon-wrapper:hover {
    background-color: rgba(255,255,255,0.2);
  }

  #disp-img-modal-wrapper {
    color:lightseagreen;
  }
  #disp-movie-modal-wrapper {
    color: orangered;
  }
  #disp-audio-modal-wrapper {
    color: gold;
  }
  #disp-room-setting-modal-wrapper {
    color: lightgray;
  }

  .hidden {
    display: none;
  }

  
  /* Modal表示アニメーション */

  /* .right-slide-enter-to, .right-slide-leave {
    transform: translate(0px, 0px);
  } */

  .right-slide-enter-active, .right-slide-leave-active {
    transform: translate(0px, 0px);
    transition: all 500ms
    /* cubic-bezier(0, 0, 0.2, 1) 0ms; */
  }

  .right-slide-enter, .right-slide-leave-to {
    transform: translateX(-100vw) 
  }



</style>