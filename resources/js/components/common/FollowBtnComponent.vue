<template>
  <div class="follow-button-wrapper">
    <button class="follow-button" :class="{'color-reverse':isFollow}" v-on:click="changeFollowStateOfViewAndDB()">
      {{follow_message}}
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
    // user_id: function(newVal,oldVal){ // 親コンポーネントのuser_idがdataにセットされるのを待つ
    //   if(newVal > 0){
    //     this.getFollowState();
    //   }
    // }
  },
  created(){
    this.getFollowState();
  }

}

</script>

<style>

@import "/resources/css/mediaEditModals.css";

.follow-button {
  border: none;
  border-radius: 15px;
  padding: 5px 15px;
}

.follow-button:hover {
  color: lightgreen;
  background-color: rgba(0,0,0,0.7);
}

.color-reverse {
  color: white;
  background-color: rgba(0,0,0,1);
}

</style>