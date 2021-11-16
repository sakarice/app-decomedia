import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import loginState from './modules/loginState.js';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules : {
    loginState,
  }

});


export default store;