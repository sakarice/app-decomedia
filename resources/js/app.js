/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

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
Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('test-component', require('./components/TestComponent.vue').default);
Vue.component('img-select-component', require('./components/ImgSelectComponent.vue').default);
Vue.component('test-parent-component', require('./components/TestParentComponent.vue').default);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el : '#app',
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
