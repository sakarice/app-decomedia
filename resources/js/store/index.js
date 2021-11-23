import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import loginState from './modules/loginState.js';
import media from './modules/media.js';
import mediaImg from './modules/mediaImg.js';
import mediaAudios from './modules/mediaAudios.js';
import mediaMovie from './modules/mediaMovie.js';
import mediaSetting from './modules/mediaSetting.js';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules : {
    loginState,
    media,
    mediaImg,
    mediaAudios,
    mediaMovie,
    mediaSetting,
  }

});


export default store;