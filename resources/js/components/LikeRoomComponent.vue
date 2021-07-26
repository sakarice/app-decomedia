<template>
  <div class="like-icon-wrapper">
    <i v-show="!(isLikeRoom)" v-on:click="changeLikeStateOfViewAndDB()" class="far fa-heart fa-lg icon"></i>
    <i v-show="isLikeRoom" v-on:click="changeLikeStateOfViewAndDB()" class="fas fa-heart fa-lg icon"></i>
  </div>
</template>


<script>
export default{
  props: [
  ],
  data : () => {
    return {
      isLikeRoom : false,
    }
  },
  methods : {
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
      if(this.isLikeRoom){
        this.isLikeRoom = false;
      } else if(!this.isLikeRoom){
        this.isLikeRoom = true;
      }
      console.log('changeLikeState');
    },
    updateLikeStateInDB(){
      let url = '/room/like';
      const room_id = this.$parent.roomSetting['id'];
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
  }
  

}

</script>

<style>

@import "../../css/roomEditModals.css";

.icon {
  color: pink;
}


</style>