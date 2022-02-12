<template>
  <div id="disp-media-like-modal-wrapper" class="icon-wrapper"
   v-if="!isMyMedia">
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
    watch :{},
    methods : {
      judgeIsMyMedia(){
        this.isMyMedia = this.getIsLogin && this.getIsMyMedia ? true:false
      },
    },
    created(){
      document.addEventListener('setIsMyMedia',this.judgeIsMyMedia,false);
    }
  }

</script>

<style scoped>
@import "/resources/css/dispIconCommon.css";

  .hidden {
    display: none;
  }

</style>