<template>
  <!-- <transition name="right-slide"> -->
    <!-- Media Movie-->
  <div id="media-movie-wrapper"
  v-bind:style="{'z-index' : getMediaMovie['layer']}">

    <!-- youtube -->
    <div id="yt-player-wrapper">
      <div id='player'></div>
    </div>

    <div id="youtube-url-form"></div>

  </div>
  <!-- </transition> -->
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    props : [],

    data : () => {
      return {
        ytPlayer : "",
        // playerVars : {},
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaMovie', ['getIsInitializedMovie']),
      ...mapGetters('mediaMovie', ['getMediaMovie']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
    },
    methods : {
      ...mapMutations('mediaMovie', ['updateIsInitializedMovie']),
      ...mapMutations('mediaMovie', ['updateMediaMovieObjectItem']),
      ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
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
        // // console.log(url);
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


  }

</script>



<style scoped>
  /* #youtube-url-form{
    margin: 20px;
  } */
  #media-movie-wrapper {
    position : absolute;

  }

  .youtube-url-description {
    margin-bottom: 5px;
    font-size: 12px;
  }

  .media-yt-loop-icon {
    margin: 10px;
  }


</style>