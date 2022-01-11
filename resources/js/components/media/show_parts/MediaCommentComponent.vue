<template>
  <div class="media-comment-wrapper flex mb10">
    <!-- 左ボックス：ユーザアイコン -->
    <div class="user-icon-area flex column a-center">
      <div class="user-icon-wrapper p5">
        <img class="w30px h30px border-r-50per" :src="comment['profile_img_url']" alt="unknown user">
      </div>
      <span class="user-name font-12 grey">{{comment['user_name']}}</span>
    </div>
    <!-- 右ボックス：コメントとオプション -->
    <div class="comment-area flex column w80 ml10 ">
      <p class="comment w100 mt5 mb3" :class="{'hide-detail':hideDetail}">
        {{comment['comment']}}
      </p>
      <div class="options-wrapper flex a-center font-13 grey">
        <span class="hover-p mr20" @click="hideDetail=!hideDetail">{{detail_toggle_text}}</span>
        <span v-if="comment['is_my_comment']" class="hover-p" @click="deleteComment">削除</span>
      </div>
    </div>
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
      hideDetail : true,
      comment : "",
      // id : 0,
      // media_id : 0,
      // user_name : "",
      // user_icon_url : "",
      // comment : "",
      // good : 0,
      // created_at : 0,
      // updated_at : 0,
      // is_my_comment : false,
    }
  },
  computed : {
    ...mapGetters('mediaSetting',['getMediaSetting']),
    ...mapGetters('mediaComments',['getMediaComment']),
    ...mapGetters('mediaComments',['getMediaComments']),
    detail_toggle_text:function(){
      return this.hideDetail ? "全て表示" : "一部を表示";
    }
  },
  methods : {
    ...mapMutations('mediaComments', ['addMediaCommentsObjectItem']),
    ...mapMutations('mediaComments', ['setTargetObjectIndex']),
    ...mapMutations('mediaComments', ['deleteMediaCommentsObjectItem']),
    
    getOneComment(){
      this.setTargetObjectIndex(this.index);
      return this.getMediaComment;
    },
    init(){ this.comment = this.getOneComment(); },
    deleteComment(){
      const media_id = this.getMediaSetting['id'];
      // const comment_id = this.comment['id'];
      const data = { "comment" : this.comment };
      const url = '/media/' + media_id + '/comment/delete';
      axios.post(url, data)
      .then(res=>{
        console.log('delete success')
        this.deleteMediaCommentsObjectItem(this.index);
      })
      .catch(error=>{
        console.log(error.message)
      })
    },
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
.grey { color: darkgrey}

.comment { overflow: hidden }
.hide-detail { max-height: 60px;}

</style>