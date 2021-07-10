<template>
    <div class="header">
        <!-- 左側 -->
        <div class="header-left">
            <div class="header-content">
                <a class="logo" href="/home">Room</a>
            </div>
        </div>
        <!-- 右側 -->
        <div class="header-right">
            <!-- ログイン -->
            <div class="header-content" v-show="isShowLogin">
                <a class="login" href="/login">ログイン</a>
            </div>
            <!-- アカウント作成 -->
            <div class="header-content" v-show="isShowSignup">
                <a class="signup" href="/register">アカウント作成</a>
            </div>
            <!-- ユーザプロフィールアイコン(ログイン済みの場合表示) -->
            <div class="header-content" v-show="isShowProfileIcon">
                <a class="user-icon" :href="toUserProfile()">
                    <img id="profile-img" src="/profile_img/user-solid.svg" alt="">
                </a>
            </div>
        </div>
    </div>
        
</template>



<script>
export default {
    props : [
        'userId',
        'isShowLogin',
        'isShowSignup',
        'isShowProfileIcon',
    ],
    data : () => {
        return {

        }
    },
    methods : {
        toUserProfile : function(){
            console.log("/users/"+this.userId);
            return "/users/" + this.userId;
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
  background-color: rgba(0, 0, 0, 0.8);
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