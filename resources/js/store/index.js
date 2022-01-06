import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import loginState from './modules/loginState.js';
import deviceType from './modules/deviceType.js';
import media from './modules/media.js';
import mediaImgs from './modules/mediaImgs';
import mediaAudios from './modules/mediaAudios.js';
import mediaMovie from './modules/mediaMovie.js';
import mediaTextFactory from './modules/mediaTextFactory';
import mediaTexts from './modules/mediaTexts';
import fontFamilyList from './modules/fontFamilyList'
import mediaContentsField from './modules/mediaContentsField';
import mediaSetting from './modules/mediaSetting.js';
import mediaFigureFactory from './modules/mediaFigureFactory.js';
import mediaFigures from './modules/mediaFigures.js';
import selectedObjects from './modules/selectedObjects';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules : {
    loginState,
    deviceType,
    media,
    mediaImgs,
    mediaAudios,
    mediaMovie,
    mediaTextFactory,
    mediaTexts,
    fontFamilyList,
    mediaSetting,
    mediaContentsField,
    mediaFigureFactory,
    mediaFigures,
    selectedObjects,
  }

});


export default store;