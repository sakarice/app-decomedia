import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import loginState from './modules/loginState.js';
import media from './modules/media.js';
import mediaImgs from './modules/mediaImgs';
import mediaAudios from './modules/mediaAudios.js';
import mediaMovie from './modules/mediaMovie.js';
import mediaSetting from './modules/mediaSetting.js';
import mediaFigureFactory from './modules/mediaFigureFactory.js';
import mediaFigures from './modules/mediaFigures.js';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules : {
    loginState,
    media,
    mediaImgs,
    mediaAudios,
    mediaMovie,
    mediaSetting,
    mediaFigureFactory,
    mediaFigures,
  }

});


export default store;