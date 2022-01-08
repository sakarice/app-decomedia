<template>
  <div id="disp-media-like-modal-wrapper" class="icon-wrapper" v-if="!(isMyMedia)">
    <like-media-component></like-media-component>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import LikeMedia from './LikeMediaComponent.vue';

  export default {
    components : {
      LikeMedia,
    },
    data : () => {
      return {
        isMyMedia : true,
      }
    },
    computed : {
      ...mapGetters('loginState', ['getIsLogin']),
      ...mapGetters('media', ['getIsMyMedia']),
    },
    watch :{
      getIsMyMedia:function(){
        this.judgeIsMyMedia();
      }
    },
    methods : {
      judgeIsMyMedia(){
        this.isMyMedia = this.getIsLogin && this.getIsMyMedia ? true:false
      },
    },
  }

</script>

<style scoped>

  .icon-wrapper {
    padding: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>