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
            <img class="avatar" v-if="mediaOwnerInfo['profile_img_url'] !== null" :src="mediaOwnerInfo['profile_img_url']" alt="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/user-solid.svg">
            <img class="avatar" v-else src="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/user-solid.svg" alt="">
        </div>
        <div class="name-wrapper">
          <!-- <input type="text" v-model="mediaOwnerInfo['name']" placeholder="ユーザ名"> -->
          <p class="name">{{mediaOwnerInfo['name']}}</p>
        </div>
        <div class="about-me">
          <textarea v-model="mediaOwnerInfo['aboutMe']" name="about-me" id="about-me" cols="20" rows="4" readonly></textarea>
        </div>
      </div>

      <!-- フォローアイコン -->
      <div id="disp-follow-modal-wrapper" class="icon-wrapper" v-show="!isMyMedia">
        <follow-component
        v-if="isShowFollow"
        :media-owner-id="mediaOwnerInfo['id']">
        </follow-component>
      </div>

    </div>
  </transition>
</template>

<script>
import { mapGetters } from "vuex";

import Follow from './FollowComponent.vue';

  export default {
    components : {
      Follow,
    },
    props : [],
    data : () => {
      return {
        isMyMedia : false,
        userId : 0,
        mediaOwnerInfo : {
          'id' : 0,
          'name' : "",
          'profile_img_url' : null,
          'aboutMe' : "",
        },
        'message' : "",
      }
    },
    computed : {
      ...mapGetters('loginState', ['getIsLogin']),
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      isShowFollow: function(){
        // ログインしていて自分のルームでなければフォローアイコンを表示
        return this.getIsLogin && !(this.isMyMedia);
      },
      doneGetMediaId : function(){
        if(this.getMediaId != ""){return true}
      }
    },
    methods : {
      checkIsMyMedia(){
        let url = '/ajax/judgeIsMyMedia/' + this.getMediaId;
        axios.get(url)
          .then(response =>{
            this.isMyMedia = response.data.isMyMedia;
          })
          .catch(error => {})
      },
      closeProfileModal(){
        this.$parent.isShowModal['mediaOwnerInfo'] = false;
      },
      stopEvent: function(){
        event.stopPropagation();
      },
      getProfile(){ // DBからログイン中ユーザのidとプロフィール情報を取得
        let url = '/user/mediaOwner/profile/show/' + this.getMediaSetting['id'];
        axios.get(url)
        .then(res => {
          this.mediaOwnerInfo['id'] = res.data.id;
          this.mediaOwnerInfo['name'] = res.data.name;
          this.mediaOwnerInfo['aboutMe'] = res.data.aboutMe;
        })
        .catch(error => {
          alert('Media作成者を取得できませんでした。');
        })
      },

    },
    mounted: function(){},
    watch : {
      doneGetMediaId : function(val){
        this.checkIsMyMedia();
      }
    },


  }

</script>



<style scoped>

#frame {
  position: absolute;
  /* left: 62px; */
  box-shadow: 1px 1px 6px grey;
  z-index: 20;
  width: 200px;
  /* height: 350px; */
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
  /* transform: translateX(-5px); */
}


/* タブレット */
@media screen and (min-width: 481px) {
  #frame {
    left: 62px;
  }
  .flowup-enter, .flowup-leave-to {
  transform: translateX(-5px);
  }
}

/* スマホ */
@media screen and (max-width: 480px) {
  #frame {
    right: 62px;
  }
  .flowup-enter, .flowup-leave-to {
  transform: translateX(5px);
  }

}


</style>