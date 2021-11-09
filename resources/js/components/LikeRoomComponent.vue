<template>
  <div class="like-icon-wrapper">
    <i v-show="!(isLikeRoom)" v-on:click="changeLikeStateOfViewAndDB()" class="far fa-heart fa-lg icon"></i>
    <i v-show="isLikeRoom" v-on:click="changeLikeStateOfViewAndDB()" class="fas fa-heart fa-lg icon"></i>
  </div>
</template>


<script>
export default{
  props: [
    'roomId',
  ],
  data : () => {
    return {
      isLikeRoom : false,
    }
  },
  methods : {
    getLikeState(){
      let url = '/user/likeState/'+this.roomId;
      axios.get(url)
      .then(response => {
        this.isLikeRoom = response.data.isLikeRoom;
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
        this.isLikeRoom = !(this.isLikeRoom);
    },
    updateLikeStateInDB(){
      let url = '/room/like';
      const room_id = this.$parent.mediaSetting['id'];
      let data = {
        'isLike' : this.isLikeRoom,
        'room_id' : room_id,
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
  },
  watch : {
    roomId: function(newVal,oldVal){ // 親コンポーネントのroomOwnerIdがdataにセットされるのを待つ
      if(newVal > 0){
        this.getLikeState();
      }
    }
  },  

}

</script>

<style>

@import "../../css/roomEditModals.css";

.icon {
  color: pink;
}


</style>