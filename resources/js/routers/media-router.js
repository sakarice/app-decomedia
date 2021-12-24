
import Media from '../components/media/MediaComponent.vue';
import MediaHeader from '../components/media/MediaHeaderComponent.vue';
import MediaOwnerInfo from '../components/media/show_parts/MediaOwnerInfoComponent.vue';
import SwitchToShowMode from '../components/media/action_parts/SwitchToShowModeComponent.vue';
import SwitchToEditMode from '../components/media/action_parts/SwitchToEditModeComponent.vue';

// 他コンポーネントのラッパー(表示切替も兼ねる)コンポーネント
import DispMediaOwnerInfo from '../components/media/show_parts/DispMediaOwnerInfoComponent.vue';
import DispMediaLike from '../components/media/show_parts/DispMediaLikeComponent.vue';
import DispMediaInfo from '../components/media/show_parts/DispMediaInfoComponent.vue';
import DispImgModal from '../components/media/change_display_parts/DispImgModalComponent.vue';
import DispAudioModal from '../components/media/change_display_parts/DispAudioModalComponent.vue';
import DispMovieModal from '../components/media/change_display_parts/DispMovieModalComponent.vue';
import DispMediaSettingModal from '../components/media/change_display_parts/DispMediaSettingModalComponent.vue';
import DispFigureSettingModal from '../components/media/change_display_parts/DispFigureSettingModalComponent.vue';

// メディア編集用コンポーネント
import ImgSelect from '../components/media/edit_parts/ImgSelectComponent.vue';
import MediaFigureFactory from '../components/media/edit_parts/MediaFigureFactoryComponent.vue';
import AudioSelect from '../components/media/edit_parts/AudioSelectComponent.vue';
import MovieSetting from '../components/media/edit_parts/MovieSettingComponent.vue';
import MediaSetting from '../components/media/edit_parts/MediaSettingComponent.vue';

// オブジェクトの詳細確認＆編集コンポーネント
import FigureUpdate from '../components/media/media_contents/objects/figure/FigureUpdateComponent.vue';
import ImgProperty from '../components/media/media_contents/objects/img/ImgPropertyComponent.vue';

// 編集中のローディング表示コンポーネント
import Overlay from '../components/common/OverlayComponent.vue';
import Loading from '../components/common/LoadingComponent.vue';


export default{
  path : '/media',
  components : {
    default : Media,
    mediaHeader : MediaHeader,
  },
  children : [
    {
      path : 'create',
      name : 'create',
      components : {
        dispImgModal : DispImgModal,
        dispAudioModal : DispAudioModal,
        dispMovieModal : DispMovieModal,
        dispMediaSettingModal : DispMediaSettingModal,
        dispFigureSettingModal : DispFigureSettingModal,
        imgSelect : ImgSelect,
        mediaFigureFactory : MediaFigureFactory,
        audioSelect : AudioSelect,
        movieSetting : MovieSetting,
        mediaSetting : MediaSetting,
        figureUpdate : FigureUpdate,
        imgProperty : ImgProperty,
        overlay : Overlay,
        loading : Loading,
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
        dispFigureSettingModal : DispFigureSettingModal,
        imgSelect : ImgSelect,
        mediaFigureFactory : MediaFigureFactory,
        audioSelect : AudioSelect,
        movieSetting : MovieSetting,
        mediaSetting : MediaSetting,
        figureUpdate : FigureUpdate,
        imgProperty : ImgProperty,
        overlay : Overlay,
        loading : Loading,
        
      },
    },
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
  ]
}

