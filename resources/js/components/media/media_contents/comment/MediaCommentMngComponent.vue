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
      <comment-delete-confirm></comment-delete-confirm>

      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>


<script>
import { mapGetters, mapMutations } from 'vuex';
import closeModalBar from '../../change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../../change_display_parts/CloseModalIconComponent.vue'

import mediaComment from './MediaCommentComponent.vue';
import mediaCommentAdd from './MediaCommentAddComponent.vue';
import commentDeleteConfirm from './CommentDeleteConfirmComponent.vue';

export default{
  components : {
    closeModalBar,
    closeModalIcon,
    mediaComment,
    mediaCommentAdd,
    commentDeleteConfirm,
  },
  props: [ 'transitionName' ],
  data : () => {
    return {

    }
  },
  computed : {
    ...mapGetters('mediaSetting',['getIsInitializedSetting']),
    ...mapGetters('mediaSetting',['getMediaSetting']),
    ...mapGetters('mediaComments',['getMediaComments']),
  },
  watch : {},
  methods : {
    ...mapMutations('mediaComments', ['addMediaCommentsObjectItem']),
    ...mapMutations('mediaComments',['updateIsInitializedComments']),

    initComment(){
      this.getCommentDataFromDb()
      .then(comments=>{
        this.setCommentDataToStore(comments);
      })
      document.removeEventListener('initMediaSettingFinish',this.initComment,false);
    },
    getCommentDataFromDb(){
      return new Promise((resolve, reject)=>{
        const media_id = this.getMediaSetting['id'];
        const url = '/media/' + media_id + '/comment';
        axios.get(url)
        .then(res=>{
          const comments = res.data.comments;
          return resolve(comments);
        })
        .catch(error=>{
          console.log('getCommentDataFromDb failed!')
        });
      })
    },
    setCommentDataToStore(comments){
      comments.forEach((comment)=>{
        this.addMediaCommentsObjectItem(comment);
      })
      this.updateIsInitializedComments(true);
    },

  },
  created(){
    document.body.addEventListener('initMediaSettingFinish',this.initComment,false);
  },
  mounted() {},

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