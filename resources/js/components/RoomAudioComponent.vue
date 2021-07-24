<template>
  <!-- <transition name="right-slide"> -->
    <!-- Roomオーディオ -->
  <div id="room-Audio-wrapper">
    <!-- オーディオ再生終了 -->
    <div v-show="isEditMode" class="all-audio-controll-wrapper">
      <div class="all-audio-controller all-audio-play-wrapper">
        <!-- <button id="play-all-button" @click="playAllAudio">全オーディオ再生</button> -->
        <div class="size-Adjust-box">
          <i id="play-all-icon" class="fas fa-caret-right fa-3x" @click="playAllAudio"></i>
        </div>
        <span style="color:grey">play all</span>
      </div>

      <div class="all-audio-controller all-audio-finish-wrapper">
        <div class="size-Adjust-box">
          <!-- <button id="finish-button" @click="finishPlayAudio">オーディオ再生終了</button> -->
          <i id="finish-all-icon" class="fas fa-pause fa-2x" @click="finishPlayAudio"></i>
        </div>
        <span style="color:grey">stop all</span>
      </div>
    </div>

    <div id="room-audio-frame">
      <ul id="audios">
        <li class="audio-area" :id="index" v-for="(roomAudio, index) in roomAudios" :key="roomAudio.audio_url">
          <div class="audio-wrapper" :class="{'isPlay' : roomAudio['isPlay']}">
            <img class="room-audio-thumbnail"
            src="" v-show="roomAudio"
            :alt="index">
            <i class="room-audio-play-icon fas fa-caret-right fa-4x" v-on:click="playRoomAudio" v-show="!(roomAudio['isPlay'])"></i>
            <i class="room-audio-pause-icon fas fa-pause fa-3x" v-on:click="pauseRoomAudio" v-show="roomAudio['isPlay']"></i>
            <i class="room-audio-delete-icon fas fa-times fa-2x" v-on:click="deleteAudio" v-show="isEditMode"></i>
            <i class="room-audio-loop-icon fas fa-undo-alt fa-2x" v-on:click="setAudioLoop" v-show="isEditMode" :class="{'isLoop' : roomAudio['isLoop']}"></i>
          </div>
          <div class="audio-vol-wrapper">
            <i class="room-audio-vol-icon fas fa-volume-off fa-2x" v-on:click="setAudioVolume"></i>
            <div class="vol-bar-wrapper">
              <input type="range" :id="index" class="audio-vol-range" v-on:input="updateAudioVol" min="0" max="1" step="0.01">
            </div>
          </div>
        </li>
        <li class="non-audio-frame" v-for="n in 5" :key="n" v-show="!(roomAudios[n-1])">
        </li>
      </ul>

    </div>
  </div>
  <!-- </transition> -->
</template>

