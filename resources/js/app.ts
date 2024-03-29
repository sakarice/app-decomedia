/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import store from './store/index';
import router from './router';
// window.Vue = require('vue').default;


const { default: axios } = require('axios');
const { default: Echo } = require('laravel-echo');
const { functionsIn } = require('lodash');


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// ■ホーム画面用
Vue.component('header-component', require('./components/common/HeaderComponent.vue').default);
Vue.component('footer-component', require('./components/common/FooterComponent.vue').default);
Vue.component('home-link-component', require('./components/media/action_parts/HomeLinkComponent.vue').default);

// ■Mediaパーツ
Vue.component('media-header-component', require('./components/media/header/MediaHeaderComponent.vue').default);

// ■マイページ用
Vue.component('mypage-menu-bar-component', require('./components/mypage/MypageMenuBarComponent.vue').default);
// マイページ
Vue.component('mypage-component', require('./components/mypage/MypageComponent.vue').default);
// フォロワー/フォロー中ユーザ一覧
Vue.component('follower-and-following-component', require('./components/FollowerAndFollowingComponent.vue').default);

// ユーザプロフィール
Vue.component('profile-component', require('./components/ProfileComponent.vue').default);
// ユーザプロフィール(ユーザページ表示用)
Vue.component('user-page-profile-component', require('./components/mypage/UserPageProfileComponent.vue').default);

// media一覧表示用コンポーネント
Vue.component('media-preview-component', require('./components/MediaPreviewComponent.vue').default);
// mediaリスト一覧表示用コンポーネント
Vue.component('media-list-preview-component', require('./components/MediaListPreviewComponent.vue').default);

// media閲覧用コンポーネント
Vue.component('media-component', require('./components/media/MediaComponent.vue').default);
Vue.component('media-info-component', require('./components/media/show_parts/MediaInfoComponent.vue').default);

// media編集用コンポーネント
Vue.component('media-edit-component', require('./components/media/MediaEditComponent.vue').default);

// media所有者(＝作成者)情報
Vue.component('disp-media-owner-info-component', require('./components/media/show_parts/DispMediaOwnerInfoComponent.vue').default);
Vue.component('media-owner-info-component', require('./components/media/show_parts/MediaOwnerInfoComponent.vue').default);
// Mediaへのいいねアイコンコンポーネント
Vue.component('disp-media-like-component', require('./components/media/show_parts/DispMediaLikeComponent.vue').default);
Vue.component('like-media-component', require('./components/media/show_parts/LikeMediaComponent.vue').default);
// Media作成者フォローコンポーネント
Vue.component('follow-btn-component', require('./components/common/FollowBtnComponent.vue').default);

// ★media作成用コンポーネント
Vue.component('img-select-component', require('./components/media/edit_parts/ImgSelectComponent.vue').default);
Vue.component('movie-setting-component', require('./components/media/edit_parts/MovieSettingComponent.vue').default);
Vue.component('audio-select-component', require('./components/media/edit_parts/AudioSelectComponent.vue').default);
Vue.component('media-setting-component', require('./components/media/edit_parts/MediaSettingComponent.vue').default);
Vue.component('media-audio-component', require('./components/media/media_contents/audio/MediaAudioComponent.vue').default);
Vue.component('media-audio-player-component', require('./components/media/media_contents/audio/MediaAudioPlayerComponent.vue').default);
Vue.component('stereo-audio-component', require('./components/media/media_contents/audio/StereoAudioComponent.vue').default);
Vue.component('monaural-audio-component', require('./components/media/media_contents/audio/MonauralAudioComponent.vue').default);

Vue.component('media-img-mng-component', require('./components/media/media_contents/objects/img/MediaImgMngComponent.vue').default);
Vue.component('media-img-component', require('./components/media/media_contents/objects/img/MediaImgComponent.vue').default);
Vue.component('img-property-component', require('./components/media/media_contents/objects/img/ImgPropertyComponent.vue').default);
Vue.component('media-movie-component', require('./components/media/media_contents/objects/movie/MediaMovieComponent.vue').default);
Vue.component('create-media-component', require('./components/media/CreateMediaComponent.vue').default);
Vue.component('media-create-button-component', require('./components/media/action_parts/MediaCreateButtonComponent.vue').default);
Vue.component('media-update-button-component', require('./components/media/action_parts/MediaUpdateButtonComponent.vue').default);
Vue.component('cancel-button-component', require('./components/media/action_parts/CancelButtonComponent.vue').default);

Vue.component('public-img-component', require('./components/mng/PublicImgComponent.vue').default);
Vue.component('public-audio-component', require('./components/mng/PublicAudioComponent.vue').default);


// media図形コンポーネント
Vue.component('media-figure-mng-component', require('./components/media/media_contents/objects/figure/MediaFigureMngComponent.vue').default);
Vue.component('media-figure-component', require('./components/media/media_contents/objects/figure/MediaFigureComponent.vue').default);
Vue.component('figure-update-component', require('./components/media/media_contents/objects/figure/FigureUpdateComponent.vue').default);

// オブジェクト編集用コンポーネント
Vue.component('object-rotate-component', require('./components/media/media_contents/objects/object_edit_parts/ObjectRotateComponent.vue').default);
Vue.component('object-resize-component', require('./components/media/media_contents/objects/object_edit_parts/ObjectResizeComponent.vue').default);

// mediaリスト作成用コンポーネント
Vue.component('media-list-create-button-component', require('./components/MediaListCreateButtonComponent.vue').default);
// mediaリスト作成用コンポーネント
Vue.component('selected-media-delete-button-component', require('./components/mypage/SelectedMediaDeleteButtonComponent.vue').default);
// オーバーレイコンポーネント
Vue.component('overlay-component', require('./components/common/OverlayComponent.vue').default);
// ローディング中に表示するコンポーネント
Vue.component('loading-component', require('./components/common/LoadingComponent.vue').default);
// マイページへの遷移ボタンコンポーネント
Vue.component('to-mypage-button-component', require('./components/media/action_parts/ToMypageButtonComponent.vue').default);



Vue.component('home-component', require('./components/home/HomeComponent.vue').default);
Vue.component('home-top-component', require('./components/home/HomeTopComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


// ログインチェック後にvueアプリを生成するため、起動処理をcreateApp関数にまとめ、最後に呼び出し
const createApp = async() => {
    await store.dispatch('loginState/checkIsLogin');

    const app = new Vue({
        el : '#app',
        router,
        store,
        // data : {
        //     message : 'Hello',
        //     messages : [],
        //     isShowModal : false,
        // },
    })
}

createApp();
