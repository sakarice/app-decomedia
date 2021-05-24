<template>
  <!-- <transition name="right-slide"> -->
    <!-- Room Movie-->
  <div id="room-movie-wrapper">

    <!-- youtube -->
    <div id="youtube-url-form">
      <div id="yt-player-wrapper">
        <div id='player'></div>
          <!-- <p class="youtube-url-description">youtube動画を設定する場合は、動画ページのURLを入力してください。</p> -->
        </div>
      </div>
    <button type="submit" @click="deleteYoutube">削除</button>

  </div>
  <!-- </transition> -->
</template>

<script>
  export default {
    props : [
      'isShowYoutube',
      // 'youtubePlayerVars'
    ],

    data : () => {
      return {
        ytPlayer : "",
        isLoopYoutube : false,
        playerVars : {},
        // youtubeVideoId : '',
        // movieFrameWidth : 320,
        // movieFrameHeight : 500,
    
      }
    },
    methods : {
      loopYoutube(){
        if(this.isLoopYoutube == false){
          this.isLoopYoutube = true;
        } else {
          this.isLoopYoutube = false;
        }
      },
      createYtPlayer(vars){
        this.ytPlayer = new YT.Player('player', {
          height: vars['height'],
          width: vars['width'],
          videoId: vars['videoId'],
          playerVars: {
            // 'autoplay' : 0,
            // 'loop' : false,
            // 'controls' : true,
            'modestbranding' : 1,
            // 'fs' : false,
          },
          events: {
            'onReady': this.onPlayReady(),
            'onStateChange': this.onPlayerStateChange.bind(this),
          }

        });

      },
      onPlayReady() {
        this.$parent.isShowYoutube = true;
        console.log(this.$parent.isShowYoutube);
        // this.ytPlayer.loadVideoById(this.youtubeVideoId);
      },
      onPlayerStateChange(event) {
        if(event.data == 0 && this.isLoopYoutube == true){
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
      deleteYoutube() {
        this.ytPlayer = "";
        this.initPlayerDom();
        this.$parent.isShowYoutube = false;
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

      window.onYouTubeIframeAPIReady = () => {
        console.log('get youtube ready');
      }
    },


  }

</script>



<style>
  #youtube-url-form{
    margin: 20px;
  }

  .youtube-url-description {
    margin-bottom: 5px;
    font-size: 12px;
  }

  .room-yt-loop-icon {
    margin: 10px;
  }


</style>