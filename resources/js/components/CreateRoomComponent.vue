<template>
  <div id="field" v-on:click.self="closeModal()">
    <div id="room-img-frame" v-on:click="showImgModal()">
      <p v-show="!(roomImgUrl)">画像を選択</p>
      <img id="room-img" :src="roomImgUrl" v-show="roomImgUrl" alt="画像が選択されていません">
    </div>
    <div id="room-audio-frame" v-on:click="showAudioModal()">
      <ul id="audios">
        <li class="audio-wrapper"><img :src="roomAudioThumbnailUrls[0]" v-show="roomAudioThumbnailUrls[0]" alt="1"></li>
        <li class="audio-wrapper"><img :src="roomAudioThumbnailUrls[1]" v-show="roomAudioThumbnailUrls[1]" alt="2"></li>
        <li class="audio-wrapper"><img :src="roomAudioThumbnailUrls[2]" v-show="roomAudioThumbnailUrls[2]" alt="3"></li>
        <li class="audio-wrapper"><img :src="roomAudioThumbnailUrls[3]" v-show="roomAudioThumbnailUrls[3]" alt="4"></li>
        <li class="audio-wrapper"><img :src="roomAudioThumbnailUrls[4]" v-show="roomAudioThumbnailUrls[4]" alt="5"></li>
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
    v-on:set-audio-url="setRoomAudioUrl"
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
      roomAudioUrls : [],
      roomAudioThumbnailUrls : []
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
}

.audio-wrapper {
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

</style>