<template>
  <div class="follow-button-wrapper">
    <button class="follow-button" :class="{'following':isFollow, 'not-following':!isFollow}"
     v-on:click="changeFollowStateOfViewAndDB()">
      <!-- <span>{{follow_message}}</span> -->
    </button>
  </div>
</template>


<script>
export default{
  props: [
    'user_id',
  ],
  data : () => {
    return {
      isFollow : false,
    }
  },
  computed : {
    follow_message : function(){
      return this.isFollow ? "フォロー中" : "フォローする";
    }
  },
  methods : {
    getFollowState(){
      console.log('user_id:'+this.user_id)
      const url = '/following/'+ this.user_id;
      axios.get(url)
      .then(response => {
        this.isFollow = response.data.isFollow;
      })
      .catch(error => {})
    },
    changeFollowStateOfViewAndDB(){
      // dataのいいね情報を更新が完了してから、DBも更新する
      // 下記Promise内はthisのスコープから外れてしまうため、thisを変数に収める
      const tmpThis = this;

      var firstMethod = new Promise(function(resolve, reject){
        tmpThis.updateFollowStateInDB();
        resolve();
      })

      firstMethod.then(function(){})
    },
    updateFollowStateInDB(){
      const url = '/user/follow';
      const data = {
        'isFollow' : this.isFollow,
        'user_id' : this.user_id,
      }
      axios.post(url, data)
        .then(res => {
          console.log(res.data.isFollow);
          this.isFollow = res.data.isFollow;
        })
        .catch(error => {})
    },

  },
  created(){
    this.getFollowState();
  },

}

</script>

<style>

@import "/resources/css/mediaEditModals.css";

.follow-button {
  border: none;
  border-radius: 15px;
  padding: 5px 15px;
}

.following {
  color: white;
  background-color: black
}
.following::after {
  content: "フォロー中";
}
.following:hover::after {
  content: "フォロー解除";
  color: tomato;
}


.not-following {
  color: black;
  background-color: rgb(240,250,250);
}
.not-following::after {
  content: "フォローする";
}
.not-following:hover {
  color: white;
  background-color: rgba(0,0,0,1);
}


</style>