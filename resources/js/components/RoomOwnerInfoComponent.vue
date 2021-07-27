<template>
  <transition name="flowup">
    <div id="frame" v-on:click="stopEvent">
      <!-- ナビゲーションタブ -->
      <nav>
        <ul class="nav-menu">
          <li>作成者</li>
        </ul>
      </nav>
      <!-- プロフィール -->
      <div class="account-modal-profile">
        <div class="avatar-wrapper">
            <img class="avatar" v-if="roomOwnerInfo['profile_img_url'] !== null" :src="roomOwnerInfo['profile_img_url']" alt="https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/user/img/user-solid.svg">
            <img class="avatar" v-else src="https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/user/img/user-solid.svg" alt="">
        </div>
        <div class="name-wrapper">
          <!-- <input type="text" v-model="roomOwnerInfo['name']" placeholder="ユーザ名"> -->
          <p class="name">{{roomOwnerInfo['name']}}</p>
        </div>
        <div class="about-me">
          <textarea v-model="roomOwnerInfo['aboutMe']" name="about-me" id="about-me" cols="20" rows="4" readonly></textarea>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>

  export default {
    props : [
      'roomId'
    ],
    data : () => {
      return {
        userId : 0,
        roomOwnerInfo : {
          'name' : "",
          'profile_img_url' : null,
          'aboutMe' : "",
        },
        'message' : "",
      }
    },
    methods : {
      closeProfileModal(){
        this.$parent.isShowModal['roomOwnerInfo'] = false;
      },
      stopEvent: function(){
        event.stopPropagation();
      },
      getProfile(){ // DBからログイン中ユーザのidとプロフィール情報を取得
        let url = '/user/getRoomOwnerInfo/' + this.roomId;
        axios.get(url)
        .then(res => {
          this.roomOwnerInfo['name'] = res.data.name;
          this.roomOwnerInfo['aboutMe'] = res.data.aboutMe;
        })
        .catch(error => {
          alert('Room作成者を取得できませんでした。');
        })
      },

    },
    created(){},
    mounted: function(){},
    watch : {
      roomId: function(newVal,oldVal){ // 親コンポーネントのroomIdがdataにセットされるのを待つ
        if(newVal > 0){
          this.getProfile();
        }
      }
    }

  }

</script>



<style scoped>

#frame {
  position: absolute;
  right: 49px;
  box-shadow: 1px 1px 6px grey;
  z-index: 20;
  width: 200px;
  height: 300px;
  border-radius: 5px;
  padding: 10px;
  background-color: white;
  color: black;

  display: flex;
  flex-direction: column;
  align-items: center;
}

.nav-menu {
  list-style: none;
  padding: 0;
  display: flex;  
}

.account-modal-profile{
  display: flex;
  flex-direction: column;
  align-items: center;
}

.avatar-wrapper, .name-wrapper, .about-me {
  margin-bottom: 20px;
}

.name {
  margin: 0;
}

.avatar-wrapper {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  box-shadow: 1px 1px 4px grey;
  color: white;

  display: flex;
  justify-content: center;
  align-items: center;
}

.avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
}

.update-buttom:hover {
  cursor: pointer;
}


/* モーダル表示アニメーション */
.flowup-enter-active, .flowup-leave-active {
  opacity: 0.85;
  transform: translate(0px, 0px);
  transition: all 150ms;
}

.flowup-enter, .flowup-leave-to {
  opacity: 0;
  transform: translateX(5px);
}


</style>