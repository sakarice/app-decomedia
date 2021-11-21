<template>
  <!-- 選択したオーディオ一覧 -->
  <!-- オーディオのサムネと各種アイコン -->
  <div class="audio-wrapper" :class="{'isPlay' : getMediaAudios['isPlay']}">
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
        this.player.currentTime = this.player.duration;
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
    watch : {},

  }

</script>



<style scoped>

  /* 全オーディオの再生停止コントローラー */
  .all-audio-controll-wrapper {
    padding-bottom: 5px;
    margin-bottom: 5px;
    border-bottom: double 2px grey;
    width: 90px;

    display: flex;
    justify-content: space-between;
  }

  .size-Adjust-box {
    opacity: 0.85;
    height: 33px;
    display: flex;
    justify-content: center;
  }
  .size-Adjust-box:hover{
    opacity: 1;
  }

  .all-audio-controller {
    color: ghostwhite;
    height: 50px;
    font-size: 11px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  #play-all-icon {
    color: green;
  }
  #play-all-icon:hover {
    cursor: pointer;
  }
  #finish-all-icon {
    color: lightgrey;
    margin-top: 5px;
  }
  #finish-all-icon:hover {
    cursor: pointer;
  }

  /* audio */
  #media-audio-wrapper {
    position: absolute;
    top:55px;
    bottom: 10px;
    right: 0;
    width: 180px;
    padding: 5px 0 60px 0;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    display: flex;
    flex-direction: column-reverse;
    justify-content: space-around;
    /* overflow-y: scroll; */
  }
  .is-show {
    background-color: rgba(0,0,0,0.8);
    z-index: 15;
  }

  .media-audio-controller-zone{
    padding-left: 15px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    overflow-y: scroll;
  }

  #media-audio-frame {
    height: 100%;
  }

  #audios{
    height: 100%;
    margin: 0;
    padding-left: 0;

    display: flex;
    flex-flow: column;
    justify-content: space-around;

  }

  .audio-wrapper {
    position: relative;
    display: flex;
    align-items: center;
  }

  .audio-wrapper,
  .non-audio-frame {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    /* background-color: cornflowerblue; */
    border: 1.5px dotted lightgrey;
    margin: 10px 5px;
    position: relative;
    opacity: 0.7;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .media-audio-thumbnail {
    width: 53px;
    height: 53px;
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
    left: 18px;
  }

  .media-audio-pause-icon {
    left: 11px;
  }

  .media-audio-delete-icon {
    position: absolute;
    left: -15px;
    top: -15px;
    z-index: -1;
    color:  rgba(220,50,50,0.8);
    display: none;
  }

  .media-audio-loop-icon {
    position: absolute;
    right: -15px;
    top: -15px;
    z-index: -1;
    color:  rgba(20,20,250,0.8);
    display: none;
  }

  .media-audio-vol-icon {
    /* position: absolute;
    top: 37px;
    right: 30px; */
    margin-right: 3px;
    z-index: -1;
    color:  rgba(255,255,255,0.8);
    display: none;
  }

  /* hover設定(wrapper) */
  .audio-wrapper:hover {
    opacity: 1;
  }

  .audio-wrapper:hover
  .media-audio-play-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-wrapper:hover
  .media-audio-pause-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-wrapper:hover
  .media-audio-delete-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-wrapper:hover
  .media-audio-loop-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-wrapper:hover
  .media-audio-vol-icon {
    z-index: 2;
    display: inline-block;
  }

  .media-audio-name {
    color : white;
    font-size: 0.7rem;
  }

  .audio-vol-wrapper {
    position: absolute;
    top: 43px;
    left: 40px;
    /* transform: rotate(180deg); */
    display: flex;
    align-items: center;
  }

  .vol-bar-wrapper {
    display: flex;
    align-items: center;
    display: none;


  }

  .audio-vol-wrapper:hover
  .vol-bar-wrapper {
    display: inline-block;
  }


  /* hover設定(各アイコン) */
  .media-audio-play-icon:hover {
    color:  rgba(0,255,0,1);
  }

  .media-audio-pause-icon:hover {
    color:  rgba(0,255,0,1);
  }

  .media-audio-delete-icon:hover {
    color:  rgba(255,10,10,1);
  }

  .media-audio-loop-icon:hover {
    color:  rgba(10,10,255,1);
  }

  .audio-vol-range {
    -webkit-appearance: none;
    appearance: none;
    cursor: pointer;
    /* background: #8acdff; */
    height: 2px;
    width: 50px;
    margin-bottom: 12px;
  }

  .change-disp-audio-wrapper {
    position: absolute;
    bottom: 0px;
    width: 100%;
    height: auto;

    display: flex;
    justify-content: flex-end;
    align-items: center;
  }

  .is-reverse{
    transform: scale(-1, 1);
  }

  .media-audio-num {
    z-index: 1;
    background-color: rgba(50, 110, 110, 0.7);
    color: white;
    padding: 0 8px;
    border-radius: 50%;
    margin-right: -20px;
    margin-top: -60px;
    font-size: 16px;
  }

  .change-disp-audio {
    color: lightgrey;
    margin: 0 10px 10px 0;
    padding: 10px 19px 10px 15px;
    border-radius: 50%;
    background-color: rgba(0,0,0, 0.5);
  }
  .change-disp-audio:hover {
    background-color: rgba(0,110,110, 0.5);
    cursor: pointer;
  }


  /* 再生関連 */
  .isPlay {
    border-color: green;
    opacity: 1;
  }

  .isLoop {
    color:  rgba(0,0,255,1);
    display: inline-block;
    z-index: 2;
  }

@media screen and (max-width:480px) {
  .change-disp-audio {
    padding: 10px 12px 10px 8px;
  }
  .fa-times {
    padding: 10px 15px;
  }
  .media-audio-delete-icon {
    left: -25px;
    top: -25px;
  }
  .media-audio-num {
    padding: 0 6px;
    margin-right: -14px;
    margin-top: -50px;
    font-size: 13px;    
  }  
}


</style>