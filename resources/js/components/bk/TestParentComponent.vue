<template>
  <div id="field" v-on:click.self="closeModal()">
    <!-- <button class="button" v-on:click="showModal()">{{button_text}}</button> -->
    <div class="media-img" v-on:click="showModal()"><p>画像を選択</p> </div>
    <img-select-component v-show="isShowModal" v-on:from-child="closeModal"></img-select-component>
  </div>
</template>

<script>
import ImgSelect from '../media/edit_parts/ImgSelectComponent.vue';
export default {
  components : {
    ImgSelect
  },
  data : () => {
    return {
      message : '親です',
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

  .media-img {
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