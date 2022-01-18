<template>
  <transition :name="transitionName">
    <div id="select-modal" @click.stop @touchstart.stop>
      <div id="area-wrapper">

        <div id="yt-setting-area" class="flex column a-start">
          <p id="player-setting-title">動画プレイヤー設定</p>
          <div class="yt-setting-wrapper w100 flex column a-start">
            <!-- 動画ID -->
            <div class="setting-block about-yt-video-id">
              <h3 class="setting-title">youtube動画のURL</h3>
              <input id="youtube-url-input" :value="youtubeUrl" @input="updateVideoId" type="text" size=30 placeholder="youtube movie URL">
            </div>
            <!-- 再生プレイヤーの作成・削除 -->
            <div class="setting-block about-create-del flex column a-start">
              <h3 class="setting-title">再生プレイヤー</h3>
              <div class="flex">
                <button  class="setting button create-btn" type="submit" @click="createMovieFrame">作成</button>
                <button  class="setting button delete-btn" type="submit" @click="deleteMovieFrame">削除</button>
              </div>
            </div>
            <!-- 再生プレイヤーの縦横幅 -->
            <div class="setting-block about-size w90">
              <h3 class="setting-title">サイズ</h3>
              <div class="flex column">
                <div class="setting-width flex j-s-between a-center">
                  <div class="flex a-center" style="opacity:0.7">
                    <i class="fas fa-arrows-alt-h icon"></i>
                    <span>横幅</span>
                  </div>
                  <div class="flex a-center">
                    <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('width')"></i>
                    <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('width')"></i>
                  </div>
                  <input id="set-movie-frame-width" class="setting" :value="getMediaMovie['width']" @input="updateWidth($event)" type="number" placeholder="横幅">
                </div>
                <div class="setting-height flex j-s-between a-center">
                  <div class="flex a-center" style="opacity:0.7">
                    <i class="fas fa-arrows-alt-v icon"></i>
                    <span>縦幅</span>
                  </div>
                  <div class="flex a-center">
                    <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('height')"></i>
                    <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('height')"></i>
                  </div>
                  <input id="set-movie-frame-height" class="setting" :value="getMediaMovie['height']" @input="updateHeight($event)" type="number" placeholder="縦幅">
                </div>
              </div>
            </div>
            <!-- 動画のループ設定 -->
            <div class="setting-block about-loop">
              <h3 class="setting-title">ループ</h3>
              <div class="flex a-center">
                <div class="toggle-outer flex a-center" v-on:click="changeLoopSetting" :class="{'is-loop-outer' : getMediaMovie['isLoop']}">
                  <div class="toggle-inner" :class="{'is-loop-inner' : getMediaMovie['isLoop']}"></div>
                </div>
                <span style="margin-left:5px;opacity:0.7">{{loopOnOff}}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../change_display_parts/CloseModalIconComponent.vue'

export default {
  components : {
    closeModalBar,
    closeModalIcon,
  },
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
    loopOnOff(){ return (this.getMediaMovie['isLoop'] ? "ON":"OFF"); },
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
    minusOneValue(data_key){this.updateMediaMovieObjectItem({key:data_key, value:Number(this.getMediaMovie[data_key]-1)})},
    plusOneValue(data_key){this.updateMediaMovieObjectItem({key:data_key, value:Number(this.getMediaMovie[data_key]+1)})},
    updateWidth(event){ this.updateMediaMovieObjectItem({key:'width',value:Number(event.target.value)}) },
    updateHeight(event){ this.updateMediaMovieObjectItem({key:'height',value:Number(event.target.value)}) },
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

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";


  #player-setting-title {
    font-weight: bold;
    margin: 10px 0 30px 0;
    background-color: orangered;
    border-radius: 5px;
    padding: 2px 10px;
  }

  #yt-setting-area {
    margin: 20px 0;
    width: 95%;
    overflow-y: scroll;
  }

  .about-yt-video-id {
    margin-bottom: 40px;
  }

  #youtube-url-input {
    width: 95%;
  }

  .setting-block {
    margin-bottom: 30px;
  }

  .about-size .icon {
    width: 20px;
    margin-right: 2px;
  }

  .setting-width,
  .setting-height {
    margin: 0 5px 15px 5px;
  }

  .about-create-del .button {
    margin-right: 17px;
    padding: 5px 18px;
    color: white;
    border: none;
    border-radius: 2px;
    box-shadow: 1px 1px 1px;
    opacity: 0.7;
  }
  .about-create-del .button:hover{
    opacity: 0.9;
  }

  .create-btn {
    background-color: orange;
  }
  .delete-btn {
    background-color: grey;
  }

  /* トグル */
  .toggle-outer{
    width: 38px;
    height: 17px;
    padding: 2px;
    border-radius: 20px;
    background-color: grey;
    transition-duration: 0.4s;
  }

  .toggle-inner {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: white;
  }


  .setting-title {
    font-size: 15px;
  }

  .about-size input {
    width: 60px;
  }

  .is-loop-outer {
    background-color: rgb(70,140,250);
  }
  .is-loop-inner {
    margin-left: 19px;
  }

  .btns {
    border-radius: 50%;
    padding: 5px 4px;
  }
  .btns:hover { cursor: pointer;}
  .plus-btn {
    color: palevioletred;
    border: 1.5px solid palevioletred;
  }
  .minus-btn {
    color: deepskyblue;
    border: 1.5px solid deepskyblue;
  }


@media screen and (max-width:480px) {
  #yt-setting-area {
    margin: 0;
  }

  #area-wrapper {
    padding: 20px;
  }
  
}


</style>