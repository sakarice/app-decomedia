<template>
  <div class="media-comment-add-wrapper flex hover-p">
    <!-- <div class="left-box w70px for-pc"></div> -->
    <div class="w100 flex j-center">
      <div class="comment-input-wrapper w100 pt25 pb10 flex column j-center a-center border-r-3"
      v-show="isShowCommentInput">
        <textarea name="" id="" cols="30" rows="3" class="mb10" maxlength="300"
        v-model="comment_text"></textarea>
        <div class="button-wrapper flex">
          <button class="button cancel-btn mr5" @click="hideCommentInput">キャンセル</button>
          <button class="button submit-btn ml5" @click="addCommentToStore">確定</button>
        </div>
      </div>
      <div class="add-icon-wrapper w90 mb10 border-r-3 flex j-center a-center"
      v-show="!isShowCommentInput" @click="showCommentInput">
        <i class="fas fa-plus fa-2x lightgreen"></i>
      </div>
    </div>

  </div>
</template>


<script>
import { mapGetters, mapMutations } from 'vuex';

export default{
  props: [],
  data : () => {
    return {
      isShowCommentInput : false,
      user_info : {
        user_id : 0,
        user_name : "testUser",
        // user_profile_icon_url : "https://app-decomedia-production.s3.ap-northeast-1.amazonaws.com/app-decomedia/user-solid.svg",
        user_profile_icon_url : "https://cdn.pixabay.com/photo/2021/11/21/22/08/british-shorthair-6815375_1280.jpg",
      },
      comment_text : "comment",
      comment : {
        id : 0,
        // media_id : 0,
        created_at : "",
        updated_at : "",
      },
    }
  },
  computed : {
    ...mapGetters('mediaSetting',['getMediaSetting']),
    ...mapGetters('mediaComments',['getMediaComments']),
    comment_data:function(){
      let comment_datas = Object.assign(this.comment, this.user_info);
      comment_datas['comment'] = this.comment_text;
      comment_datas['media_id'] = this.getMediaSetting['id'];
      return comment_datas;
    }
  },
  methods : {
    ...mapMutations('mediaComments', ['addMediaCommentsObjectItem']),
    getUserInfo(){
      const url = '/user/info';
      axios.get(url)
      .then(response => {
        const userInfos = response.data.userInfo;
        Object.keys(userInfos).forEach((key)=>{
          this.user_info[key] = userInfos[key]; 
        })
      })
      .catch(error => {})
    },
    storeCommentInDb(){
      const url = '/media/comment/store/'
      axios.post(url, this.comment_data)
      .then(res => {
        console.log('success add comment!');
        const storeDatas = response.data.storeDatas;
        Object.keys(storeDatas).forEach((key)=>{
          this.comment[key] = storeDatas[key]; 
          this.addCommentDataToStore();
        })
      })
      .catch(error=>{
        console.log('failed add comment!');
      })
    },
    showCommentInput(){this.isShowCommentInput = true;},
    hideCommentInput(){this.isShowCommentInput = false;},    
    addCommentToStore(){
      this.addMediaCommentsObjectItem(this.comment_data);
      this.comment_text = "";
      this.hideCommentInput();
    }

  },
  created : function(){},

}

</script>

<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.add-icon-wrapper{
  background-color: rgba(112,128,144,0.2)
}
.add-icon-wrapper:hover{
  background-color: rgba(112,128,144,0.4);
}

.media-comment-add-wrapper {
  position: absolute;
  bottom: 0;
  width: 95%;
}

.comment-input-wrapper {
  background-color:rgba(32,178,170,0.9);
}

.lightgreen { color: lightgreen }

@media screen and (max-width:480px) {
  .comment-input-wrapper {
    height: 180px;
  }

  
}


</style>