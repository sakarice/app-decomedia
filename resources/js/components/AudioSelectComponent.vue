<template>
  <transition name="right-slide">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="area-wrapper">
        <div id="drop-zone" 
        :class="{show: isDragEnter, hidden: !(isDragEnter)}"
        @dragleave = "dragLeave"
        @dragover.prevent
        @drop.prevent = "dropFile">
        </div>
        <div id="contents-wrapper"
        @dragenter = "dragEnter">
          <div id="toggle-wrapper">
            <button id="file-category-toggle" tabindex=1 @click="changeFileCategory" :class="{isDefault: isDefault, isUpload: !(isDefault)}">
              <div id="toggle-state-icon"></div>
            </button>
            <div id="category-type"><span>{{fileCategory}}</span></div>

            <!-- 再生を強制終了する　※後で消す -->
            <button id="finishPlay" @click="finishPlay">再生終了</button>
          </div>

          <!-- アップロードエリア -->
          <div id="upload-input-wrapper">
            <label id="upload-label" for="upload-audio-input" tabindex=2 @keydown.enter="startInput" v-show="!(isDefault)">
              <i class="fas fa-upload" style="margin-right: 5px"></i>
              <span>アップロード</span>
              <input id="upload-audio-input" style="display: none" @change="selectedFile" type="file" accept="audio/*" name="audio">
            </label>
            <div id="loading-display-wrapper" v-show="isLoading">
              <p class="loading-message">{{loadingMessage}}</p>
              <div id="uploading-dot" :class="{'copy-to-right': isLoading}"></div>
            </div>
          </div>

          <!-- オーディオのリスト表示 -->
          <ul id="audio-thumbnail-wrapper">

            <!-- uploads -->
            <li v-show="!(isDefault)" class="audio-list" v-for="(userOwnAudio, index) in userOwnAudios" :key="userOwnAudio.id">
              <img class="audio-thumbnail" :src="userOwnAudio['thumbnail_url']" :alt="userOwnAudio['thumbnail_url']">
              <span class="audio_name" :class="{'now-play' : userOwnAudio['isPlay']}" v-on:click="addAudioToRoom('user-own', index)">
                {{userOwnAudio['audio_name']}}
              </span>
              <i class="audio-play-icon fas fa-caret-right fa-2x"
               v-show="!(userOwnAudio['isPlay'])"
               v-on:click="playAudio('user-own', index)"></i>
              <i class="audio-pause-icon fas fa-pause fa-lg"
               v-show="userOwnAudio['isPlay']"
               v-on:click="pauseAudio('user-own', index)"></i>
              <i class="delete-audio fas fa-times fa-2x" v-on:click="deleteaudio"></i>
            </li>

            <!-- default -->
            <li v-show="isDefault" class="audio-list" v-for="(defaultAudio, index) in defaultAudios" :key="defaultAudio.id">
              <img class="audio-thumbnail" :src="defaultAudio['thumbnail_url']" :alt="defaultAudio['thumbnail_url']">
              <span class="audio_name" :class="{'now-play' : defaultAudio['isPlay']}" v-on:click="addAudioToRoom('default', index)">
                {{defaultAudio['audio_name']}}
              </span>
              <i class="audio-play-icon fas fa-caret-right fa-2x"
              v-show="!(defaultAudio['isPlay'])"
              v-on:click="playAudio('default', index)"></i>
              <i class="audio-pause-icon fas fa-pause fa-lg"
              v-show="defaultAudio['isPlay']"
              v-on:click="pauseAudio('default', index)"></i>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data : () => {
    return {
      popMessage : 'メッセージです',
      isDefault : true,
      fileCategory : "default",
      isDragEnter : false,
      uploadFile : "",
      isLoading : false,
      loadingMessage : "",
      userOwnAudios : [],   // thumbnail_url, audio_name
      defaultAudios : [],
      audioPlayer : new Audio(),
      playAudioType : "",
      playAudioIndex : -1,
      playAudioUrl : "",
      isPlay : false,
      // userOwnAudioThumbnailUrls : [],
      // defaultAudioThumbnailUrls : []
    }
  },
  methods : {
    finishPlay(){
      let audioDuration = this.audioPlayer.duration;
      this.audioPlayer.currentTime = audioDuration - 1;
    },
    changeFileCategory(){
      this.isDefault = !(this.isDefault);
      if(this.isDefault == true){
        this.fileCategory = "default";
      } else {
        this.fileCategory = "uploads";
      }
    },
    getUserOwnAudios(){
      const url = '/ajax/getUserOwnAudios';
      axios.get(url)
        .then(response => {
          response.data.audios.forEach(audio => {
            audio['isPlay'] = false;
            this.userOwnAudios.unshift(audio);
          });
        })
        .catch(error => {
          alert('オーディオサムネイル取得失敗');
        })
    },
    getDefaultAudios(){
      const url = '/ajax/getDefaultAudios';
      axios.get(url)
        .then(response => {
          response.data.audios.forEach(audio => {
            audio['isPlay'] = false;
            this.defaultAudios.unshift(audio);
          });
        })
        .catch(error => {
          alert('オーディオサムネイル取得失敗');
        })
    },
    playAudio : function(type, index){
      // 選択したオーディオを再生
      let playTargetAudio;
      if(type == 'user-own'){
        playTargetAudio = this.userOwnAudios[index];
      } else if (type == 'default'){
        playTargetAudio = this.defaultAudios[index];
      }

      // テスト用　後で消す
      // let audio = document.getElementById('audio');
      // audio.src = playTargetAudio['audio_url'];
      // audio.play();

      this.audioPlayer.src = playTargetAudio['audio_url'];
      this.audioPlayer.play();
      this.isPlay = true;
      playTargetAudio['isPlay'] = true;

      // 一つ前に再生していたオーディオがあれば、再生中フラグを折る
      let stopTargetAudio;
      if(this.playAudioType !== ""){
        if(this.playAudioType == 'user-own'){
          stopTargetAudio = this.userOwnAudios[this.playAudioIndex];
        } else if(this.playAudioType == 'default'){
          stopTargetAudio = this.defaultAudios[this.playAudioIndex];
        }
        stopTargetAudio['isPlay'] = false;
      }

      // 再生中のオーディオ種別とインデックスを更新
      this.playAudioType = type;
      this.playAudioIndex = index;


      // ★後で消す
      console.log(this.audioPlayer.ended);
    },

    // ★後で消す
    finishAudio: function(event){
      let isFinish = event.target.ended;
      alert(isFinish);
    },
    pauseAudio : function(type, index){
      // オーディオを再生停止
      this.audioPlayer.pause();
      this.isPlay = false;

      // 対象オーディオの再生フラグを折る
      let targetAudio;
      if(type == 'user-own'){
        targetAudio = this.userOwnAudios[index];
      } else if (type == 'default'){
        targetAudio = this.defaultAudios[index];
      }
      targetAudio['isPlay'] = false;

      // 再生中のオーディオ種別とインデックスを更新(再生オーディオなし)
      this.playAudioType = "";
      this.playAudioIndex = -1;
    },
    addAudioToRoom : function(type, index) {
      // こちらのコンポーネントの配列をそのまま渡すと、
      // 参照渡しとなり、コンポーネント間で配列が同期され予期せぬ同差をしてしまう
      // 配列の内容だけを、新しい配列にコピーして、room側に渡す。
      let tmpAudio;
      if(type == 'user-own'){
        tmpAudio = this.userOwnAudios[index];
      } else if (type == 'default'){
        tmpAudio = this.defaultAudios[index];
      }

      // 新しい連想配列を用意　
      let audio = {};
      audio['audio_name'] = tmpAudio['audio_name'];
      audio['audio_url'] = tmpAudio['audio_url'];
      audio['thumbnail_url'] = tmpAudio['thumbnail_url'];
      audio['isPlay'] = false;


      this.$emit('add-audio', audio);
    },
    
    dragEnter: function() {
      this.isDragEnter = true;
    },
    dragLeave: function() {
      this.isDragEnter = false;
    },
    closeModal() {
      this.$emit('close-modal');
    },
    sendUserOwnAudioThumbnailUrl: function(event){
      let thumbnailUrl = event.target.previousElementSibling.getAttribute('src');
      this.$emit('set-audio-thumbnail-url', thumbnailUrl);
    },
    startInput(event){
      let target = document.getElementById('upload-input');
      target.click();
    },
    // アップロードされたファイルをdataに保存する
    selectedFile: function(e) {
      e.preventDefault();
      let files = e.target.files;
      this.uploadFile = files[0];
      this.uploadAudio();
    },
    dropFile() {
      this.uploadFile = [...event.dataTransfer.files][0];
      this.uploadAudio();
      this.isDragEnter = false;
    },
    // ajaxでオーディオファイルを送る
    uploadAudio() {
      const url = '/ajax/uploadAudio';
      let formData = new FormData();
      formData.append('audio', this.uploadFile);
      this.loadingMessage = 'アップロード中'
      this.isLoading = true;
      // アイコンを回転させてローディング中であることを表現
      // const loading_icon = document.getElementById('loading-icon');
      // loading_icon.classList.add('rotate');
      axios.post(url, formData)
        .then(response => {
          this.userOwnAudios.unshift(response.data.audios);
          // alert(this.userOwnAudios['audio_name']);
          this.uploadFile = "";
          this.loadingMessage = '';
          this.isLoading = false;
          // loading_icon.classList.remove('rotate');
          this.dragLeave();
        })
        .catch(error => {
          alert('アップロード失敗');
        });
    },
    // オーディオファイルを削除する
    deleteaudio:function(event) {
      const url = '/ajax/deleteAudio'
      let audioUrl = event.target.previousElementSibling.getAttribute('id');
      const params = {
        'audioUrl' : audioUrl
      }
      this.loadingMessage = '削除中'
      this.isLoading = true;
      // const loading_icon = document.getElementById('loading-icon');
      // loading_icon.classList.add('rotate');
      // alert(audioUrl);
      axios.post(url, params)
        .then(response => {
          alert(response.data);
          // 画面に即自反映するため、オーディオURLをdataから削除
          // 削除対象URLが入っている配列のインデックスを取得
          let index = this.userOwnAudios.some(function(v, i){
            if(v['audio_url']==audioUrl) {
              return (i);
            };
          });
          // 配列から削除
          this.userOwnAudios.splice(index,1);
          this.loadingMessage = ''
          this.isLoading = false;
          // loading_icon.classList.remove('rotate');
          // Roomオーディオと同じだった場合は削除する必要があるので、親コンポーネントに通知
          // this.$emit('audio-thumbnail-del-notice', audioUrl);
        })
        .catch(error => {
          alert('オーディオ削除失敗');
        })

    },
    finishAudio(){
      this.isPlay = false;
      if(this.playAudioType == 'user-own'){
        this.userOwnAudios[this.playAudioIndex]['isPlay'] = false;
      } else if(this.playAudioType == 'default'){
        this.defaultAudios[this.playAudioIndex]['isPlay'] = false;
      }
      this.isPlay = false;
      this.playAudioType = "";
      this.playAudioIndex = -1;
    },

  },
  mounted : function() {
    this.getUserOwnAudios();
    this.getDefaultAudios();

    let audio = this.audioPlayer;
    audio.onended = this.finishAudio.bind(this);
  },

}
</script>


