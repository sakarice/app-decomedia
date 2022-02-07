<template>
    <div id="home-top">
        <!-- {{-- タイトルコピー --}} -->
        <div class="top-message-wrapper">
            <h2 class="top-message">
                <span>創作に手が出せなかったあなたへ</span><br>
            </h2>
            <!-- <h3 class="sub-catch-copy">
                <span>画像や音楽を「選んで配置する」だけ。</span><br>
                <span>1分でできるコンテンツ制作。</span><br>
            </h3> -->
            <p class="sub-message">
                <!-- 必要なのは「作ってみたい」という好奇心だけ。<br>
                今こそクリエイティブを体験しよう。<br> -->
                画像や音楽を「選んで配置する」だけ。<br>
                1分でできるコンテンツ制作。<br>
            </p>
        </div>

        <!-- {{-- マイページへのリンク --}} -->
        <div class="link-wrapper" v-if="getIsLogin">
            <a class="link-to-mypage" href="/mypage">マイページへ</a>
        </div>
        <!-- ゲストログイン＆コンテンツ作成画面へ移動 -->
        <div class="link-wrapper" v-if="!getIsLogin">
            <span class="link-message">試しにコンテンツを作ってみる</span>
            <a class="link-to-mypage" href="/login/guest">ゲストとしてコンテンツ作成</a>
        </div>
    </div>

</template>

<script>
import {mapGetters} from 'vuex';
import Overlay from '../common/OverlayComponent.vue'
import Loading from '../common/LoadingComponent.vue'

export default {
    components : {
        Overlay,
        Loading,
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
            // return getIsLogin ? "ゲストとしてメディアを作ってみる" : "ゲストとしてメディアを作ってみる";
            return getIsLogin ? "ゲストとしてメディアを作ってみる" : "ゲストとしてメディアを作ってみる";
        },

    },
}

</script>

<style scoped>

/* トップ */
.top-message-wrapper {
  position: absolute;
  top: 10%;
  color: white;
  text-align: center;
  text-shadow: 2px 2px 5px grey;
}

.top-message {
  font-size: 50px;
  font-family: "Yu Mincho Medium";
  /* text-align: center; */
  margin-bottom: 0;
}

.sub-catch-copy {
    font-family: serif;
}

.sub-message {
    margin-top : 200px;
}


/* スマホ以上のサイズ(PC、タブレット */
@media screen and (min-width: 481px) {
  .top-message-wrapper {
    left: 10%;
  }
}

/* タブレットサイズ */
@media screen and (max-width: 780px) {}

/* スマホサイズ */
@media screen and (max-width: 480px) {
  .top-message-wrapper {
    /* max-width: 400px; */
    width: 90%;
    font-size: 0.8rem;
    text-align: center;
  }

  .top-message {
    font-size: 1.5rem;
  }
  .link-wrapper {
    /* 縦積みに */
    flex-direction: column;
  }

}

</style>