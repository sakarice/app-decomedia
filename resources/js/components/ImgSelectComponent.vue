<template>
  <transition name="right-slide">
    <div id="content" v-show="showModal">
      <p>これがモーダルウィンドウです</p>
      <button v-on:click="popMessage()">{{popMessage}}</button>
      <button v-on:click="clickEvent()">close</button>
    </div>
  </transition>
</template>

<script>
export default {
  data : () => {
    return {
      popMessage : 'メッセージです'
    }
  },
  methods : {
    clickEvent() {
      this.$emit('from-child');
      // showModal = false;
    },
    popMessage() {
      alert("aaaa");
    }
  }

}
</script>

<style>
  #content {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 2;
    width: 40%;
    height: 100%;
    padding :1em;
    background-color:antiquewhite; 

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    justify-content: center;
  }

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