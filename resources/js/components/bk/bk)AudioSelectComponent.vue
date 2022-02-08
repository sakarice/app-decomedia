<template>
  <transition :name="transitionName">
    <div id="select-modal" @click.stop @touchstart.stop>
      <div id="area-wrapper">
        <div id="drop-zone" 
        :class="{show: isDragEnter, hidden: !(isDragEnter)}"
        @dragleave = "dragLeave"
        @dragover.prevent
        @drop.prevent = "dropFile">
        </div>
        <div id="contents-wrapper" class="contents-audio"
        @dragenter = "dragEnter">
          <div id="toggle-wrapper">
            <div id="category-type">
              <span @click="changeFileCategory"
              class="category category-default"
               :class="{active_category: isDefault}">
               default
               </span>
              <span @click="changeFileCategory"
              class="category category-upload"
               :class="{active_category: !isDefault}">
               upload
               </span>
            </div>
          </div>

          <!-- アップロードエリア -->
          <div id="upload-input-wrapper">
            <label id="upload-label" for="upload-audio-input" tabindex=2 @keydown.enter="startInput" v-show="!(isDefault)">
              <i class="fas fa-upload" style="margin-right: 5px"></i>
              <span class="upload-label-text"></span>
              <input id="upload-audio-input" style="display: none" @change="selectedFile" type="file" accept="audio/*" name="audio">
            </label>
            <div id="loading-display-wrapper" v-show="isLoading">
              <p class="loading-message">{{loadingMessage}}</p>
              <div id="uploading-dot" :class="{'copy-to-right': isLoading}"></div>
            </div>
          </div>

          <!-- オーディオのカテゴリ -->
          <ul class="audio-category-wrapper">
            <li @click="changeAudioCategory('all')"
            class="audio-category" :class="{'active-audio-category':selectedAudioCategory=='all'}">
              <span>all</span>
            </li>
            <li @click="changeAudioCategory(category)" v-for="category in audioCategory" :key="category.id"
            class="audio-category" :class="{'active-audio-category':(category==selectedAudioCategory)}">
              <span>{{category}}</span>
            </li>
          </ul>

          <!-- オーディオのリスト表示 -->
          <ul id="audio-thumbnail-wrapper">
            <!-- uploads -->
            <li v-show="!(isDefault)" :id="index" class="audio-list" v-for="(userOwnAudio, index) in userOwnAudios" :key="userOwnAudio.id">
              <img class="audio-thumbnail" :src="userOwnAudio['thumbnail_url']" :alt="userOwnAudio['thumbnail_url']">
              <span class="audio-name" :class="{'now-play' : userOwnAudio['isPlay']}" v-on:click="addAudioToMedia('user-own', index)">
                {{userOwnAudio['name']}}
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
            <li class="audio-list" v-for="(defaultAudio, index) in defaultAudios" :key="defaultAudio.id"
            v-show="isDefault && (defaultAudio['category']==selectedAudioCategory || selectedAudioCategory=='all')">
              <img class="audio-thumbnail" :src="defaultAudio['thumbnail_url']" :alt="defaultAudio['thumbnail_url']">
              <span class="audio-name" :class="{'now-play' : defaultAudio['isPlay']}" v-on:click="addAudioToMedia('default', index)">
                {{defaultAudio['name']}}
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

      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import closeModalBar from '../media/change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../media/change_display_parts/CloseModalIconComponent.vue'

