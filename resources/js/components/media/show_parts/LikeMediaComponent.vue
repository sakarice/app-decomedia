<template>
  <div class="like-icon-wrapper">
    <i v-show="!(isLikeMedia)" v-on:click="changeLikeStateOfViewAndDB()" class="far fa-heart fa-lg icon"></i>
    <i v-show="isLikeMedia" v-on:click="changeLikeStateOfViewAndDB()" class="fas fa-heart fa-lg icon"></i>
  </div>
</template>


<script>
import { mapGetters } from 'vuex';
export default{
  props: [],
  data : () => {
    return {
      isLikeMedia : false,
    }
  },
  computed : {
    ...mapGetters('mediaSetting',['getMediaSetting']),
  },
  methods : {
    getLikeState(){
      let url = '/user/likeState/'+this.getMediaSetting['id'];
      axios.get(url)
      .then(response => {
        this.isLikeMedia = response.data.isLikeMedia;
      })
      .catch(error => {})
    },
    changeLikeStateOfViewAndDB(){
      // dataのいいね情報を更新が完了してから、DBも更新する
      // 下記Promise内はthisのスコープから外れてしまうため、thisを変数に収める
      let tmpThis = this;

      var firstMethod = new Promise(function(resolve, reject){
        tmpThis.changeLikeState();
        resolve();
      })

      firstMethod.then(function(){
        tmpThis.updateLikeStateInDB();
      })
    },
    changeLikeState() {
        this.isLikeMedia = !(this.isLikeMedia);
    },
    updateLikeStateInDB(){
      let url = '/media/like';
      const media_id = this.getMediaSetting['id'];
      let data = {
        'isLike' : this.isLikeMedia,
        'media_id' : media_id,
      }
      console.log('updateLikeStateInDB');
      axios.post(url, data)
        .then(response => {
          console.log(response.data.msg);
        })
        .catch(error => {
          console.log(response.data.msg);
        })
    },

  },
  mounted : function() {
    this.getLikeState();
  },

}

</script>

<style>

@import "/resources/css/mediaEditModals.css";

.icon {
  color: pink;
}


</style>