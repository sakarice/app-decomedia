import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import loginState from './modules/loginState.js';
import mediaImg from './modules/mediaImg.js';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules : {
    loginState,
    mediaImg,
  }

});


export default store;