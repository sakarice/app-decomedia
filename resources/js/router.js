
import Vue from 'vue'
import VueRouter from 'vue-router'
window.Vue = require('vue').default;

Vue.use(VueRouter)

// const HomeComponent = { template: '<div>初めてのVue Resource</div>'}
const Test2Component = { template: '<router-link to="/">topへ</router-link>'}

import Home from './components/HomeComponent.vue';
import Media from './components/MediaComponent.vue';
import MediaEdit from './components/MediaEditComponent.vue';
import MediaHeader from './components/MediaHeaderComponent.vue';
import MediaOwnerInfo from './components/MediaOwnerInfoComponent.vue';
import DispMediaOwnerInfo from './components/media/action_parts/DispMediaOwnerInfoComponent.vue';
import DispMediaLike from './components/media/action_parts/DispMediaLikeComponent.vue';
import DispMediaInfo from './components/media/action_parts/DispMediaInfoComponent.vue';
import DispImgModal from './components/media/action_parts/DispImgModalComponent.vue';
import DispAudioModal from './components/media/action_parts/DispAudioModalComponent.vue';
import DispMovieModal from './components/media/action_parts/DispMovieModalComponent.vue';
import DispMediaSettingModal from './components/media/action_parts/DispMediaSettingModalComponent.vue';

import ImgSelect from './components/ImgSelectComponent.vue';
import AudioSelect from './components/AudioSelectComponent.vue';
import MovieSetting from './components/MovieSettingComponent.vue';
import MediaSetting from './components/MediaSettingComponent.vue';
import Overlay from './components/OverlayComponent.vue';
import Loading from './components/LoadingComponent.vue';
Vue.component('home-component', Home);
Vue.component('media-edit-component', MediaEdit);
Vue.component('media-header-component', MediaHeader);
Vue.component('media-owner-info-component', MediaOwnerInfo);
Vue.component('disp-media-owner-info-component', DispMediaOwnerInfo);
Vue.component('disp-media-like-component', DispMediaLike);
Vue.component('disp-media-info-component', DispMediaInfo);
Vue.component('disp-img-modal-component', DispImgModal);
Vue.component('disp-audio-modal-component', DispAudioModal);
Vue.component('disp-movie-modal-component', DispMovieModal);
Vue.component('disp-media-setting-modal-component', DispMediaSettingModal);

Vue.component('img-select-component', ImgSelect);
Vue.component('audio-select-component', AudioSelect);
Vue.component('movie-setting-component', MovieSetting);
Vue.component('media-setting-component', MediaSetting);
Vue.component('overlay-component', Overlay);
Vue.component('loading-component', Loading);


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
    {
      path : '/media',
      components : {
        default : Media,
        mediaHeader : MediaHeader,
      },
      children : [
      {
        path : ':id',
        name : 'show',
        components : {
          mediaOwnerInfo : MediaOwnerInfo,
          dispMediaLike : DispMediaLike,
          dispMediaOwnerInfo : DispMediaOwnerInfo,
          dispMediaInfo : DispMediaInfo,
        },
      },
      {
        path : ':id/edit',
        name : 'edit',
        components : {
          dispImgModal : DispImgModal,
          dispAudioModal : DispAudioModal,
          dispMovieModal : DispMovieModal,
          dispMediaSettingModal : DispMediaSettingModal,
          imgSelect : ImgSelect,
          audioSelect : AudioSelect,
          movieSetting : MovieSetting,
          mediaSetting : MediaSetting,
          overlay : Overlay,
          loading : Loading,
        },
      },
        ]
    },
    // {
    //   path : '/media/:mediaId',
    //   component : Media,
    //   children : [{
    //     path : '',
    //     components : {
    //       mediaHeader: MediaHeader,
    //       mediaImg : MediaImg,
    //       mediaAudio : MediaAudio,
    //       mediaMovie : MediaMovie,
    //       mediaSetting : MediaSetting,
    //     }
    //   }]
    // },
    {
      path : '/media/:mediaId/edit',
      component : MediaEdit,
    }
  ]
})

export default router;

