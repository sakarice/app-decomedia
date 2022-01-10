<template>
  <div class="media-comment-wrapper flex mb10">
    <div class="user-icon-area flex column a-center">
      <div class="user-icon-wrapper p5">
        <!-- <i class="fas fa-user fa-lg"></i> -->
        <img class="w30px h30px border-r-50per" :src="comment['user_profile_icon_url']" alt="unknown user">
      </div>
      <span class="user-name font-12">{{comment['user_name']}}</span>
    </div>
    <p class="comment w80 mt10 mb5 ml10">{{comment['comment']}}</p>
  </div>
</template>


<script>
import { mapGetters, mapMutations } from 'vuex';
export default{
  props: [
    "index",
  ],
  data : () => {
    return {
      comment : ""
      // id : 0,
      // media_id : 0,
      // user_id : 0,
      // user_name : "",
      // user_icon_url : "",
      // comment : "",
      // good : 0,
      // created_at : 0,
      // updated_at : 0,
    }
  },
  computed : {
    ...mapGetters('mediaSetting',['getMediaSetting']),
    ...mapGetters('mediaComments',['getMediaComment']),
    ...mapGetters('mediaComments',['getMediaComments']),
  },
  methods : {
    ...mapMutations('mediaComments', ['addMediaCommentsObjectItem']),
    ...mapMutations('mediaComments', ['setTargetObjectIndex']),
    
    getOneComment(){
      this.setTargetObjectIndex(this.index);
      return this.getMediaComment;
    },
    init(){ this.comment = this.getOneComment(); },
    deleteComment(comment_id){},
  },
  created:function(){
    this.init();
  },

}

</script>

<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.comment {
  overflow-wrap: break-word;
}
.user-name { color: grey}

</style>