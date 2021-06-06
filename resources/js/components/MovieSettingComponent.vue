<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="area-wrapper">

        <div id="setting-wrapper">
          <p id="player-setting-title">動画Player設定</p>
          <div class="yt-form-wrapper">
            <input v-model="youtubeUrl" type="text" id="youtube-url-input" size=30 placeholder="youtube movie URL">
          </div>
          <div class="yt-setting-wrapper">
            <div>
              <input v-model="movieFrameWidth" type="text" id="set-movie-frame-width" size=5 placeholder="横幅">
              <span>[px] 横幅</span><span class="message-label"> (ブラウザの横幅：{{window_width}})</span><br>
            </div>
            <div>
              <input v-model="movieFrameHeight" type="text" id="set-movie-frame-height" size=5 placeholder="縦幅">
              <span>[px] 縦幅</span><span class="message-label"> (ブラウザの縦幅：{{window_height}})</span>
            </div>
            <button type="submit" @click="setParentYoutubePlayerVars">再生プレイヤー作成</button>
            <button type="submit" @click="deleteMovieFrame">削除</button>

          </div>
          <i class="room-yt-loop-icon fas fa-undo-alt fa-2x" v-on:click="loopYoutube" :class="{'isLoop' : isLoopYoutube}"></i>
        </div>

      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props : ['transitionName','isLoopYoutube'],
  data : () => {
    return {
      youtubeUrl : '',
      movieFrameWidth : 500,
      movieFrameHeight : 320,
      window_width : 0,
      window_height : 0,
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
    extractVideoIdFromUrl() {
      let url = this.youtubeUrl;
      let pattern = /v=.*/;
      let matchText = url.match(pattern); // object型で返ってくる
      matchText = matchText.toString(); // object⇒stringへ変換
      let videoId = matchText.substring(2, 13);  // videoID部分を切りだし
      return videoId;
    },
    setParentYoutubePlayerVars() {
      this.$parent.moviePlayerSettings['videoId'] = this.extractVideoIdFromUrl();
      this.$parent.moviePlayerSettings['width'] = this.movieFrameWidth;
      this.$parent.moviePlayerSettings['height'] = this.movieFrameHeight;

      // 親コンポーネントの動画フレーム作成メソッドを実行
      this.$emit('create-movie-frame');

    },
    deleteMovieFrame(){
      this.$emit('delete-movie-frame');
    },
    loopYoutube(){
      if(this.isLoopYoutube == false){
        this.$parent.moviePlayerSettings['isLoop'] = true;
      } else {
        this.$parent.moviePlayerSettings['isLoop'] = false;
      }
    },
  },
  mounted : function() {
    this.window_width = window.innerWidth;
    this.window_height = window.innerHeight;
  },

}
</script>


<style>

  /* コンテンツのCSS */
  #setting-wrapper {
    margin: 20px 0;

    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  .yt-setting-wrapper {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }

  /* #player-setting-title, */
  #youtube-url-input,
  #set-movie-frame-width,
  #set-movie-frame-height {
    margin-bottom: 7px;
  }

  /* アニメーション */

  /* .right-slide-enter-to, .right-slide-leave {
    transform: translate(0px, 0px);
  } */

  .right-slide-enter-active, .right-slide-leave-active {
    transform: translate(0px, 0px);
    transition: all 500ms
    /* cubic-bezier(0, 0, 0.2, 1) 0ms; */
  }

  .right-slide-enter, .right-slide-leave-to {
    transform: translateX(100vw) 
  }

</style>