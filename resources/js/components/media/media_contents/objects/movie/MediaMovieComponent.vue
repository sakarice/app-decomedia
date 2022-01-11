<template>
  <!-- <transition name="right-slide"> -->
  <div :id="movieWrapperWithIndex"
  class="movie-wrapper"
  :style="movieWrapperBindStyle()">

    <div class="movie-resize-trigger-area"
    :style="movieResizeTriggerAreaBindStyle()"
    @mousedown="moveStart($event)" @touchstart="moveStart($event)">
      <i class="far fa-hand-lizard fa-2x pinch-icon"></i>
    </div>

    <!-- youtube -->
    <div id="yt-player-wrapper">
      <div id='player'></div>
    </div>

    <movie-resize :class="{hidden:!isActive}"
    :index="index"
    @move="moveStart($event)"
    @resize="resize()">
    </movie-resize>

    <div id="youtube-url-form"></div>

  </div>
  <!-- </transition> -->
</template>

<script>
  import {moveStart} from '../../../../../functions/moveHelper'
  import { mapGetters, mapMutations } from 'vuex';
  import movieResize from '../object_edit_parts/MovieResizeComponent.vue'
  export default {
    components : {
      movieResize,
    },
    props : [],
    data : () => {
      return {
        index : 0,
        "isActive" : false,
        ytPlayer : "",
        // playerVars : {},
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('media', ['getMode']),
      ...mapGetters('mediaMovie', ['getIsInitializedMovie']),
      ...mapGetters('mediaMovie', ['getMediaMovie']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      movieWrapperWithIndex(){ return ('media-movie-wrapper' + this.index) },
    },
    watch : {
    },
    methods : {
      ...mapMutations('mediaMovie', ['updateIsInitializedMovie']),
      ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
      ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
      // サイズ変更
      resize(){
        this.ytPlayer.width = this.getMediaMovie['width'];
        this.ytPlayer.height = this.getMediaMovie['height'];
      },
      // 位置操作用
      moveStart(e){
        if(this.getMode != 3){
          const move_target_dom = document.getElementById(this.movieWrapperWithIndex);
          moveStart(e, move_target_dom);
          move_target_dom.addEventListener('moveFinish', this.moveEnd, false);
        }
      },
      moveEnd(e){
        e.target.removeEventListener('moveFinish', this.moveEnd, false);
        this.updateMediaMovieObjectItem({index:this.index,key:"left",value:e.detail.left});
        this.updateMediaMovieObjectItem({index:this.index,key:"top",value:e.detail.top});
      }, 
      movieWrapperBindStyle(){
        const mm = this.getMediaMovie;
        const style = {
          // "top" : Number(mm['top']) + "px",
          // "left" : Number(mm['left']) + "px",
          // "z-index" : Number(mm['layer']),
          "top" : mm['top'] + "px",
          "left" : mm['left'] + "px",
          "z-index" : mm['layer'],
        }
        return style;
      },
      movieResizeTriggerAreaBindStyle(){
        const mm = this.getMediaMovie;
        const style = {
          "width" : mm['width'] + 60 + "px",
          "height" : mm['height'] + 60 + "px",
        }
        return style;
      },

      getMediaMovieFromDB(){
        return new Promise((resolve, reject) => {
          const url = '/mediaMovie/'+this.getMediaId;
          axios.get(url)
          .then(response=>{
            return resolve(response.data);
          })
          .catch(error=>{});
        })
      },
      initMovie(){
        this.getMediaMovieFromDB()
        .then(datas=>{
          for(let key in datas){
            this.updateMediaMovieObjectItem({key:key, value:datas[key]});
          }
          this.updateIsInitializedMovie(true);
          // this.initStatus += 4;
        });
      },
      loopYoutube(){
        if(this.getMediaMovie['isLoop'] == false){
          this.updateMediaSettingObjectItem({kye:'isLoop', value:true});
        } else {
          this.updateMediaSettingObjectItem({kye:'isLoop', value:false});
        }
      },
      createYtPlayer(){
        this.ytPlayer = new YT.Player('player', {
          height: this.getMediaMovie['height'],
          width: this.getMediaMovie['width'],
          videoId: this.getMediaMovie['videoId'],
          playerVars: {
            // 'autoplay' : 0,
            // 'loop' : false,
            // 'controls' : true,
            'modestbranding' : 1,
            // 'fs' : false,
          },
          events: {
            'onReady': this.onPlayReady.bind(this),
            'onStateChange': this.onPlayerStateChange.bind(this),
          }
        });

      },
      onPlayReady(event) { // 再生準備完了
        this.$parent.getReadyPlayMovie = true;
        // this.ytPlayer.playVideo();
          if(this.$parent.autoPlay == true){
            event.target.playVideo();
          }
      },
      // ★動画の長さ取得
      setMovieDurationToFinishTime(){
        let movieDuration = this.ytPlayer.getDuration();
        this.updateMediaSettingObjectItem({key:'finish_time', value:movieDuration});

      },
      onPlayerStateChange(event) {
        if(event.data == 0 && this.getMediaMovie['isLoop']){
          this.ytPlayer.seekTo(0);
          event.target.playVideo();
        }
      },
      submitYoutubeUrl(event) {
        // let url = event.target.previousElementSibling.value;
        // let pattern = /v=.*/;
        // let matchText = url.match(pattern); // object型で返ってくる
        // matchText = matchText.toString(); // object⇒stringへ変換
        // let videoId = matchText.substring(2, 13);  // videoID部分を切りだし
        // this.youtubeVideoId = videoId;
        // if(this.ytPlayer == ""){
        //   this.createYtPlayer(this.youtubeVideoId);
        // } else if(this.ytPlayer != ""){
        //   this.ytPlayer.cueVideoById(this.youtubeVideoId);
        //   this.onPlayReady();
        // }
      },
      deleteYtPlayer() {
        this.ytPlayer = "";
        this.updateMediaMovieObjectItem({key:'videoId', value:""});
        this.initPlayerDom();
      },
      initPlayerDom() {
        // 初期状態に戻す(現在の再生プレイヤーを削除し、playerのdivタグを配置)

        // 1.プレイヤー削除
        let target = document.getElementById('player');
        let clone = target.cloneNode(false);
        target.parentNode.replaceChild(clone, target);

        // 2.divタグ配置
        let parent = document.getElementById('yt-player-wrapper');
        parent.innerHTML = '<div id="player"><div>';

      }

    },
    created() {
      // youtubeplayer
      const tag = window.document.createElement('script');
      tag.src = "https://www.youtube.com/iframe_api";
      tag.async = true;
      const firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      window.onYouTubeIframeAPIReady = () => {}
    },
    mounted(){
      // イベント登録
      document.addEventListener('click', (e)=> {
        // if(!e.target.closest("#"+this.movieWrapperWithIndex)){
        if(!e.target.closest("#media-movie-wrapper0")){
          this.isActive = false;
        } else {
          this.isActive = true;
        }
      });
    }


  }

</script>



<style scoped>
  /* #youtube-url-form{
    margin: 20px;
  } */
  #media-movie-wrapper {
    position : absolute;
  }

  .movie-wrapper {
    position: absolute;
    display: flex;
    justify-content: center;
  }

  .movie-resize-trigger-area {
    position: absolute;
    display: flex;
    justify-content: center;
    top : -30px;
    left : -30px;
    opacity : 0.2;
    border-radius: 6px;
    box-shadow: 1px 1px 10px darkgrey;
    opacity : 0;
  }
  .movie-resize-trigger-area:hover{
    cursor: all-scroll;
  }
  .movie-wrapper:hover .movie-resize-trigger-area{
    opacity: 1;
  }
  .pinch-icon {
    height: 30px;
    margin-top: -22px;
    opacity: 0.5;
    transform-origin:center;
    transform: rotate(-60deg);
  }


  #yt-player-wrapper {
    position: absolute;
    top: 0;
    left: 0;
  }

  .youtube-url-description {
    margin-bottom: 5px;
    font-size: 12px;
  }

  .media-yt-loop-icon {
    margin: 10px;
  }

  .hidden {
    opacity: 0;
  }


</style>