<template>
    <div id="link-in-home-top">
        <!-- 通常ログイン後のリンク -->
        <div class="link-wrapper" v-if="getIsLogin">
            <!-- {{-- メディア作成画面へのリンク --}} -->
            <a class="link-btn" href="/media/create">メディア作成</a>
            <span class="between-link">または</span>
            <!-- {{-- マイページへのリンク --}} -->
            <a class="link-btn" href="/mypage">マイページへ</a>
        </div>

        <!-- ゲストログイン＆コンテンツ作成画面へ移動 -->
        <div class="link-wrapper" v-if="!getIsLogin">
            <span class="link-message">さっそく試してみる</span>
            <a class="link-btn" href="/login/guest">ゲストとしてコンテンツ作成</a>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    components : {
    },
    props : [],
    data : () => {
        return {

        }
    },
    computed :{
        ...mapGetters('loginState', ['getIsLogin']),
        ...mapGetters('loginState', ['getIsGuest']),
        message_1:function(){
            return getIsLogin ? "メディアを作る" : "ログインして自分のメディアを作る";
        },
        link_1_mgs:function(){
            return getIsLogin ? "" : "";
        },
        message_2:function(){
            return getIsLogin ? "ゲストとしてメディアを作ってみる" : "ゲストとしてメディアを作ってみる";
        },

    },
}

</script>

<style scoped>

.sub-catch-copy {
    font-family: serif;
}

.link-wrapper {
  font-size: 20px;
  height: 80px;
  display: flex;
  justify-content: space-evenly;
  align-items: center;
  flex-wrap: wrap;
}

.between-link {
  color:white;
  font-size:11px;
  margin:10px;
}

.media-watch-message {
  height: 80px;
}


/* スマホ以上のサイズ(PC、タブレット */
@media screen and (min-width: 481px) {

}

/* タブレットサイズ */
@media screen and (max-width: 780px) {}

/* スマホサイズ */
@media screen and (max-width: 480px) {
  .link-wrapper {
    /* 縦積 */
    flex-direction: column;
    height: auto;
    margin-top: 10px;
  }

}

</style>