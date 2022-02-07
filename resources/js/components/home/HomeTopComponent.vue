<template>
    <div id="home-top">
        <!-- {{-- タイトルコピー --}} -->
        <div class="top-message-wrapper">
            <h2 class="top-message">
                <span>創作に手が出せなかった</span><br>
                <span>あなたへ</span>
            </h2>
            <p class="sub-message">
                0からコンテンツを作るのではなく、<br>
                既にある画像や音楽、動画を組み合わせて<br>
                メディアとして公開できるサービスです<br><br>
                アイデアを形にするためのスキル・時間の<br>
                ハードルを少しでも下げ、<br>
                創作を楽しむきっかけを提供します
            </p>
        </div>

        <!-- {{-- 検索ウィンドウとマイページへのリンク --}} -->
        <!-- {{-- <div class="search-wrapper"> --}}
            {{-- 検索フォーム --}}
            {{-- <div class="search-form-wrapper">
                <form method="POST" class="search-form" action="/media/show/search/result">
                    @csrf
                    <input class="search-input" type="text" name="keyword" size="30" placeholder="検索ワード">
                    <button type="submit" class="search-icon-wrapper">
                        <i class="fas fa-search search-icon"></i>
                    </button>
                </form>
            </div>
            <p class="sample-keywords">カフェ、雨、勉強、作業用、ロック、自然、chill、etc...</p> --}}
        {{-- </div> --}} -->
        <!-- {{-- マイページへのリンク --}} -->
        <div class="link-wrapper">
            <span class="link-message">自分のメディアを作成する。</span>
            <a class="link-to-mypage" href="/mypage">マイページへ</a>
            <!-- {{-- <button class="link-to-mypage-button"></button> --}} -->
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
    },
}

</script>

<style scoped>

</style>