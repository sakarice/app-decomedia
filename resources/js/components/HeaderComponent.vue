<template>
    <div class="header">
        <!-- 左側 -->
        <div class="header-left">
            <home-link-component></home-link-component>
        </div>
        <!-- 右側 -->
        <div class="header-right">
            <!-- ログイン -->
            <div class="header-content" v-show="!(isLogin)">
                <a class="login" href="/login">ログイン</a>
            </div>
            <!-- アカウント作成 -->
            <div class="header-content" v-show="!(isLogin)">
                <a class="signup" href="/register">アカウント作成</a>
            </div>
            <!-- ログアウト -->
            <div class="header-content" v-show="isLogin">
                <a class="logout" href="/logout"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">ログアウト
                </a>
                <form id="logout-form" action="/logout" method="POST" class="d-none">
                    <input type="hidden" name="_token" v-bind:value="csrf">
                </form>
            </div>
            <div class="header-content" v-show="isLogin">
                <a class="user-icon" v-on:click="openProfileModal()">
                    <img id="profile-img" src="/profile_img/user-solid.svg" alt="">
                </a>
            </div>
            <!-- ユーザプロフィール -->
            <profile-component
            v-show="isShowProfile">
            </profile-component>

        </div>
    </div>
        
</template>



<script>
import Profile from './ProfileComponent.vue';
import HomeLink from './HomeLinkComponent.vue';

export default {
    components : {
        HomeLink,
        Profile,
    },
    props : [
        'csrf',
        'isLogin',
    ],
    data : () => {
        return {
            isShowProfile : false,
        }
    },
    methods : {
        // toUserProfile : function(){
        //     console.log("/users/"+this.userInfo['userId']);
        //     return "/users/" + this.userInfo['userId'];
        // },
        openProfileModal(){
            this.isShowProfile = true;
        },
        
    },
    mounted(){}
}

</script>

<style scoped>

.header {
  position: fixed;
  top: 0;
  z-index: 2;
  width: 100%;
  padding: 10px 0;
  background-color: rgba(0, 0, 0, 1);
  display: flex;

  justify-content: space-between;
  align-items: center;
}

.header-right {
  display: flex;
  justify-content: space-around;
}

.header-content {
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    margin: 5px 20px;
    opacity: 0.9;
}

.header-content:hover {
  opacity: 1;
}

/* aタグ全体の設定 */
a {
    color: white;
    text-decoration: none;
}
a:hover {
    color: aquamarine;
}

.user-icon {
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 36px;
    height: 36px;
    padding: 2px;    
    border-radius: 50%;
}

#profile-img {
    width: 30px;
    height: 30px;
    color: white;
}



</style>