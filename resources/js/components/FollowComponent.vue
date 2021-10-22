<template>
  <div class="follow-button-wrapper">
    <button class="follow-button" v-on:click="changeFollowStateOfViewAndDB()">
      {{follow_message}}
    </button>
  </div>
</template>


<script>
export default{
  props: [
    'roomOwnerId',
  ],
  data : () => {
    return {
      isFollow : false,
    }
  },
  methods : {
    getFollowState(){
      const room_owner_id = this.$parent.roomOwnerInfo['id'];
      let url = '/user/followState/'+room_owner_id;
      axios.get(url)
      .then(response => {
        this.isFollow = response.data.isFollow;
      })
      .catch(error => {})
    },
    changeFollowStateOfViewAndDB(){
      // dataのいいね情報を更新が完了してから、DBも更新する
      // 下記Promise内はthisのスコープから外れてしまうため、thisを変数に収める
      let tmpThis = this;

      var firstMethod = new Promise(function(resolve, reject){
        tmpThis.changeFollowState();
        resolve();
      })

      firstMethod.then(function(){
        tmpThis.updateFollowStateInDB();
      })
    },
    changeFollowState() {
      this.isFollow = !(this.isFollow);
    },
    updateFollowStateInDB(){
      let url = '/user/follow';
      const target_user_id = this.$parent.roomOwnerInfo['id'];
      let data = {
        'isFollow' : this.isFollow,
        'target_user_id' : target_user_id,
      }
      console.log('updateFollowStateInDB');
      axios.post(url, data)
        .then(response => {})
        .catch(error => {})
    },

  },
  computed : {
    follow_message : function(){
      if(this.isFollow){
        return "フォロー中"
      } else {
        return "フォローする"
      }
    }
  },
  watch : {
    roomOwnerId: function(newVal,oldVal){ // 親コンポーネントのroomOwnerIdがdataにセットされるのを待つ
      if(newVal > 0){
        this.getFollowState();
      }
    }
  },
  // mounted() {
  //   this.getFollowState();
  // }

}

</script>

<style>

@import "../../css/roomEditModals.css";

.follow-button {
  background-color: rgba(0,0,0,1);
  color: white;
  border: none;
  border-radius: 15px;
  padding: 5px 15px;
}

.follow-button:hover {
  background-color: rgba(0,0,0,0.7);
}

</style>