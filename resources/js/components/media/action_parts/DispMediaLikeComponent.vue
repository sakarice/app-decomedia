<template>
  <div id="disp-media-like-modal-wrapper" class="icon-wrapper" v-if="isShowMediaLike">
    <like-media-component></like-media-component>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import LikeMedia from './../../LikeMediaComponent.vue';

  export default {
    components : {
      LikeMedia,
    },
    data : () => { return { } },
    computed : {
      ...mapGetters('loginState', ['getIsLogin']),
      ...mapGetters('media', ['getIsMyMedia']),
      isShowMediaLike : function(){
        if(this.getIsLogin && !(this.getIsMyMedia)){ return true }
      }
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