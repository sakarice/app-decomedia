<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">

        <div id="setting-wrapper">
          <p id="player-setting-title">動画Player設定</p>
          <div class="yt-form-wrapper setting-content">
            <input :value="youtubeUrl" @input="updateVideoId" type="text" id="youtube-url-input" size=30 placeholder="youtube movie URL">
          </div>
          <div class="yt-setting-wrapper">
            <div class="setting-content">
              <input :value="getMediaMovie['width']" @input="updateMediaMovieObjectItem({key:'width',value:$event.target.value})" type="text" id="set-movie-frame-width" size=5 placeholder="横幅">
              <span>[px] 横幅</span><span class="message-label"> (ブラウザの横幅：{{window_width}})</span><br>
            </div>
            <div class="setting-content">
              <input :value="getMediaMovie['height']" @input="updateMediaMovieObjectItem({key:'height',value:$event.target.value})" type="text" id="set-movie-frame-height" size=5 placeholder="縦幅">
              <span>[px] 縦幅</span><span class="message-label"> (ブラウザの縦幅：{{window_height}})</span>
            </div>
            <button  class="setting-content" type="submit" @click="createMovieFrame">再生プレイヤー作成</button>
            <button  class="setting-content" type="submit" @click="deleteMovieFrame">削除</button>

          </div>
          <div class="setting-loop setting-content" v-on:click="changeLoopSetting" :class="{'isLoop' : getMediaMovie['isLoop']}">
            <i class="media-yt-loop-icon fas fa-undo-alt fa-2x"></i>
            <span style="margin-left:10px">ループ</span>
          </div>
        </div>

      </div>
      <i v-on:click="closeModal()" id="change-disp-modal" class="fas fa-times-circle fa-2x for-mobile"></i>
      <div class="close-icon-wrapper for-pc-tablet">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-left fa-3x"></i>
      </div>
    </div>
  </transition>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
export default {
  props : [
    'transitionName',
  ],
  data : () => {
    return {
      youtubeUrl : '',
      window_width : 0,
      window_height : 0,
    }
  },
  computed : {
    ...mapGetters('mediaMovie', ['getMediaMovie']),
  },
  methods : {
    ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
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
      this.updateMediaMovieObjectItem({key:'videoId',value:this.extractVideoIdFromUrl(youtubeUrl)});
    },
    // updateVideoWidth(event){},
    // updateVideoHeight(event){},
    createMovieFrame() {
      // 親コンポーネントの動画フレーム作成メソッドを実行
      this.$emit('create-movie-frame');
    },
    deleteMovieFrame(){
      this.$emit('delete-movie-frame');
    },
    changeLoopSetting(){
      this.updateMediaMovieObjectItem({key:'isLoop',value:!(this.getMediaMovie['isLoop'])});
    },
  },
  mounted : function() {
    this.window_width = window.innerWidth;
    this.window_height = window.innerHeight;
  },

}
</script>


<style scoped>

@import "../../css/mediaEditModals.css";

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