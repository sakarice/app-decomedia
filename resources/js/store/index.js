import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions';
import storeA from './modules/storeA.js';

Vue.use(Vuex);

const store = new Vuex.Store({
  state : {
    text: 'abcde',
    storeA
  },
  getters : {
    getMessage(state) {
      return state.storeA.message;
    }
  }
});


export default store