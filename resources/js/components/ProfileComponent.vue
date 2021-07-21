<template>
  <transition name="flowup">

    <div id="overlay" v-on:click="closeProfileModal()">
      <div id="frame" v-on:click="stopEvent">
        <!-- ナビゲーションタブ -->
        <nav>
          <ul class="nav-menu">
            <li>プロフィール</li>
          </ul>
        </nav>
        <!-- プロフィール -->
        <div class="account-modal-profile">
          <div class="avatar-wrapper">
              <img class="avatar" v-if="userProfile['profile_img_url'] !== null" :src="userProfile['profile_img_url']" alt="https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/user/img/user-solid.svg">
              <img class="avatar" v-else src="https://hirosaka-testapp-room.s3.ap-northeast-1.amazonaws.com/default/user/img/user-solid.svg" alt="">
          </div>
          <div class="name">
            <input type="text" v-model="userProfile['name']" placeholder="ユーザ名">
          </div>
          <div class="about-me">
            <textarea v-model="userProfile['aboutMe']" name="about-me" id="about-me" cols="30" rows="5" placeholder="プロフィール"></textarea>
          </div>
        </div>

        <button v-on:click="updateProfile" class="update-buttom">更新</button>
        <span style="margin:10px">{{message}}</span>
        
      </div>

    </div>
  </transition>
</template>

<script>

  export default {
    props : [],
    data : () => {
      return {
        userId : 0,
        // プロフィールの初期値(編集前の状態)
        userProfileInit : {
          'name' : "",
          'profile_img_url' : null,
          'aboutMe' : "",
        },
        userProfile : {
          'name' : "",
          'profile_img_url' : null,
          'aboutMe' : "",
        },
        'message' : "",
      }
    },
    methods : {
      closeProfileModal(){
        // プロフィールを最初の状態に戻す
        this.userProfile['name'] = this.userProfileInit['name'];
        this.userProfile['aboutMe'] = this.userProfileInit['aboutMe'];
        this.$parent.isShowProfile = false;
      },
      stopEvent: function(){
        event.stopPropagation();
      },
      getProfile(){ // DBからログイン中ユーザのidとプロフィール情報を取得
        let url = '/user/getProfile';
        axios.get(url)
        .then(res => {
          this.userId = res.data.id;
          this.userProfileInit['name'] = res.data.name;
          this.userProfileInit['aboutMe'] = res.data.aboutMe;
        })
        .catch(error => {
          alert('ユーザプロフィール情報を取得できませんでした。');
        })
      },
      updateProfile() { // 入力したデータでプロフィールを更新
        const userId = this.userId;
        let url = '/user/'+userId;
        let profileDatas = {
          'id' : userId,
          'name' : this.userProfile['name'],
          'profile' : this.userProfile['aboutMe'],
        }
        this.message = "更新中...";
        axios.put(url, profileDatas)
        .then(response => {
          alert('更新完了');
          let newName = response.data.name;
          let newAboutMe = response.data.profile;
          this.userProfileInit['name']    = this.userProfile['name']    = newName;
          this.userProfileInit['aboutMe'] = this.userProfile['aboutMe'] = newAboutMe;
          this.message = "";
        })
        .catch(error => {
          alert('更新失敗');
          this.message = "";
        })
      },
    },
    created(){
      if(this.$parent.isLogin){
        this.getProfile(); // プロフィールの初期値を取得
        this.userProfile['name'] = this.userProfileInit['name'];
        this.userProfile['aboutMe'] = this.userProfileInit['aboutMe'];
      }
    },
    mounted: function(){

    },

  }

</script>



<style scoped>

#overlay {
  z-index: 10;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.5);

  /* flex設定 */
  display: flex;
  align-items: center;
  justify-content: center;
}

#frame {
  z-index: 20;
  width: 200px;
  height: 300px;
  border-radius: 5px;
  padding: 50px;
  background-color: white;

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

.avatar-wrapper, .name, .about-me {
  margin-bottom: 20px;
}

.avatar-wrapper {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  box-shadow: 1px 1px 4px grey;

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
  opacity: 1;
  transform: translate(0px, 0px);
  transition: all 150ms;
}

.flowup-enter, .flowup-leave-to {
  opacity: 0;
  transform: translateY(20px);
}


</style>