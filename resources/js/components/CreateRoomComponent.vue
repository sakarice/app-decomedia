<template>
  <div id="field" v-on:click.self="closeModal()">
    <div id="room-img-frame" v-on:click="showImgModal()">
      <p v-show="!(roomImgUrl)">画像を選択</p>
      <img id="room-img" :src="roomImgUrl" v-show="roomImgUrl" alt="画像が選択されていません">
    </div>
    <div id="room-audio-frame" v-on:click="showAudioModal()">

      <ul id="audios">
        <li class="audio-wrapper" :id="index" v-for="(roomAudio, index) in roomAudios" :key="roomAudio.id">
          <img class="room-audio-thumbnail" src="" v-show="roomAudio" :alt="index">
        </li>
        <li class="audio-wrapper-copy" v-for="n in 5" :key="n" v-show="!(roomAudios[n-1])">
        </li>
      </ul>
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
      let audioNum = this.roomAudios.length;
      // オーディオは1ルームに5つまで。
      // 既に5つある場合は一つ消してから追加。
      if(audioNum == this.maxAudioNum){
        this.roomAudios.splice(0, 1);
      }
      this.roomAudios.push(audio);

      // オーディオの更新
      this.$nextTick(function () { // DOMの更新を待つ
        let audioDoms = document.getElementsByClassName('audio-wrapper');
        let audioNum = audioDoms.length;
        for(let i = 0; i < audioNum; i++){
          // オーディオのサムネイル表示&更新
          let audioThumbnail = audioDoms[i].firstChild;
          let targetAudio = this.roomAudios[i];
          audioThumbnail.setAttribute('src', targetAudio['thumbnail_url']);


        }
      });

      // // オーディオプレイヤーの作成
      // let audioplayer = new Audio();
      // audioplayer.src = ""
    },
    judgeDelAudio(url) {
      if(this.roomAudioUrl == url){
        this.roomAudioUrl = "";
        this.roomAudioThumbnailUrl = "";
      }
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
.audio-wrapper-copy {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  /* background-color: cornflowerblue; */
  border: 1px dashed;

  margin: 20px 10px;

  display: flex;
  justify-content: center;
  align-items: center;
}

.room-audio-thumbnail {
  width: 70px;
  height: 70px;
  border-radius: 50%;
}

</style>