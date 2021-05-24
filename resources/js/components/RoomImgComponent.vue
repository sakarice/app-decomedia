<template>
  <!-- <transition name="right-slide"> -->
    <!-- Room画像-->
  <div id="room-img-wrapper">

    <div id="room-img-frame" v-on:click="$emit('parent-action')" v-show="!(isShowYoutube)">
      <p v-show="!(roomImgUrl)"></p>
      <img id="room-img" :src="roomImgUrl" v-show="roomImgUrl" alt="画像が選択されていません">
    </div>

  </div>
  <!-- </transition> -->
</template>

<script>
  export default {
    props : ['roomImgUrl', 'isShowYoutube'],

    data : () => {
      return {}
    },
    methods : {},

  }

</script>



<style>
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

</style>