<script>
  export default {
    props : [
      'maxAudioNum',
      'roomAudios',
    ],

    data : () => {
      return {
        audioPlayers : [],
        isEditMode : false,
      }
    },
    methods : {
      validEditMode(){ // 親コンポーネントから実行される
        this.isEditMode = true;
      },
      playRoomAudio: function(event) {
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.roomAudios[audioIndex]['player_index'];
        this.audioPlayers[playerIndex].play();
        this.$parent.roomAudios[audioIndex]['isPlay'] = true;
      },
      pauseRoomAudio: function(event) {
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.roomAudios[audioIndex]['player_index'];
        this.audioPlayers[playerIndex].pause();
        this.$parent.roomAudios[audioIndex]['isPlay'] = false;
      },
      onFinishAudio : function(i){
        let roomAudioNum = this.$parent.roomAudios.length;
        for(let j=0; j < roomAudioNum; j++){
          let roomAudio = this.$parent.roomAudios[j];
          if(roomAudio['player_index'] == i && roomAudio['isLoop'] == false){
            this.$parent.roomAudios[j]['isPlay'] = false;
          }
        }
      },
      playAllAudio(){
        this.audioPlayers.forEach(function(audioPlayer, index){
          audioPlayer.play();
        });
        let audioNum = this.$parent.roomAudios.length;
        for(let i = 0; i < audioNum; i++){
          this.$parent.roomAudios[i]['isPlay'] = true;
        }
      },
      finishPlayAudio(){
        this.audioPlayers.forEach(function(audioPlayer){
          let audioDuration = audioPlayer.duration;
          audioPlayer.currentTime = audioDuration;
        });
      },
      setPlayerInfo(){ // 親コンポーネントのroomAudiosから再生情報を取得
        let audioNum = this.$parent.roomAudios.length;
        for(let i=0; i < audioNum; i++){
          let audioPlayerIndex = this.roomAudios[i]['player_index'];
          this.audioPlayers[audioPlayerIndex].src = this.roomAudios[i]['audio_url'];
          this.audioPlayers[audioPlayerIndex].volume = this.roomAudios[i]['volume'];
          this.audioPlayers[audioPlayerIndex].loop = this.roomAudios[i]['isLoop'];
        }
      },
      addAudio(audio) {
        audio['isPlay'] = false;
        audio['isLoop'] = false;
        audio['volume'] = 0.5;
        let beforeAudioNum = this.$parent.roomAudios.length;
        // オーディオは1ルームに5つまで。
        // 既に5つある場合は一つ消してから追加。
        if(beforeAudioNum == this.maxAudioNum){
          // プレイヤーの初期化
          let resetPlayerIndex = this.$parent.roomAudios[0]['player_index'];
          this.audioPlayers[resetPlayerIndex].pause();
          let newAudio = new Audio();
          this.audioPlayers.splice(resetPlayerIndex, 1, newAudio);

          // オーディオの入れ替え
          this.$parent.roomAudios.splice(0, 1);
        }
        this.$parent.roomAudios.push(audio);

        // 追加されたオーディオの情報を取得
        let addedAudioIndex = this.$parent.roomAudios.length - 1;
        let addedAudio = this.$parent.roomAudios[addedAudioIndex];
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
        });
      },
      updateAudioThumbnail() {
        let audioDoms = document.getElementsByClassName('audio-wrapper');
        let audioNum = audioDoms.length;
        for(let i = 0; i < audioNum; i++){
          // オーディオのサムネイル表示&更新
          let audioThumbnail = audioDoms[i].firstChild;
          let targetAudio = this.$parent.roomAudios[i];
          audioThumbnail.setAttribute('src', targetAudio['thumbnail_url']);
        }
      },
      judgeDelAudio(url) {
        if(this.roomAudioUrl == url){
          this.roomAudioUrl = "";
        }
      },
      deleteAudio: function(event) {
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.roomAudios[audioIndex]['player_index'];
        this.audioPlayers[playerIndex].pause(); // オーディオの再生を止めて、
        let newAudioPlayer = new Audio(); // 新しいplayerを用意して、
        this.audioPlayers.splice(playerIndex, 1, newAudioPlayer); // 削除したplayerと入れ替える

        // デバッグ用後で消す
        for(let i=0; i < 5; i++){
          console.log(i, this.audioPlayers[i].src);
        }

        this.$parent.roomAudios.splice(audioIndex, 1);

        // オーディオの更新
        this.$nextTick(function(){ // DOMの更新を待つ
          this.updateAudioThumbnail();
          // this.updateAudioPlayers();
        });
      },
      setAudioLoop: function(event){  
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.roomAudios[audioIndex]['player_index'];
        let audioPlayer = this.audioPlayers[playerIndex];
        if(audioPlayer.loop == false){
          audioPlayer.loop = true;
        } else if(audioPlayer.loop == true){
          audioPlayer.loop = false;
        }
        this.$parent.roomAudios[audioIndex]['isLoop'] = audioPlayer.loop;
      },
      setAudioVolume: function(event) {
        console.log('called setAudioVolume', event.target.getAttribute('class'));
      },
      doubleVal: function(event){
        return 0;
      },
      updateAudioVol(event){
        let audioIndex = event.target.getAttribute('id');
        let audioPlayerIndex = this.roomAudios[audioIndex]['player_index'];
        let audioVolume = event.target.value;
        this.roomAudios[audioIndex]['volume'] = audioVolume;
        this.audioPlayers[audioPlayerIndex].volume = audioVolume;
      }

    },
    mounted : function() {
      for(let i = 0; i < this.maxAudioNum; i++){
        let audioPlayer = new Audio();
        this.audioPlayers.push(audioPlayer);
      }

      // オーディオの再生終了を監視
      for(let i=0; i < this.maxAudioNum; i++){
        this.audioPlayers[i].onended = this.onFinishAudio.bind(this,i);
      };
    
    },


  }

</script>



<style scoped>

  /* 全オーディオの再生停止コントローラー */
  .all-audio-controll-wrapper {
    padding-bottom: 7px;
    margin-bottom: 10px;
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
  #finish-all-icon {
    color: lightgrey;
    margin-top: 5px;
  }

  /* audio */
  #room-Audio-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    width: 120px;
    height: 100%;
    padding: 20px 5px;
    background-color: black;

    display: flex;
    flex-direction: column;
    align-items: center;
  }

  #audios{
    display: flex;
    flex-direction: column;
    padding-left: 0;

  }

  .audio-area {
    position: relative;
    display: flex;
    align-items: center;
  }

  .audio-wrapper,
  .non-audio-frame {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    /* background-color: cornflowerblue; */
    border: 2px dotted lightgrey;

    margin: 10px 5px;

    position: relative;

    opacity: 0.7;

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .room-audio-thumbnail {
    width: 60px;
    height: 60px;
    border-radius: 50%;
  }

  .room-audio-play-icon,
  .room-audio-pause-icon {
    position: absolute;
    top: 5;
    z-index: -1;
    color: rgba(0,255,0,0.7);
    display: none;
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
    z-index: -1;
    color:  rgba(220,50,50,0.8);
    display: none;
  }

  .room-audio-loop-icon {
    position: absolute;
    right: 0;
    margin-bottom: 60px;
    z-index: -1;
    color:  rgba(20,20,250,0.8);
    display: none;
  }

  .room-audio-vol-icon {
    /* position: absolute;
    top: 37px;
    right: 30px; */
    margin-right: 3px;
    z-index: -1;
    color:  rgba(255,255,255,0.8);
    display: none;
  }

  /* hover設定(wrapper) */
  .audio-area:hover {
    opacity: 1;
  }

  .audio-area:hover
  .room-audio-play-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .room-audio-pause-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .room-audio-delete-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .room-audio-loop-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .room-audio-vol-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-vol-wrapper {
    position: absolute;
    left: 70px;
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
  .room-audio-play-icon:hover {
    color:  rgba(0,255,0,1);
  }

  .room-audio-pause-icon:hover {
    color:  rgba(0,255,0,1);
  }

  .room-audio-delete-icon:hover {
    color:  rgba(255,10,10,1);
  }

  .room-audio-loop-icon:hover {
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


</style>