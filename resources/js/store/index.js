import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import storeA from './modules/storeA.js';

Vue.use(Vuex);

const store = new Vuex.Store({
  state : {
    isLogin : false,
    storeA
  },
  getters : {
    getIsLogin(state) {
      return state.isLogin;
    },
  },
  mutations : {
    setIsLogin(state, payload){
      state.isLogin = payload;
    },
  },
  actions : {
    async checkIsLogin (context) {
      const res = await axios.get('/checkIsLogin');
      const isLogin = res.data.isLogin;
      context.commit('setIsLogin', isLogin);
    }

  }

});


export default store