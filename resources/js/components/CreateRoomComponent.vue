<template>
  <div id="field" v-on:click.self="closeModal()">

    <!-- オーディオ再生終了 -->
    <button id="finish-button" @click="finishPlayAudio">再生終了</button>

    <!-- Room画像 -->
    <div id="room-img-frame" v-on:click="showImgModal()">
      <p v-show="!(roomImgUrl)"></p>
      <img id="room-img" :src="roomImgUrl" v-show="roomImgUrl" alt="画像が選択されていません">
    </div>

    <!-- youtube -->
    <div id='player'></div>

    <!-- Roomオーディオ -->
    <div id="room-audio-frame">

      <ul id="audios">
        <li class="audio-wrapper" :class="{'isPlay' : roomAudio['isPlay']}" :id="index" v-for="(roomAudio, index) in roomAudios" :key="roomAudio.id">
          <img class="room-audio-thumbnail"
          src="" v-show="roomAudio"
          :alt="index">
          <i class="room-audio-play-icon fas fa-caret-right fa-4x" v-on:click="playRoomAudio" v-show="!(roomAudio['isPlay'])"></i>
          <i class="room-audio-pause-icon fas fa-pause fa-3x" v-on:click="pauseRoomAudio" v-show="roomAudio['isPlay']"></i>
          <i class="room-audio-delete-icon fas fa-times fa-2x" v-on:click="deleteAudio"></i>
          <i class="room-audio-loop-icon fas fa-undo-alt fa-2x" v-on:click="loopAudio" :class="{'isLoop' : roomAudio['isLoop']}"></i>
        </li>
        <li class="non-audio-frame" v-for="n in 5" :key="n" v-show="!(roomAudios[n-1])">
        </li>
      </ul>
    </div>

    <!-- 画像&オーディオ 選択モーダル表示ボタン -->
    <div id="disp-modal-zone">
      <div id="disp-modal-wrapper">

        <!-- 画像 -->
        <div id="disp-img-modal-wrapper" class="icon-wrapper" v-on:click="showImgModal()">
          <i class="fas fa-image fa-2x"></i>
        </div>
        <!-- オーディオ -->
        <div id="disp-audio-modal-wrapper" class="icon-wrapper" v-on:click="showAudioModal()">
          <i class="fas fa-music fa-2x"></i>
        </div>

      </div>
    </div>



    <!-- 画像選択コンポーネント -->
    <img-select-component 
    v-show="isShowImg" 
    v-on:close-modal="closeModal" 
    v-on:set-img-url="setRoomImgUrl"
    v-on:img-del-notice="judgeDelImg">
    </img-select-component>

    <!-- オーディオ選択コンポーネント -->
    <audio-select-component 
    v-show="isShowAudio" 
    v-on:close-modal="closeModal" 
    v-on:add-audio="addAudio"
    v-on:audio-del-notice="judgeDelAudio">
    </audio-select-component>
  </div>
</template>

