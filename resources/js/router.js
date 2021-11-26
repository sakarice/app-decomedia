
import Vue from 'vue'
import VueRouter from 'vue-router'
window.Vue = require('vue').default;

Vue.use(VueRouter)

import Media from './components/MediaComponent.vue';
import MediaHeader from './components/MediaHeaderComponent.vue';
import MediaOwnerInfo from './components/MediaOwnerInfoComponent.vue';
import SwitchToShowMode from './components/media/action_parts/SwitchToShowModeComponent.vue';
import SwitchToEditMode from './components/media/action_parts/SwitchToEditModeComponent.vue';

// 他コンポーネントのラッパー(表示切替も兼ねる)コンポーネント
import DispMediaOwnerInfo from './components/media/change_display_parts/DispMediaOwnerInfoComponent.vue';
import DispMediaLike from './components/media/change_display_parts/DispMediaLikeComponent.vue';
import DispMediaInfo from './components/media/change_display_parts/DispMediaInfoComponent.vue';
import DispImgModal from './components/media/change_display_parts/DispImgModalComponent.vue';
import DispAudioModal from './components/media/change_display_parts/DispAudioModalComponent.vue';
import DispMovieModal from './components/media/change_display_parts/DispMovieModalComponent.vue';
import DispMediaSettingModal from './components/media/change_display_parts/DispMediaSettingModalComponent.vue';

// メディア編集用コンポーネント
import ImgSelect from './components/ImgSelectComponent.vue';
import AudioSelect from './components/AudioSelectComponent.vue';
import MovieSetting from './components/MovieSettingComponent.vue';
import MediaSetting from './components/MediaSettingComponent.vue';

// 編集中のローディング表示コンポーネント
import Overlay from './components/OverlayComponent.vue';
import Loading from './components/LoadingComponent.vue';

const router = new VueRouter({
  mode : 'history',
  routes: [
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
          switchToEditMode : SwitchToEditMode,
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
          switchToShowMode : SwitchToShowMode,
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
  ]
})

export default router;

