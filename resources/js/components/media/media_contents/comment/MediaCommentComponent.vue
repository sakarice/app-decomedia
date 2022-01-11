<template>
  <div :id="idWithIndex" class="flex mb10">
    <!-- 左ボックス：ユーザアイコン -->
    <div class="user-icon-area flex column a-center">
      <div class="user-icon-wrapper p5">
        <img class="w30px h30px border-r-50per" :src="comment['profile_img_url']" alt="unknown user">
      </div>
      <span class="user-name font-12 grey">{{comment['user_name']}}</span>
    </div>
    <!-- 右ボックス：コメントとオプション -->
    <div class="comment-area flex column w80 ml10 ">
      <p class="comment-text w100 mt5 mb3" :class="{'hide-detail':hideDetail}">
        {{comment['comment']}}
      </p>
      <div class="options-wrapper flex a-center font-13 grey">
        <span class="hover-p mr20" @click="hideDetail=!hideDetail">{{detail_toggle_text}}</span>
        <span v-if="comment['is_my_comment']" class="hover-p hover-color-red" @click="deleteConfirm">削除</span>
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
      myElem : "",
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
    idWithIndex:function(){return 'media-comment-wrapper'+this.index;},
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
    setMyElem(){this.myElem = document.getElementById(this.idWithIndex);},
    init(){
      this.comment = this.getOneComment();
      this.hideDetail = true;
      this.setMyElem();
    },
    deleteConfirm(){
      this.myElem.addEventListener('deleteComment', this.deleteComment, false);
      const event = new CustomEvent('showCommentDeleteConfirm',{detail:{elem:this.myElem}});
      document.body.dispatchEvent(event);
    },
    deleteComment(){
      this.myElem.removeEventListener('deleteComment', this.deleteComment, false);
      const media_id = this.getMediaSetting['id'];
      const data = { "comment" : this.comment };
      const url = '/media/' + media_id + '/comment/delete';
      axios.post(url, data)
      .then(res=>{
        console.log('delete success')
        this.deleteMediaCommentsObjectItem(this.index);
        const event = new CustomEvent('commentDeleted');
        document.body.dispatchEvent(event);
      })
      .catch(error=>{
        console.log(error.message)
      })
    },
  },
  created(){
    this.init();
  },
  mounted(){
    this.setMyElem();
  }

}

</script>

<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.comment-text {
  overflow-wrap: break-word;
  overflow: hidden;
}
.grey { color: darkgrey}
.hide-detail { max-height: 60px;}
.hover-color-red:hover{ color: red;}

</style>