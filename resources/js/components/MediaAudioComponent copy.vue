<template>
  <!-- <transition name="right-slide"> -->
    <!-- Mediaオーディオ -->
  <div id="media-audio-wrapper" v-bind:class="{'is-show': isShowAudio}">
    <!-- オーディオの表示・非表示切り替えアイコン -->
    <div class="change-disp-audio-wrapper">
      <span v-if="isEditMode" class="media-audio-num">{{mediaAudioNum}}</span>
        <i v-on:click="isShowAudio = !(isShowAudio)" class="fas fa-chevron-left fa-3x change-disp-audio for-pc-tablet" v-bind:class="{'is-reverse': isShowAudio}"></i>
        <i v-on:click="isShowAudio = !(isShowAudio)" class="fas fa-music fa-2x change-disp-audio for-mobile" v-show="!isShowAudio"></i>
        <i v-on:click="isShowAudio = !(isShowAudio)" class="fas fa-times fa-2x change-disp-audio for-mobile" v-show="isShowAudio"></i>
    </div>

    <div v-show="isShowAudio" class="media-audio-controller-zone">
      <!-- オーディオ再生・停止 -->
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

      <!-- 選択したオーディオ一覧 -->
      <div id="media-audio-frame">
        <ul id="audios">
          <li class="audio-area" :id="index" v-for="(mediaAudio, index) in mediaAudios" :key="mediaAudio.audio_url">
            <!-- オーディオのサムネと各種アイコン -->
            <div class="audio-wrapper" :class="{'isPlay' : mediaAudio['isPlay']}">
              <img class="media-audio-thumbnail"
              src="" v-show="mediaAudio"
              :alt="index">
              <i class="media-audio-play-icon fas fa-caret-right fa-4x" v-on:click="playMediaAudio" v-show="!(mediaAudio['isPlay'])"></i>
              <i class="media-audio-pause-icon fas fa-pause fa-2x" v-on:click="pauseMediaAudio" v-show="mediaAudio['isPlay']"></i>
              <i class="media-audio-delete-icon fas fa-times fa-2x" v-on:click="deleteAudio" v-show="isEditMode"></i>
              <i class="media-audio-loop-icon fas fa-undo-alt fa-2x" v-on:click="setAudioLoop" v-show="isEditMode" :class="{'isLoop' : mediaAudio['isLoop']}"></i>
            </div>
            <!-- オーディオ名 -->
            <div v-if="mediaAudio" class="media-audio-name-wrapper">
              <span class="media-audio-name">
                {{mediaAudio['name']}}
              </span>
            </div>
            <!-- ボリューム -->
            <div class="audio-vol-wrapper">
              <i class="media-audio-vol-icon fas fa-volume-off fa-2x" v-on:click="setAudioVolume"></i>
              <div class="vol-bar-wrapper">
                <input type="range" :id="index" class="audio-vol-range" v-on:input="updateAudioVol" min="0" max="1" step="0.01">
              </div>
            </div>

          </li>
          <li class="non-audio-frame" v-for="n in 5" :key="n" v-show="!(mediaAudios[n-1])">
          </li>
        </ul>
      </div>
    </div>



  </div>
  <!-- </transition> -->
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    props : [
      'maxAudioNum',
      'mediaAudios',
    ],

    data : () => {
      return {
        audioPlayers : [],
        isShowAudio : false,
        isEditMode : false,
        mediaAudioNum : 0,
      }
    },
    computed : {
      ...mapGetters('mediaAudios', ['getMediaAudios']),
    },
    methods : {
      ...mapMutations('mediaAudios', ['deleteMediaAudiosObjectItem']),
      ...mapMutations('mediaAudios', ['addMediaAudiosObjectItem']),
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),
      ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
      hideAudio(){ // media閲覧時に最初に実行される
        this.isShowAudio = false;
      },
      validEditMode(){ // 親コンポーネントから実行される
        this.isEditMode = true;
      },
      playMediaAudio: function(event) {
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.mediaAudios[audioIndex]['player_index'];
        this.audioPlayers[playerIndex].play();
        this.$parent.mediaAudios[audioIndex]['isPlay'] = true;
      },
      pauseMediaAudio: function(event) {
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.mediaAudios[audioIndex]['player_index'];
        this.audioPlayers[playerIndex].pause();
        this.$parent.mediaAudios[audioIndex]['isPlay'] = false;
      },
      onFinishAudio : function(i){
        let mediaAudioNum = this.$parent.mediaAudios.length;
        for(let j=0; j < mediaAudioNum; j++){
          let mediaAudio = this.$parent.mediaAudios[j];
          if(mediaAudio['player_index'] == i && mediaAudio['isLoop'] == false){
            this.$parent.mediaAudios[j]['isPlay'] = false;
          }
        }
      },
      playAllAudio(){
        this.audioPlayers.forEach(function(audioPlayer, index){
          audioPlayer.play();
        });
        let audioNum = this.$parent.mediaAudios.length;
        for(let i = 0; i < audioNum; i++){
          this.$parent.mediaAudios[i]['isPlay'] = true;
        }
      },
      finishPlayAudio(){
        this.audioPlayers.forEach(function(audioPlayer){
          let audioDuration = audioPlayer.duration;
          audioPlayer.currentTime = audioDuration;
        });
      },
      setPlayerInfo(){ // 親コンポーネントのmediaAudiosから再生情報を取得
        let audioNum = this.$parent.mediaAudios.length;
        for(let i=0; i < audioNum; i++){
          let audioPlayerIndex = this.mediaAudios[i]['player_index'];
          this.audioPlayers[audioPlayerIndex].src = this.mediaAudios[i]['audio_url'];
          this.audioPlayers[audioPlayerIndex].volume = this.mediaAudios[i]['volume'];
          this.audioPlayers[audioPlayerIndex].loop = this.mediaAudios[i]['isLoop'];
        }
      },
      addAudio(audio) {
        audio['isPlay'] = false;
        audio['isLoop'] = false;
        audio['volume'] = 0.5;
        let beforeAudioNum = this.$parent.mediaAudios.length;
        // オーディオは1メディアに5つまで。
        // 既に5つある場合は一つ消してから追加。
        if(beforeAudioNum == this.maxAudioNum){
          // まずはプレイヤーの初期化
          // 一番古いオーディオに対応するプレイヤーを選択
          let resetPlayerIndex = this.$parent.mediaAudios[0]['player_index'];
          this.audioPlayers[resetPlayerIndex].pause(); // 停止して
          let newAudio = new Audio(); //新しいオーディオプレイヤーを作って
          this.audioPlayers.splice(resetPlayerIndex, 1, newAudio); // プレイヤーを入れ替え

          // プレイヤーの初期化が終わったら、一番古いオーディオを削除
          this.$parent.mediaAudios.splice(0, 1);
        }
        // オーディオの追加
        this.$parent.mediaAudios.push(audio);

        // 追加されたオーディオの情報を取得
        let addedAudioIndex = this.$parent.mediaAudios.length - 1;
        let addedAudio = this.$parent.mediaAudios[addedAudioIndex];
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
        // プレイヤーのインデックスをaudioに設定
        addedAudio['player_index'] = emptyPlayerIndex;

        // オーディオサムネイルの更新
        this.$nextTick(function () { // DOMの更新を待つ
          this.updateAudioThumbnail();
        });
      },
      // ★最も再生時間が長いオーディオの再生時間を取得
      setLongestAudioDurationToFinishTime(){
        let longestAudioDuration = 0;
        for(let i=0; i<this.maxAudioNum; i++){
          if(longestAudioDuration < this.audioPlayers[i].duration){
            longestAudioDuration = this.audioPlayers[i].duration
          }
        }
        console.log(longestAudioDuration);
        this.updateMediaSettingObjectItem({key:'finish_time', value:longestAudioDuration});
      },
      updateAudioThumbnail() {
        let audioDoms = document.getElementsByClassName('audio-wrapper');
        let audioNum = audioDoms.length;
        for(let i = 0; i < audioNum; i++){
          // オーディオのサムネイル表示&更新
          let audioThumbnail = audioDoms[i].firstChild;
          let targetAudio = this.$parent.mediaAudios[i];
          audioThumbnail.setAttribute('src', targetAudio['thumbnail_url']);
        }
      },
      judgeDelAudio(url) {
        if(this.mediaAudioUrl == url){
          this.mediaAudioUrl = "";
        }
      },
      deleteAudio: function(event) {
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.mediaAudios[audioIndex]['player_index'];
        this.audioPlayers[playerIndex].pause(); // オーディオの再生を止めて、
        let newAudioPlayer = new Audio(); // 新しいplayerを用意して、
        this.audioPlayers.splice(playerIndex, 1, newAudioPlayer); // 削除したplayerと入れ替える

        // デバッグ用後で消す
        for(let i=0; i < 5; i++){
          console.log(i, this.audioPlayers[i].src);
        }

        this.$parent.mediaAudios.splice(audioIndex, 1);

        // オーディオの更新
        this.$nextTick(function(){ // DOMの更新を待つ
          this.updateAudioThumbnail();
          // this.updateAudioPlayers();
        });
      },
      setAudioLoop: function(event){  
        let audioIndex = event.target.parentNode.parentNode.getAttribute('id');
        let playerIndex = this.$parent.mediaAudios[audioIndex]['player_index'];
        let audioPlayer = this.audioPlayers[playerIndex];
        if(audioPlayer.loop == false){
          audioPlayer.loop = true;
        } else if(audioPlayer.loop == true){
          audioPlayer.loop = false;
        }
        this.$parent.mediaAudios[audioIndex]['isLoop'] = audioPlayer.loop;
      },
      setAudioVolume: function(event) {
        console.log('called setAudioVolume', event.target.getAttribute('class'));
      },
      doubleVal: function(event){
        return 0;
      },
      updateAudioVol(event){
        let audioIndex = event.target.getAttribute('id');
        let audioPlayerIndex = this.mediaAudios[audioIndex]['player_index'];
        let audioVolume = event.target.value;
        this.mediaAudios[audioIndex]['volume'] = audioVolume;
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
    watch : {
      mediaAudios : function(){
        this.mediaAudioNum = this.$parent.mediaAudios.length;
      }
    }

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

  .audio-area {
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
  .audio-area:hover {
    opacity: 1;
  }

  .audio-area:hover
  .media-audio-play-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .media-audio-pause-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .media-audio-delete-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .media-audio-loop-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
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
    z-index : 10;

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