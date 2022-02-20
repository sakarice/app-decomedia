<template>
  <transition :name="transitionName">
    <div id="select-modal" @click.stop @touchstart.stop>
      <div id="area-wrapper">

        <p id="player-setting-title" class="w100">動画プレイヤー</p>
        <div id="yt-setting-area" class="flex column a-start">
          <div class="yt-setting-wrapper w100 flex column a-start">

            <!-- 動画ID -->
            <div class="setting-block about-yt-video-id">
              <!-- <h3 class="setting-title" v-show="!isCreatedFrame">youtube動画のURL</h3> -->
              <input id="youtube-url-input" v-show="!isCreatedFrame" :value="youtubeUrl" @input="updateVideoId" type="text" size=30 placeholder="youtube動画のURLを入力して下さい">
              <!-- 再生プレイヤーの作成・削除 -->
              <div class="btn-wrapper flex mt3">
                <button class="setting button create-btn" type="submit" v-show="!isCreatedFrame" @click="createMovieFrame">
                  <i class="fas fa-plus fa-lg plus-icon mr5"></i>動画プレイヤー作成
                </button>
                <button class="setting button delete-btn" type="submit" v-show="isCreatedFrame" @click="deleteMovieFrame">動画プレイヤー削除</button>
              </div>
            </div>

            <div class="player-property-wrapper flex column w100 p5 m1">
              <!-- 再生プレイヤーの縦横幅 -->
              <div class="about-size w100 mb20">
                <h3 class="setting-title">プレイヤーのサイズ</h3>
                <div class="flex column">
                  <div class="setting-width setting-row flex j-s-between a-end">
                    <div class="flex a-center" style="opacity:0.7">
                      <i class="fas fa-arrows-alt-h icon"></i>
                      <span>横幅</span>
                    </div>
                    <div class="flex a-end">
                      <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('width')"></i>
                      <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('width')"></i>
                    </div>
                    <input id="set-movie-frame-width" class="setting" :value="getMediaMovie['width']" @input="updateWidth($event)" type="number" placeholder="横幅">
                  </div>
                  <div class="setting-height setting-row flex j-s-between a-end">
                    <div class="flex a-end" style="opacity:0.7">
                      <i class="fas fa-arrows-alt-v icon"></i>
                      <span>縦幅</span>
                    </div>
                    <div class="flex a-end">
                      <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue('height')"></i>
                      <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue('height')"></i>
                    </div>
                    <input id="set-movie-frame-height" class="setting" :value="getMediaMovie['height']" @input="updateHeight($event)" type="number" placeholder="縦幅">
                  </div>
                </div>
              </div>
              <!-- 動画のループ設定 -->
              <div class="setting-row about-loop flex j-s-between a-end w100 mb20">
                <h3 class="setting-title m0">ループ再生</h3>
                <div class="flex a-end">
                  <span class="mr5 font-11 grey">{{loopOnOff}}</span>
                  <div class="toggle-outer flex a-end" v-on:click="changeLoopSetting" :class="{'is-loop-outer' : getMediaMovie['isLoop']}">
                    <div class="toggle-inner" :class="{'is-loop-inner' : getMediaMovie['isLoop']}"></div>
                  </div>
                </div>
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
      isCreatedFrame : false,
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
      this.isCreatedFrame = true;
    },
    deleteMovieFrame(){
      this.$emit('delete-movie-frame');
      this.isCreatedFrame = false;
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
    margin: 10px 0;
    padding: 5px 0;
    text-align: center;
  }

  #yt-setting-area {
    margin: 10px 0;
    width: 95%;
  }

  .about-yt-video-id {
    margin-bottom: 40px;
  }

  #youtube-url-input {
    width:95%;
  }

  .setting-block {
    width: 100%;
    margin-bottom: 50px;
  }

  .setting-row {
    padding: 0 5px 3px 5px;
    border-bottom: 0.5px solid rgba(200,200,200,0.2);
  }

  .player-property-wrapper {
    /* outline: 1px dotted dimgrey; */
    margin: 1px;
    overflow-y: scroll;
  }

  .about-size .icon {
    width: 20px;
    margin-right: 2px;
  }

  .setting-width,
  .setting-height {
    margin: 0 5px 15px 5px;
  }

  .btn-wrapper .button {
    padding: 2px 5px;
    color: white;
    border: none;
    border-radius: 2px;
  }
  .btn-wrapper .button:hover{
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
    height: 19px;
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
    font-size: 13px;
    color: darkgrey;
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

  .grey { color: grey;}


@media screen and (max-width:480px) {
  #player-setting-title {
    margin: 0 0 10px 0;
  }

  #yt-setting-area {
    margin: 0;
    align-items: center;
  }

  .player-property-wrapper {
    max-height: 140px;
  }

  #area-wrapper {
    padding: 10px;
  }

  .setting-block {
    margin-bottom: 20px;
  }
  
}


</style>