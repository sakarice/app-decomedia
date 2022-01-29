<template>
    <div class="header">
        <!-- 左側 -->
        <div class="header-left">
            <home-link-component></home-link-component>
        </div>
        <!-- 右側 -->
        <div class="header-right">
            <!-- ゲストログイン -->
            <div class="header-content-wrapper guest-login-wrapper" v-if="!(getIsLogin)">
                <a class="guest-login header-content" href="/login/guest">ゲストログイン</a>
            </div>
            <!-- ゲストログイン中に表示する文言 -->
            <span v-if="getIsGuest" class="logged-in-as-guest-msg">ゲスト</span>

            <!-- ログイン -->
            <div class="header-content-wrapper" v-if="!(getIsLogin)">
                <a class="login header-content" href="/login">ログイン</a>
            </div>
            <!-- アカウント作成 -->
            <div class="header-content-wrapper" v-if="!(getIsLogin)">
                <a class="signup header-content" href="/register">アカウント作成</a>
            </div>
            <!-- ログアウト -->
            <div class="header-content-wrapper" v-if="getIsLogin">
                <a class="logout header-content" href="/logout"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">ログアウト
                </a>
                <form id="logout-form" action="/logout" method="POST" class="d-none">
                    <input type="hidden" name="_token" v-bind:value="csrf">
                </form>
            </div>
            <!-- ユーザアイコン -->
            <div class="header-content-wrapper column" v-if="getIsLogin">
                <a class="user-icon header-content" v-on:click="openProfileModal()">
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
import { mapState, mapGetters } from "vuex";
import Profile from '../ProfileComponent.vue';
import HomeLink from '../media/action_parts/HomeLinkComponent.vue';

export default {
    components : {
        HomeLink,
        Profile,
    },
    props : [
        'csrf',
    ],
    data : function(){
        return {
            isShowProfile : false,
        }
    },
    computed : {
        ...mapState('loginState', ['isLogin']),
        ...mapGetters('loginState', ['getIsLogin']),
        ...mapGetters('loginState', ['getIsGuest']),
    },
    methods : {
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
  padding: 10px;
  z-index: 2;
  width: 100%;
  background-color: rgba(0, 0, 0, 1);
  display: flex;

  justify-content: space-between;
  align-items: center;
}

.header-right {
  display: flex;
  justify-content: space-around;
}

.header-content-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    margin: 5px 20px;
    opacity: 0.9;
}
.header-content-wrapper:hover {
  opacity: 1;
}

.guest-login {
    background-color: slategray;
    border-radius: 2px;
    padding: 0 10px;
}
.guest-login:hover {
    color: white;
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

.column {
    flex-direction: column;
}

.logged-in-as-guest-msg {
    position: absolute;
    top : 0;
    left : calc(50% - 25px);
    background-color: greenyellow;
    color: black;
    font-size: 13px;
    padding: 0 10px;
}

@media screen and (max-width: 480px){
    .header-content {
        font-size: 0.8rem;
    }

    .guest-login-wrapper {
        position: absolute;
        top: 45px;
        right: 3px;
    }

    .guest-login {
        padding: 2px 8px;
    }

}




</style>