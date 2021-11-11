/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import store from './store/index';


const { default: axios } = require('axios');
const { default: Echo } = require('laravel-echo');
const { functionsIn } = require('lodash');

require('./bootstrap');


window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('test-component', require('./components/TestComponent.vue').default);
Vue.component('test-parent-component', require('./components/TestParentComponent.vue').default);



// ■ホーム画面用
Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('home-link-component', require('./components/HomeLinkComponent.vue').default);

// ■Mediaパーツ
Vue.component('media-header-component', require('./components/MediaHeaderComponent.vue').default);


// ■マイページ用
Vue.component('mypage-menu-bar-component', require('./components/MypageMenuBarComponent.vue').default);
// マイページ
Vue.component('mypage-component', require('./components/MypageComponent.vue').default);
// ユーザページ
Vue.component('user-page-component', require('./components/UserPageComponent.vue').default);
// フォロー/フォロワー一覧
Vue.component('follow-and-follower-component', require('./components/FollowAndFollowerComponent.vue').default);

// ユーザプロフィール
Vue.component('profile-component', require('./components/ProfileComponent.vue').default);
// ユーザプロフィール(ユーザページ表示用)
Vue.component('user-page-profile-component', require('./components/UserPageProfileComponent.vue').default);


// media一覧表示用コンポーネント
Vue.component('media-preview-component', require('./components/MediaPreviewComponent.vue').default);
// mediaリスト一覧表示用コンポーネント
Vue.component('media-list-preview-component', require('./components/MediaListPreviewComponent.vue').default);

// media閲覧用コンポーネント
Vue.component('media-component', require('./components/MediaComponent.vue').default);
Vue.component('media-info-component', require('./components/MediaInfoComponent.vue').default);

// media編集用コンポーネント
Vue.component('media-edit-component', require('./components/MediaEditComponent.vue').default);


// media所有者(＝作成者)情報
Vue.component('media-owner-info-component', require('./components/MediaOwnerInfoComponent.vue').default);
// Mediaへのいいねアイコンコンポーネント
Vue.component('like-media-component', require('./components/LikeMediaComponent.vue').default);
// Media作成者フォローコンポーネント
Vue.component('follow-component', require('./components/FollowComponent.vue').default);



// ★media作成用コンポーネント
Vue.component('img-select-component', require('./components/ImgSelectComponent.vue').default);
Vue.component('movie-setting-component', require('./components/MovieSettingComponent.vue').default);
Vue.component('audio-select-component', require('./components/AudioSelectComponent.vue').default);
Vue.component('media-setting-component', require('./components/MediaSettingComponent.vue').default);
Vue.component('media-audio-component', require('./components/MediaAudioComponent.vue').default);
Vue.component('media-img-component', require('./components/MediaImgComponent.vue').default);
Vue.component('media-movie-component', require('./components/MediaMovieComponent.vue').default);
Vue.component('create-media-component', require('./components/CreateMediaComponent.vue').default);
Vue.component('media-create-button-component', require('./components/MediaCreateButtonComponent.vue').default);
Vue.component('media-update-button-component', require('./components/MediaUpdateButtonComponent.vue').default);
Vue.component('cancel-button-component', require('./components/CancelButtonComponent.vue').default);

// mediaリスト作成用コンポーネント
Vue.component('media-list-create-button-component', require('./components/MediaListCreateButtonComponent.vue').default);
// mediaリスト作成用コンポーネント
Vue.component('selected-media-delete-button-component', require('./components/SelectedMediaDeleteButtonComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// ログインチェック後にvueアプリを生成するため、起動処理をcreateApp関数にまとめ、最後に呼び出し
const createApp = async() => {
    await store.dispatch('checkIsLogin');

    new Vue({
        el : '#app',
        store,
            data : {
                message : 'Hello',
                messages : [],
                isShowModal : false,
            },
            
            methods : {
                showModal() {
                    this.isShowModal = true;
                },
                closeModal() {
                    this.isShowModal = false;
                },
                send() {
                    alert('ajax start: send message');
                    const url = '/ajax/chat';
                    const params = { 
                        message: this.message 
                    };
                    axios.post(url, params)
                        .then((response) => {
                            alert('finish');
                            // 成功したらメッセージをクリア
                            this.message = '';
                        });
                },
                getMessages() {
                    const url = '/ajax/chat';
                    axios.get(url)
                    .then((response) => {
                        this.messages = response.data;
                    })
                }
            
            },
        
            created : function(){
                console.log('created')
                console.log(this.$el)
            },
            mounted(){
                this.getMessages();
        
                window.Echo.channel('chat')
                    .listen('MessageCreated', (e) => {
                        this.getMessages(); // 全メッセージを再読込
                        alert('retake all messages');
                    });
            }
        
    });
}

createApp();
