
import Vue from 'vue'
import VueRouter from 'vue-router'
window.Vue = require('vue').default;

Vue.use(VueRouter)

import mediaRouter from './routers/media-router';


const router = new VueRouter({
  mode : 'history',
  routes: [
    {...mediaRouter},

  ]
})

export default router;

