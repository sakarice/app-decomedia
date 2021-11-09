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

// ■Roomパーツ
Vue.component('room-header-component', require('./components/RoomHeaderComponent.vue').default);


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


// room一覧表示用コンポーネント
Vue.component('room-preview-component', require('./components/RoomPreviewComponent.vue').default);
// roomリスト一覧表示用コンポーネント
Vue.component('room-list-preview-component', require('./components/RoomListPreviewComponent.vue').default);

// room閲覧用コンポーネント
Vue.component('room-component', require('./components/RoomComponent.vue').default);
Vue.component('room-info-component', require('./components/RoomInfoComponent.vue').default);

// room編集用コンポーネント
Vue.component('room-edit-component', require('./components/RoomEditComponent.vue').default);


// room所有者(＝作成者)情報
Vue.component('room-owner-info-component', require('./components/RoomOwnerInfoComponent.vue').default);
// Roomへのいいねアイコンコンポーネント
Vue.component('like-room-component', require('./components/LikeRoomComponent.vue').default);
// Room作成者フォローコンポーネント
Vue.component('follow-component', require('./components/FollowComponent.vue').default);



// ★room作成用コンポーネント
Vue.component('img-select-component', require('./components/ImgSelectComponent.vue').default);
Vue.component('movie-setting-component', require('./components/MovieSettingComponent.vue').default);
Vue.component('audio-select-component', require('./components/AudioSelectComponent.vue').default);
Vue.component('media-setting-component', require('./components/MediaSettingComponent.vue').default);
Vue.component('room-audio-component', require('./components/RoomAudioComponent.vue').default);
Vue.component('media-img-component', require('./components/MediaImgComponent.vue').default);
Vue.component('room-movie-component', require('./components/RoomMovieComponent.vue').default);
Vue.component('create-media-component', require('./components/CreateMediaComponent.vue').default);
Vue.component('room-create-button-component', require('./components/RoomCreateButtonComponent.vue').default);
Vue.component('room-update-button-component', require('./components/RoomUpdateButtonComponent.vue').default);
Vue.component('cancel-button-component', require('./components/CancelButtonComponent.vue').default);

// roomリスト作成用コンポーネント
Vue.component('room-list-create-button-component', require('./components/RoomListCreateButtonComponent.vue').default);
// roomリスト作成用コンポーネント
Vue.component('selected-room-delete-button-component', require('./components/SelectedRoomDeleteButtonComponent.vue').default);


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
