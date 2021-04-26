<template>
  <div id="field" v-on:click.self="closeModal()">
    <div id="room-img-frame" v-on:click="showModal()">
      <p v-show="!(roomImgUrl)">画像を選択</p>
      <img id="room-img" :src="roomImgUrl" v-show="roomImgUrl" alt="画像が選択されていません">
    </div>
    <img-select-component 
    v-show="isShowModal" 
    v-on:close-modal="closeModal" 
    v-on:set-img-url="setRoomImgUrl"
    v-on:img-del-notice="judgeDelImg">
    </img-select-component>
  </div>
</template>

<script>
import ImgSelect from './ImgSelectComponent.vue';
export default {
  components : {
    ImgSelect
  },
  data : () => {
    return {
      button_text : "画像選択",
      isShowModal : false,
      roomImgUrl : ""
    }
  },
  methods : {
    showModal() {
      this.isShowModal = true;
    },
    closeModal() {
      this.isShowModal = false;
    },
    setRoomImgUrl(url) {
      this.roomImgUrl = url;
    },
    judgeDelImg(url) {
      if(this.roomImgUrl == url){
        this.roomImgUrl = "";
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
  }

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

</style>