<script>
import ImgSelect from './ImgSelectComponent.vue';
import AudioSelect from './AudioSelectComponent.vue';
export default {
  components : {
    ImgSelect,
    AudioSelect
  },
  data : () => {
    return {
      button_text : "画像選択",
      isShowImg : false,
      isShowAudio : false,
      roomImgUrl : "",
      maxAudioNum : 5,
      roomAudios : [],
      audioPlayers : [],
      // roomAudioUrls : [],
      // roomAudioThumbnailUrls : []
    }
  },
  methods : {
    finishPlayAudio(){
      this.audioPlayers.forEach(function(audioPlayer){
        let audioDuration = audioPlayer.duration;
        audioPlayer.currentTime = audioDuration;
        console.log(audioDuration);
      });
      this.roomAudios.forEach(function(roomAudio){
        console.log('aaabbb');
        roomAudio['isPlay'] = false;
      });
    },
    showImgModal() {
      this.isShowAudio = false;
      this.isShowImg = true;
    },
    showAudioModal() {
      this.isShowImg = false;
      this.isShowAudio = true;
    },
    closeModal() {
      this.isShowImg = false;
      this.isShowAudio = false;
    },
    setRoomImgUrl(url) {
      this.roomImgUrl = url;
    },
    judgeDelImg(url) {
      if(this.roomImgUrl == url){
        this.roomImgUrl = "";
      }
    },
    addAudio(audio) {
      audio['isPlay'] = false;
      audio['isLoop'] = false;
      let beforeAudioNum = this.roomAudios.length;
      // オーディオは1ルームに5つまで。
      // 既に5つある場合は一つ消してから追加。
      if(beforeAudioNum == this.maxAudioNum){
        // プレイヤーの初期化
        let resetPlayerIndex = this.roomAudios[0]['player_index'];
        this.audioPlayers[resetPlayerIndex].pause();
        let newAudio = new Audio();
        this.audioPlayers.splice(resetPlayerIndex, 1, newAudio);

        // オーディオの入れ替え
        this.roomAudios.splice(0, 1);
      }
      this.roomAudios.push(audio);

      // 追加されたオーディオの情報を取得
      let addedAudioIndex = this.roomAudios.length - 1;
      let addedAudio = this.roomAudios[addedAudioIndex];
      let addedAudioUrl = addedAudio['audio_url'];
      
      // 空いているオーディオプレイヤーの中で一番小さいIndexを取得
      let emptyPlayerIndex;
      this.audioPlayers.some(function(audioPlayer, index){
        emptyPlayerIndex = index;
        // console.log(index, audioPlayer.src);
        if(audioPlayer.src == ""){
          return true;
        };
      });
      this.audioPlayers[emptyPlayerIndex].src = addedAudioUrl;

      console.log('ループ再生フラグ', this.audioPlayers[emptyPlayerIndex].loop);

      // プレイヤーのインデックスをaudioに設定
      addedAudio['player_index'] = emptyPlayerIndex;

      // オーディオサムネイルの更新
      this.$nextTick(function () { // DOMの更新を待つ
        this.updateAudioThumbnail();
        // this.updateAudioPlayers();
      });

    },
    deleteAudio: function(event) {
      let audioIndex = event.target.parentNode.getAttribute('id');
      let playerIndex = this.roomAudios[audioIndex]['player_index'];
      this.audioPlayers[playerIndex].pause(); // オーディオの再生を止めて、
      let newAudioPlayer = new Audio(); // 新しいplayerを用意して、
      this.audioPlayers.splice(playerIndex, 1, newAudioPlayer); // 削除したplayerと入れ替える

      // デバッグ用後で消す
      for(let i=0; i < 5; i++){
        console.log(i, this.audioPlayers[i].src);
      }

      this.roomAudios.splice(audioIndex, 1);

      // オーディオの更新
      this.$nextTick(function(){ // DOMの更新を待つ
        this.updateAudioThumbnail();
        // this.updateAudioPlayers();
      });
    },
    updateAudioThumbnail() {
      let audioDoms = document.getElementsByClassName('audio-wrapper');
      let audioNum = audioDoms.length;
      for(let i = 0; i < audioNum; i++){
        // オーディオのサムネイル表示&更新
        let audioThumbnail = audioDoms[i].firstChild;
        let targetAudio = this.roomAudios[i];
        audioThumbnail.setAttribute('src', targetAudio['thumbnail_url']);
      }
    },
    loopAudio: function(event){
      let audioIndex = event.target.parentNode.getAttribute('id');
      let playerIndex = this.roomAudios[audioIndex]['player_index'];
      let audioPlayer = this.audioPlayers[playerIndex];
      if(audioPlayer.loop == false){
        audioPlayer.loop = true;
      } else if(audioPlayer.loop == true){
        audioPlayer.loop = false;
      }
      this.roomAudios[audioIndex]['isLoop'] = audioPlayer.loop;
    },
    // updateAudioPlayers() {
    //   const audioSrcs = [];
    //   this.roomAudios.forEach(function(roomAudio){;
    //     audioSrcs.push(roomAudio['audio_url']);
    //   });
    //   this.audioPlayers.forEach(function(audioPlayer, index){
    //     audioPlayer.src = audioSrcs[index];
    //   });
    // },
    judgeDelAudio(url) {
      if(this.roomAudioUrl == url){
        this.roomAudioUrl = "";
        this.roomAudioThumbnailUrl = "";
      }
    },
    playRoomAudio: function(event) {
      let audioIndex = event.target.parentNode.getAttribute('id');
      let playerIndex = this.roomAudios[audioIndex]['player_index'];
      this.audioPlayers[playerIndex].play();
      this.roomAudios[audioIndex]['isPlay'] = true;
    },
    pauseRoomAudio: function(event) {
      let audioIndex = event.target.parentNode.getAttribute('id');
      let playerIndex = this.roomAudios[audioIndex]['player_index'];
      this.audioPlayers[playerIndex].pause();
      this.roomAudios[audioIndex]['isPlay'] = false;
    },
    
  },
  finishAudio(){
    alert('finish');
  },
  mounted() {
    for(let i = 0; i < this.maxAudioNum; i++){
      let audioPlayer = new Audio();
      // audioPlayer.src = "";
      this.audioPlayers.push(audioPlayer);
    }

    // オーディオの再生終了を監視
    this.roomAudios.forEach(function(roomAudio){
      roomAudio.onended = this.finishAudio();
    });

    // youtubeplayer
    var tag = window.document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var player;
    function onYouTubeIframeAPIReady() {
      player = new YT.Player('player', {
        height: '360',
        width: '640',
        videoId: 'M7lc1UVf-VE',
        events: {
          'onReady': onPlayerReady,
          'onStateChange': onPlayerStateChange
        }
      })
      alert('get youtube ready');
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
      event.target.playVideo();
    }

    // 5. The API calls this function when the player's state changes.
    //    The function indicates that when playing a video (state=1),
    //    the player should play for six seconds and then stop.
    var done = false;
    function onPlayerStateChange(event) {
      if (event.data == YT.PlayerState.PLAYING && !done) {
        setTimeout(stopVideo, 6000);
        done = true;
      }
    }
    function stopVideo() {
      player.stopVideo();
    }


  }

}
</script>

