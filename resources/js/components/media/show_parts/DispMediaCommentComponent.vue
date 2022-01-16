<template>
  <div id="disp-media-comment-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaComment')">
    <i class="fas fa-comment fa-lg"></i>
    <div class="comment-num-wrapper" v-show="comment_num">
      <span class="font-11">{{comment_num}}</span>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex';

  export default {
    components : {},
    computed : {
      ...mapGetters('mediaComments',['getMediaComments']),
      comment_num:function(){ return this.getMediaComments.length },
    },
    methods : {
      showModal(target){
        this.$emit('show-modal',target);
      }
    }
  }

</script>

<style scoped>

  .icon-wrapper {
    position: relative;
    padding: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  #disp-media-comment-modal-wrapper {
    color: white;
  }

  .comment-num-wrapper {
    position: absolute;
    bottom: 0;
    right: 8px;
    color: lightgreen;
  }
  
</style>