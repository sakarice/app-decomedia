<template>
  <!-- 選択したオーディオ一覧 -->
  <!-- オーディオのサムネと各種アイコン -->
  <div class="audio-player-wrapper" :class="{'isPlay' : isPlay}">
    <img v-show="getMediaAudios" class="media-audio-thumbnail" :src="thumbnailUrl">
    <i class="media-audio-play-icon fas fa-caret-right fa-4x" v-on:click="play" v-show="!(isPlay)"></i>
    <i class="media-audio-pause-icon fas fa-pause fa-2x" v-on:click="pause" v-show="isPlay"></i>
  </div>
</template>

<script>
  import { mapGetters, } from 'vuex';
  export default {
    props : [
      // storeから取得するオーディオオブジェクトのインデックス。
      'mediaAudioIndex'
    ],
    data : () => {
      return {
        player : "",
        isPlay : false,
      }
    },
    computed : {
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      thumbnailUrl : function(){
        return this.getMediaAudios[this.mediaAudioIndex]['thumbnail_url'];
      },
      loopSetting : function(){
        return this.getMediaAudios[this.mediaAudioIndex]['isLoop'];
      }
    },
    methods : {
      play(){ 
        this.player.play(); 
      },
      pause(){ 
        this.player.pause(); 
        this.isPlay = false;
      },
      finish(){ // 再生位置を終わりに設定して疑似的に再生終了を実現する
        this.player.currentTime = Math.floor(this.player.duration);
      },
      setPlayerInfo(){ // 親コンポーネントのmediaAudiosから再生情報を取得
          // this.player.src = this.getMediaAudios[this.mediaAudioIndex]['audio_url'];
          this.player.volume = this.getMediaAudios[this.mediaAudioIndex]['volume'];
          this.player.loop = this.getMediaAudios[this.mediaAudioIndex]['isLoop'];
      },
      updateAudioThumbnail(){},
      updateLoopSetting(loopSetting){ this.player.loop = loopSetting},
      updateVolume(volume){ this.player.volume = volume; },
      onPlayingAudio(){this.isPlay = true; },
      onNotPlayingAudio(){this.isPlay = false; },
      onFinishAudio(){if(this.player.loop==false){this.onNotPlayingAudio()}},
    },
    created : function(){
      let tmpThis = this;
      const setAudioData = new Promise((resolve,reject)=>{
        tmpThis.player = new Audio(tmpThis.getMediaAudios[tmpThis.mediaAudioIndex]['audio_url']);
        tmpThis.setPlayerInfo();
        resolve();
      });
      setAudioData.then(function(){
        tmpThis.player.addEventListener('loadedmetadata', function(){
          tmpThis.$emit('setMediaAudioDuration', tmpThis.mediaAudioIndex, tmpThis.player.duration);
          tmpThis.$emit('taskAfterAudioAdded', tmpThis.mediaAudioIndex);
        });
        tmpThis.player.addEventListener('playing',()=>{ tmpThis.onPlayingAudio() });
        tmpThis.player.addEventListener('pause',()=>{ tmpThis.onNotPlayingAudio() });
        tmpThis.player.addEventListener('ended',()=>{ tmpThis.onFinishAudio()});

      })

    },
    mounted : function() {},
    watch : {
      loopSetting : function(val){
        this.updateLoopSetting(val);
      }
    },

  }

</script>



<style scoped>
  .audio-player-wrapper {
    display: flex;
    align-items: center;
    border: 1.5px dotted lightgrey;
    border-radius: 50%;
  }

  .media-audio-thumbnail {
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }

  .media-audio-play-icon,
  .media-audio-pause-icon {
    position: absolute;
    top: 5;
    z-index: -1;
    color: rgba(0,255,0,0.7);
    display: none;
  }

  .media-audio-play-icon {
    left: 14px;
  }
  .media-audio-pause-icon {
    left: 8.5px;
  }

  /* hover設定(wrapper) */
  .audio-player-wrapper:hover {
    opacity: 1;
  }

  .audio-player-wrapper:hover
  .media-audio-play-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-player-wrapper:hover
  .media-audio-pause-icon {
    z-index: 2;
    display: inline-block;
  }



  /* hover設定(各アイコン) */
  .media-audio-play-icon:hover {
    color:  rgba(0,255,0,1);
  }
  .media-audio-pause-icon:hover {
    color:  rgba(0,255,0,1);
  }

  /* 再生関連 */
  .isPlay {
    border-color: springgreen;
    opacity: 1;
  }


@media screen and (max-width:480px) {

}


</style>