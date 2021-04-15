<template>
  <div id="test">
    <!-- <button class="button" v-on:click="showModal()">{{button_text}}</button> -->
  </div>
</template>

<script>
  export default {
    data: () => {
      return {
        message : 'メッセージです',
        button_text : '画像を選択します',
      }
    },
    methods: {
      openModal() {
        this.showContent = true;
      },
      closeModal() {
        this.showContent = false;
      }
    }
  }
</script>

<style>
  .button {
    background-color: black;
    color : blue;
  }

</style>