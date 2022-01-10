<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper" class="comment-area flex">
        
        <h2 class="sub-title w90 font-15 mt15 mb15">コメント</h2>
        <div id="media-comments-wrapper" class="modal-wrapper">
          <media-comment v-for="(comment, index) in getMediaComments" :key="index"
          :index="index" ref="comments">
          </media-comment>
        </div>
        
      </div>
      <media-comment-add></media-comment-add>
      
      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>


<script>
import { mapGetters, mapMutations } from 'vuex';
import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../change_display_parts/CloseModalIconComponent.vue'

import mediaComment from './MediaCommentComponent.vue';
import mediaCommentAdd from './MediaCommentAddComponent.vue'

export default{
  components : {
    closeModalBar,
    closeModalIcon,
    mediaComment,
    mediaCommentAdd,
  },
  props: [ 'transitionName' ],
  data : () => {
    return {

    }
  },
  computed : {
    ...mapGetters('mediaSetting',['getMediaSetting']),
    ...mapGetters('mediaComments',['getMediaComments']),
  },
  methods : {
    ...mapMutations('mediaComments', ['addMediaCommentsObjectItem']),
    setDbDataToStore(){
      const media_id = this.getMediaSetting['id'];
      const url = '/media/' + media_id + '/comments';
      axios.get(url)
      .then(datas=>{
        datas.forEach(data=>{
          this.addMediaCommentsObjectItem(data);
          this.updateIsInitializedComments(true);
        })
      })
    },
  },
  mounted : function() {},

}

</script>

<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/FrequentlyUseStyle.css";

#media-comments-wrapper{
  width: 95%;
  overflow-y: scroll;
}

.comment-area {
  background-color: rgba(35,40,50,0.95);
}

.sub-title {
  font-family: monospace;
  text-align: center;
}

@media screen and (min-width:481px) {
  #media-comments-wrapper{height: 80%}  
}

@media screen and (max-width:480px) {
  #media-comments-wrapper{height: 65%}  
}


</style>