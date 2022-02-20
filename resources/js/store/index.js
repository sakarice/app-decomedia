import axios from 'axios';
import Vue from 'vue';
import Vuex from 'vuex';
import followers from './modules/followers.js';
import followings from './modules/followings.js';
import loginState from './modules/loginState.js';
import deviceType from './modules/deviceType.js';
import media from './modules/media.js';
import mediaImgs from './modules/mediaImgs';
import mediaAudios from './modules/mediaAudios.js';
import mediaMovie from './modules/mediaMovie.js';
import mediaTextFactory from './modules/mediaTextFactory';
import mediaTexts from './modules/mediaTexts';
import fontFamilyList from './modules/fontFamilyList'
import japaneseFontFamilyList from './modules/japaneseFontFamilyList'
import mediaContentsField from './modules/mediaContentsField';
import mediaSetting from './modules/mediaSetting.js';
import mediaFigureFactory from './modules/mediaFigureFactory.js';
import mediaFigures from './modules/mediaFigures.js';
import selectedObjects from './modules/selectedObjects';
import mediaComments from './modules/mediaComments';
import stereoPhonicArrangeDefault from './modules/stereoPhonicArrangeDefault';
import tutorialInfo_1 from './modules/tutorial/tutorialInfo_1';
import tutorialInfo_2 from './modules/tutorial/tutorialInfo_2';
import tutorialInfo_3 from './modules/tutorial/tutorialInfo_3';
import tutorialInfos from './modules/tutorial/tutorialInfos';

Vue.use(Vuex);

const store = new Vuex.Store({
  modules : {
    followers,
    followings,
    loginState,
    deviceType,
    media,
    mediaImgs,
    mediaAudios,
    mediaMovie,
    mediaTextFactory,
    mediaTexts,
    fontFamilyList,
    japaneseFontFamilyList,
    mediaSetting,
    mediaContentsField,
    mediaFigureFactory,
    mediaFigures,
    selectedObjects,
    mediaComments,
    stereoPhonicArrangeDefault,
    tutorialInfo_1,
    tutorialInfo_2,
    tutorialInfo_3,
    tutorialInfos,
  }

});


export default store;