<style>
  #field {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 2;
    width: 100%;
    height: 100%;
    /* padding :1em; */
    background-color:white; 

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
  }


/* img */
  #room-img-frame {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 400px;
    height: 400px;
    border: 2px;
    border-style: dotted;
    border-color: cadetblue;
  }

  #room-img {
    width: 400px;
    height: 400px;
  }



/* audio */
#audios{
  display: flex;
  padding-left: 0;
}

.audio-wrapper,
.non-audio-frame {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  /* background-color: cornflowerblue; */
  border: 2px dotted lightgrey;

  margin: 20px 10px;

  position: relative;

  opacity: 0.7;

  display: flex;
  justify-content: center;
  align-items: center;
}

.room-audio-thumbnail {
  width: 70px;
  height: 70px;
  border-radius: 50%;
}


#disp-modal-zone {
  position: absolute;
  top: 0;
  right: 0;

  height: 100%;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

#disp-modal-wrapper {
  background-color: ghostwhite;
}

.icon-wrapper {
  padding: 20px;
}

#disp-img-modal-wrapper {
  background-color:hotpink;
}

#disp-audio-modal-wrapper {
  background-color: gold;
}

.isPlay {
  border-color: green;
  opacity: 1;
}

.room-audio-play-icon,
.room-audio-pause-icon {
  position: absolute;
  top: 5;
  z-index: -2;
  color: rgba(0,255,0,0);
}

.room-audio-play-icon {
  left: 28px;
}

.room-audio-pause-icon {
  left: 16px;
}

.room-audio-delete-icon {
  position: absolute;
  left: 0;
  margin-bottom: 60px;
  z-index: -2;
  color: rgba(0,255,0,0);
}

.room-audio-loop-icon {
  position: absolute;
  right: 0;
  margin-bottom: 60px;
  z-index: -2;
  color:  rgba(50,50,180,0.4);
  /* opacity: 0; */
  display: none;
}


.audio-wrapper:hover
.room-audio-play-icon {
  z-index: 2;
  color:  rgba(0,255,0,1);
}

.audio-wrapper:hover
.room-audio-pause-icon {
  z-index: 2;
  color:  rgba(0,255,0,1);
}

.audio-wrapper:hover
.room-audio-delete-icon {
  z-index: 2;
  color:  rgba(180,50,50,0.4);
}

.audio-wrapper:hover
.room-audio-loop-icon {
  z-index: 2;
  display: inline-block;
}

.room-audio-delete-icon:hover {
  color:  rgba(255,10,10,0.8);
}

.room-audio-loop-icon:hover {
  color:  rgba(10,10,255,1);
}

.isLoop {
  display: inline-block;
  color:  rgba(10,10,255,0.6);
}

</style>