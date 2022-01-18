<template>
  <div id="frame">

    <div class="account-modal-profile">
      <div class="profile-block profile-left">
        <div class="avatar-wrapper">
          <img class="avatar" v-if="userProfile['profile_img_url'] !== null" :src="userProfile['profile_img_url']" alt="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/user-solid.svg">
          <img class="avatar" v-else src="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/user-solid.svg" alt="">
        </div>
      </div>

      <div class="profile-block profile-right">
        <div class="profile-right-above">
          <div class="name">
            <input class="name-input" type="text" v-model="userProfile['name']" placeholder="ユーザ名">
          </div>
          <button v-on:click="updateProfile" class="update-button">更新</button>
        </div>
        <div class="profile-right-below">
          <div class="about-me-wrapper">
            <textarea v-model="userProfile['profile']" name="about-me" id="about-me" rows="5" placeholder="プロフィール"></textarea>
          </div>
          <span style="margin:10px">{{message}}</span>
        </div>
      </div>

    </div>
    
  </div>

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
          'profile' : "",
        },
        userProfile : {
          'name' : "",
          'profile_img_url' : null,
          'profile' : "",
        },
        'message' : "",
      }
    },
    methods : {
      getProfile(){ // DBからログイン中ユーザのidとプロフィール情報を取得
        let url = '/user/getOwnProfile';
        axios.get(url)
        .then(res => {
          this.userId = res.data.id;
          this.userProfileInit['name'] = this.userProfile['name'] = res.data.name;
          this.userProfileInit['profile'] = this.userProfile['profile'] = res.data.profile;
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
          'profile' : this.userProfile['profile'],
        }
        this.message = "更新中...";
        axios.put(url, profileDatas)
        .then(response => {
          alert('更新完了');
          let newName = response.data.name;
          let newProfile = response.data.profile;
          this.userProfileInit['name']    = this.userProfile['name']    = newName;
          this.userProfileInit['profile'] = this.userProfile['profile'] = newProfile;
          this.message = "";
        })
        .catch(error => {
          alert('更新失敗');
          this.message = "";
        })
      },
    },
    created(){},
    mounted: function(){
      let promise = new Promise((resolve, reject) => {
        this.getProfile(); // プロフィールの初期値を取得
        resolve();
      });
      promise.then(function(){ // getProfile()が完了してから実行する
      })
    },

  }

</script>



<style scoped>

#frame {
}

.account-modal-profile{
  display: flex;
  align-items: flex-start;
  justify-content: center;
}

.profile-block {
  margin: 0 10px;
}

.profile-left, .profile-right {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  justify-content: center;
}

.profile-right-above {
  display: flex;
  margin-bottom: 10px;
}

.profile-right-below {
  width: 100%
}

.name-input {
  font-size: 25px;
  border: none;
  width: 180px;
}

#about-me {
  border: none;
  background-color: rgba(250,250,250,1);
  color: darkslategray;
  width: 100%;
}

.avatar-wrapper {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  box-shadow: 1px 1px 4px grey;

  display: flex;
  justify-content: center;
  align-items: center;
}

.avatar {
  width: 70px;
  height: 70px;
  border-radius: 50%;
}

.update-button {
  background-color: black;
  color: white;
  padding: 0 10px;
  border: 0.1px solid grey;
  border-radius: 3px;
  margin: 0 0 0 10px;
  transition: 0.2s;
}
.update-button:hover {
  cursor: pointer;
  background-color: aquamarine;
  color: black;
}


@media screen and (max-width: 480px) {

  #frame {
    margin-bottom: 5px;
  }

  .profile-right-above {
    flex-direction: column;
  }

  .avatar-wrapper {
    width: 75px;
    height: 75px;
  }
  .avatar {
    width: 65px;
    height: 65px;
  }

  .update-button {
    margin: 10px 0;
  }
  .name-input {
    font-size: 20px;
    width: 100%;
  }
  #about-me {
    font-size: 12px;
  }

  
}

</style>