<style>

  #select-modal {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 2;
    width: 400px;
    height: 100vh;

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    flex-flow: row;

  }

  #toggle-wrapper {
    display: flex;
    margin-bottom: 20px;
  }

  #file-category-toggle {
    width: 50px;
    height: 24px;
    outline: none;
    border: none;
    border-radius: 50px;
    padding: 2px 2px;
    background-color: plum;
  }
  #file-category-toggle:focus {
    box-shadow: 0 0 0 1px grey;
  }

  #category-type {
    width: 60px;
    margin-left: 10px;
    color: grey;
    display: flex;
    align-items: center;
  }

  .isUpload {
    animation-name: change-toggle-left-to-right;
    animation-duration: 0.2s;
    animation-timing-function: ease-out;
    animation-fill-mode: forwards; 
  }
  @keyframes change-toggle-left-to-right{
    0% {
      background-color: plum;
      padding-left: 2px;
    }    
    100% {
      background-color:paleturquoise;
      padding-left: 28px;
    }
  }
  
  .isDefault {
    animation-name: change-toggle-right-to-left;
    animation-duration: 0.2s;
    animation-timing-function: ease-out;
    animation-fill-mode: forwards;
  }
  @keyframes change-toggle-right-to-left{
    0% {
      background-color:paleturquoise;
      padding-left: 28px;
    }
    100% {
      background-color: plum;
      padding-left: 2px;
    }    
  }

  #toggle-state-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: white;

    pointer-events: none;
  }


  .close-icon-wrapper{
    padding: 10px;
    border-top-left-radius: 50%;
    border-bottom-left-radius: 50%;
    background-color: white;
    box-shadow: 1px 1px 1px 1px grey;
  }

  #close-modal-icon {
    /* position: absolute;
    top: 200px;
    left: -20px; */
    cursor: pointer;
  }
  
  #area-wrapper {
    position: relative;
    width: 90%;
    height: 100%;
    background-color: white;
    box-shadow: 1px 1px 2px 1px rgba(130, 130, 130, 0.6);

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    flex-flow: column;
  }

  #drop-zone {
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background-color: blue;
  }

  .show {
    z-index: 3;
    opacity: 0.3;
  }
  .hidden {
    z-index: -3;
  }

  #contents-wrapper {
    z-index: 2;
    width: 100%;
    height: auto;
    padding: 10px;
  
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

  }


  #upload-input-wrapper {
    width: 100%;
    height: 50px;
    margin-bottom: 5px;
    padding: 0 10px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
  }

  #loading-display-wrapper {
    display: flex;
    align-items: center;
    margin-left: 10px;
  }

  .loading-message {
    font-size: 0.5rem;
    margin-bottom: 0;
  }

  #uploading-dot {
    margin-left: 3px;
    width: 2px;
    height: 2px;
    border-radius: 50%;
    /* background-color: black; */
  }

  .copy-to-right {
    animation-name: dot-copy-to-right;
    animation-duration: 3s;
    animation-timing-function: steps(3, start);
    animation-iteration-count: infinite;
  }
  @keyframes dot-copy-to-right {
    /* ドットを右にコピーして増やしていく(影でコピーを表現) */
    33%   {box-shadow: 5px 0 0 0 black}
    66%   {box-shadow: 10px 0 0 0 black}
    100%  {box-shadow: 15px 0 0 0 black,16px 0 0 0 black;}
  }

  #loading-icon {
    width: 20px; 
    height: 20px;
    margin-right: 10px;
    background: linear-gradient(#05FBFF, #FF33aa);
    border-radius: 50%;
  }

  .rotate {
    animation: rotate-anime 2s linear infinite;
  }
  @keyframes rotate-anime {
    0%  {transform: rotate(0);}
    100%  {transform: rotate(360deg);}
  }

  #upload-label {
    padding: 5px 30px;
    background-color: rgba(100, 200, 250, 0.4);
    border-radius: 20px;
    margin-bottom: 0;
  }
  #upload-label:hover {
    cursor: pointer;
    background-color: rgba(100, 200, 250, 0.8);
  }

  #audio-thumbnail-wrapper {
    /* モーダル内のオーディオサムネの配置 */
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    align-items: center;
    justify-content: space-between;

    width: 92%;
    height: 80vh;
    /* margin-top: 20px; */
    padding-left: 0;
    overflow-y: scroll;
  }

  .audio-list {
    position: relative;
    width: 100%;
    height: 30px;
    margin-bottom: 2px;
    border-radius: 5px;
    list-style: none;
    transition: transform 0.3s;
    /* background-color: grey; */

    display: flex;
    flex-direction: row;
    align-items: center;
  }

  .audio-play-icon,
  .audio-pause-icon {
    position: absolute;
    z-index: -1;
    color: rgba(255, 0, 0, 0);
  }

  .audio-play-icon {
    top: 0;
    left: 12px;
  }

  .audio-pause-icon {
    top: 8px;
    left: 7px;
  }

  .now-play {
    color : rgb(0, 255, 0);
  }

  .delete-audio {
    position: absolute;
    top: 0;
    right: 0;
    margin-right: 5px;
    color: rgba(180, 50, 50, 0);
    z-index: -1;
  }

  .audio-list:hover {
    cursor: pointer;
    transform: scale(0.98,0.98);
  }

  .audio-list:hover .delete-audio {
    z-index: 2;
    color: rgba(180, 50, 50, 0.4);
  }

  .audio-list:hover .audio-play-icon {
    z-index: 2;
    color: rgba(0, 255, 0, 0.8);
  }

  .audio-list:hover .audio-pause-icon {
    z-index: 2;
    color: rgba(0, 255, 0, 0.8);
  }

  .audio-list:hover .audio-thumbnail {
    z-index: -1;
    opacity: 0.3;
  }


  .audio_name {
    margin-left: 7px;
    white-space: nowrap;
  }

  .audio_name:hover {
    text-decoration: underline;
  }


  .icon-cover {
    position: absolute ;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 100%;
    background-color: rgba(130, 130, 130, 0);

    /* 要素の配置 */
    display: flex;
    align-items: center;
    justify-content: center;

  }

  #add-audio-thumbnail-icon {
    color: rgba(255, 255, 255, 0.7);
    pointer-events: none;
  }


  .audio-thumbnail {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: darkgray;
  }



  /* アニメーション */

  /* .right-slide-enter-to, .right-slide-leave {
    transform: translate(0px, 0px);
  } */

  .right-slide-enter-active, .right-slide-leave-active {
    transform: translate(0px, 0px);
    transition: all 500ms
    /* cubic-bezier(0, 0, 0.2, 1) 0ms; */
  }

  .right-slide-enter, .right-slide-leave-to {
    transform: translateX(100vw) 
  }

</style>