export default {
  components : {
    closeModalBar,
    closeModalIcon,
  },
  props : ['transitionName'],
  data : () => {
    return {
      popMessage : 'メッセージです',
      isDefault : true,
      audioCategory : [],
      selectedAudioCategory : "all",
      fileCategory : "default",
      isDragEnter : false,
      uploadFile : "",
      isLoading : false,
      loadingMessage : "",
      userOwnAudios : [],   // thumbnail_url, audio-name
      defaultAudios : [],
      audioPlayer : new Audio(),
      ctxs : [],
      audioInputNodes : [],
      panner : "",
      playAudioType : "",
      playAudioIndex : -1,
      playAudioUrl : "",
      isPlay : false,
      // userOwnAudioThumbnailUrls : [],
      // defaultAudioThumbnailUrls : []
    }
  },
  computed : {
    ...mapGetters('stereoPhonicArrangeDefault', ['getStereoPhonicArrangeDefault']),
  },
  methods : {
    ...mapMutations('mediaAudios', ['deleteMediaAudiosObjectItem']),
    ...mapMutations('mediaAudios', ['addMediaAudiosObjectItem']),
    finishPlay(){
      let audioDuration = this.audioPlayer.duration;
      this.audioPlayer.currentTime = audioDuration - 1;
    },
    changeFileCategory(){
      this.isDefault = !(this.isDefault);
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
          alert('オーディオ取得失敗');
        })
    },
    getPublicAudios(){
      const url = '/ajax/getPublicAudios';
      axios.get(url)
        .then(response => {
          response.data.audios.forEach(audio => {
            audio['isPlay'] = false;
            this.defaultAudios.unshift(audio);
          });
        })
        .catch(error => {
          alert('オーディオ取得失敗');
        })
    },
    getAudioCategory(){
      const url = '/audioCategory';
      axios.get(url)
      .then(res=>{
        res.data.category.forEach(category=>{
          this.audioCategory.push(category);
        })
      })
    },
    changeAudioCategory(category){
      this.selectedAudioCategory = category;
    },
    playAudio : function(type, index){
      // 選択したオーディオを再生
      let playTargetAudio;
      if(type == 'user-own'){
        playTargetAudio = this.userOwnAudios[index];
      } else if (type == 'default'){
        playTargetAudio = this.defaultAudios[index];
      }

      this.isPlay = true;
      playTargetAudio['isPlay'] = true;

      // audioエレメントを初期化
      this.audioPlayer = new Audio(playTargetAudio['audio_url']);
      // クロスオリジン設定をリクエストヘッダにを付与
      this.audioPlayer.crossOrigin = "anonymous";
      this.audioPlayer.onloadstart = this.setUpWebAudio();

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
    },

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
    addAudioToMedia : function(type, index) {
      // こちらのコンポーネントの配列をそのまま渡すと、
      // 参照渡しとなり、コンポーネント間で配列が同期され予期せぬ同差をしてしまう
      // 配列の内容だけを、新しい配列にコピーして、media側に渡す。
      let tmpAudio;
      let audio_type;
      if(type == 'user-own'){
        tmpAudio = this.userOwnAudios[index];
        audio_type = 2;
      } else if (type == 'default'){
        tmpAudio = this.defaultAudios[index];
        audio_type = 1;
      }

      // 新しい連想配列を用意
      let audio = {};
      audio['type'] = audio_type;
      audio['name'] = tmpAudio['name'];
      audio['audio_url'] = tmpAudio['audio_url'];
      audio['thumbnail_url'] = tmpAudio['thumbnail_url'];
      audio['isLoop'] = false;
      audio['duration'] = 0;
      audio['volume'] = 0.5;

      // 立体音響用のデフォルト設定を追加する
      const stereoSetting = this.getStereoPhonicArrangeDefault;
      Object.assign(audio, stereoSetting);
      
      this.addMediaAudiosObjectItem(audio);
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
          // alert(this.userOwnAudios['audio-name']);
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
      let audioId = event.target.parentNode.getAttribute('id');
      let audioUrl = this.userOwnAudios[audioId]['audio_url'];
      const params = {
        'audioUrl' : audioUrl
      }
      this.loadingMessage = '削除中'
      this.isLoading = true;
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
        })
        .catch(error => {
          alert('オーディオ削除失敗');
          this.loadingMessage = '';
          this.isLoading = false;

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
    setAudioCtx(){
      const AudioContext = window.AudioContext || window.webkitAudioContext;
      return new AudioContext();
    },
    setUpWebAudio(){
      this.ctxs.forEach((ctx)=>{
        ctx.close();
      })
      this.ctxs.length = 0;
      this.audioInputNodes.length = 0;

      this.ctxs.push(this.setAudioCtx());
      this.audioInputNodes.push(this.ctxs[this.ctxs.length-1].createMediaElementSource(this.audioPlayer));

      for(let i=0; i<this.ctxs.length; i++){
        this.audioInputNodes[i].connect(this.ctxs[i].destination);
      }

      if(this.isPlay==true){
        this.audioPlayer.play();
      }
    },

  },
  created(){
    this.getUserOwnAudios();
    this.getPublicAudios();
    this.getAudioCategory();

  },
  mounted(){
    let audio = this.audioPlayer;
    audio.onended = this.finishAudio.bind(this);
  },

}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
  
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

  .audio-category-wrapper {
    width: 85%;
    display: flex;
    padding: 5px 0px;
    overflow-x: scroll;
  }

  .audio-category {
    padding: 12px 8px;
    margin: 0 5px;
    white-space: pre;
    border-radius: 15px;
    background-color: white;
    color : black;
    list-style: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .audio-category:hover {
    cursor: pointer;
    outline: 1.5px solid black;
  }

  .active-audio-category{
    color: white;
    background-color: black;
  }

  .audio-list {
    position: relative;
    width: 100%;
    height: 40px;
    margin-bottom: 5px;
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
    top: 5;
    left: 12px;
  }

  .audio-pause-icon {
    top: 13px;
    left: 7px;
  }

  .now-play {
    color : rgb(0, 255, 0);
  }

  .delete-audio {
    position: absolute;
    top: 4px;
    right: 0;
    margin-right: 5px;
    color: rgba(180, 50, 50, 0);
    z-index: -1;
  }

  .audio-list:hover {
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


  .audio-name {
    width: 80%;
    margin-left: 10px;
    font-size: 11px;
    white-space: nowrap;
  }

  .audio-name:hover {
    cursor: pointer;
    text-decoration: underline;
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

  .contents-audio {
    padding: 15px 0 15px 0;
  }

  .upload-label-text::after {
    content: "追加";
    font-size: 12px;
  }

  @media screen and (max-width: 480px) {
    #audio-thumbnail-wrapper {
      width: 80%;
    }

    .audio-category-wrapper {
      margin: 0;
      padding: 0;
    }

  }


</style>