<template>
  <div id="field" v-on:click.self="closeModal()">
    <div class="room-img" v-on:click="showModal()"><p>画像を選択</p> </div>
    <img-select-component v-show="isShowModal" v-on:from-child="closeModal"></img-select-component>
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
      isShowModal : false
    }
  },
  methods : {
    showModal() {
      this.isShowModal = true;
    },
    closeModal() {
      this.isShowModal = false;
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
    background-color:aquamarine; 

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .room-img {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 350px;
    height: 300px;
    border: 2px;
    border-style: dotted;
    border-color: cadetblue;
  }

</style>