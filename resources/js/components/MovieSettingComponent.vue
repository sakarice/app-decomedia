<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="area-wrapper">

        <div id="setting-wrapper">
          <p id="player-setting-title">動画Player設定</p>
          <div class="yt-form-wrapper setting-content">
            <input :value="youtubeUrl" @input="updateVideoId" type="text" id="youtube-url-input" size=30 placeholder="youtube movie URL">
          </div>
          <div class="yt-setting-wrapper">
            <div class="setting-content">
              <input :value="movieFrameWidth" @input="updateVideoWidth" type="text" id="set-movie-frame-width" size=5 placeholder="横幅">
              <span>[px] 横幅</span><span class="message-label"> (ブラウザの横幅：{{window_width}})</span><br>
            </div>
            <div class="setting-content">
              <input :value="movieFrameHeight" @input="updateVideoHeight" type="text" id="set-movie-frame-height" size=5 placeholder="縦幅">
              <span>[px] 縦幅</span><span class="message-label"> (ブラウザの縦幅：{{window_height}})</span>
            </div>
            <button  class="setting-content" type="submit" @click="createMovieFrame">再生プレイヤー作成</button>
            <button  class="setting-content" type="submit" @click="deleteMovieFrame">削除</button>

          </div>
          <div class="setting-loop setting-content" v-on:click="loopYoutube" :class="{'isLoop' : isLoopYoutube}">
            <i class="room-yt-loop-icon fas fa-undo-alt fa-2x"></i>
            <span style="margin-left:10px">ループ</span>
          </div>
        </div>

      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props : [
    'transitionName',
    'movieFrameWidth',
    'movieFrameHeight',
    'isLoopYoutube'
    ],
  data : () => {
    return {
      youtubeUrl : '',
      window_width : 0,
      window_height : 0,
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
    extractVideoIdFromUrl(url) {
      let pattern = /v=.*/;
      let matchText = url.match(pattern); // object型で返ってくる
      matchText = matchText.toString(); // object⇒stringへ変換
      let videoId = matchText.substring(2, 13);  // videoID部分を切りだし
      return videoId;
    },
    updateVideoId(event){
      let youtubeUrl = event.target.value;
      this.$parent.roomMovie['videoId'] = this.extractVideoIdFromUrl(youtubeUrl);
    },
    updateVideoWidth(event){
      this.$parent.roomMovie['width'] = event.target.value;
    },
    updateVideoHeight(event){
      this.$parent.roomMovie['height'] = event.target.value;
    },
    createMovieFrame() {
      // 親コンポーネントの動画フレーム作成メソッドを実行
      this.$emit('create-movie-frame');
    },
    deleteMovieFrame(){
      this.$emit('delete-movie-frame');
    },
    loopYoutube(){
      if(this.isLoopYoutube == false){
        this.$parent.roomMovie['isLoop'] = true;
      } else {
        this.$parent.roomMovie['isLoop'] = false;
      }
    },
  },
  mounted : function() {
    this.window_width = window.innerWidth;
    this.window_height = window.innerHeight;
  },

}
</script>


<style scoped>

@import "../../css/roomEditModals.css";

  /* コンテンツのCSS */
  #setting-wrapper {
    margin: 20px 0;

    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  #player-setting-title {
    font-weight: bold;
  }

  .yt-setting-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  .setting-content {
    margin-bottom: 7px;
  }

  .setting-loop{
    display: flex;
    align-items: center;
  }
  .setting-loop:hover {
    cursor: pointer;
  }

  .isLoop {
    color: blue;
  }



</style>