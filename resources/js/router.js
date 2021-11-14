
import Vue from 'vue'
import VueRouter from 'vue-router'
window.Vue = require('vue').default;

Vue.use(VueRouter)

// const HomeComponent = { template: '<div>初めてのVue Resource</div>'}
const Test2Component = { template: '<router-link to="/">topへ</router-link>'}

import Home from './components/HomeComponent.vue';
Vue.component('home-component', Home);


const router = new VueRouter({
  mode : 'history',
  routes: [
    {
      path :'/',
      component : Home,
      children: [{
        path : 'test',
        component : Test2Component,
      }]
    },
  ]
})

export default router;

