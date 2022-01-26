
import Media from '../components/media/MediaComponent.vue';
import MediaHeader from '../components/media/MediaHeaderComponent.vue';
import MediaOwnerInfo from '../components/media/show_parts/MediaOwnerInfoComponent.vue';
import MediaCommentMng from '../components/media/media_contents/comment/MediaCommentMngComponent.vue';
import SwitchToShowMode from '../components/media/action_parts/SwitchToShowModeComponent.vue';
import SwitchToEditMode from '../components/media/action_parts/SwitchToEditModeComponent.vue';

// 他コンポーネントのラッパー(表示切替も兼ねる)コンポーネント
import DispMediaOwnerInfo from '../components/media/show_parts/DispMediaOwnerInfoComponent.vue';
import DispMediaComment from '../components/media/show_parts/DispMediaCommentComponent.vue';
import DispMediaLike from '../components/media/show_parts/DispMediaLikeComponent.vue';
import DispMediaInfo from '../components/media/show_parts/DispMediaInfoComponent.vue';
import DispImgModal from '../components/media/change_display_parts/DispImgModalComponent.vue';
import DispAudioModal from '../components/media/change_display_parts/DispAudioModalComponent.vue';
import DispMovieModal from '../components/media/change_display_parts/DispMovieModalComponent.vue';
import DispMediaSettingModal from '../components/media/change_display_parts/DispMediaSettingModalComponent.vue';
import DispContentsFieldSettingModal from '../components/media/change_display_parts/DispMediaContentsFieldSettingModalComponent.vue';
import DispTextSettingModal from '../components/media/change_display_parts/DispTextSettingModalComponent.vue';
import DispFigureSettingModal from '../components/media/change_display_parts/DispFigureSettingModalComponent.vue';

// メディア編集用コンポーネント
import ImgSelect from '../components/media/edit_parts/ImgSelectComponent.vue';
import MediaFigureFactory from '../components/media/edit_parts/MediaFigureFactoryComponent.vue';
import MediaTextFactory from '../components/media/edit_parts/MediaTextFactoryComponent.vue';
import AudioSelect from '../components/media/edit_parts/AudioSelectComponent.vue';
import MovieSetting from '../components/media/edit_parts/MovieSettingComponent.vue';
import MediaSetting from '../components/media/edit_parts/MediaSettingComponent.vue';
import ContentsFieldSetting from '../components/media/edit_parts/MediaContentsFieldSettingComponent.vue';
import DomResize from '../components/media/media_contents/objects/object_edit_parts/DomResizeComponent.vue'
import ObjectRotate from '../components/media/media_contents/objects/object_edit_parts/ObjectRotateComponent.vue'

// オブジェクト選択管理コンポーネント
import ObjectSelectMng from '../components/media/media_contents/objects/object_edit_parts/ObjectSelectMngComponent.vue';

// オブジェクトの詳細確認＆編集コンポーネント
import FigureUpdate from '../components/media/media_contents/objects/figure/FigureUpdateComponent.vue';
import ImgProperty from '../components/media/media_contents/objects/img/ImgPropertyComponent.vue';
import TextProperty from '../components/media/media_contents/objects/text/TextPropertyComponent.vue';

// オーディオの立体音響の定位設定
import StereoPhonicArrange from '../components/media/media_contents/StereoPhonicArrangeComponent.vue';


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
        dispContentsFieldSettingModal : DispContentsFieldSettingModal,
        dispTextSettingModal : DispTextSettingModal,
        dispFigureSettingModal : DispFigureSettingModal,
        imgSelect : ImgSelect,
        mediaFigureFactory : MediaFigureFactory,
        mediaTextFactory : MediaTextFactory,
        audioSelect : AudioSelect,
        movieSetting : MovieSetting,
        contentsFieldSetting : ContentsFieldSetting,
        mediaSetting : MediaSetting,
        figureUpdate : FigureUpdate,
        imgProperty : ImgProperty,
        textProperty : TextProperty,
        stereoPhonicArrange : StereoPhonicArrange,
        objectSelectMng : ObjectSelectMng,
        objectRotate : ObjectRotate,
        domResize : DomResize,
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
        dispContentsFieldSettingModal : DispContentsFieldSettingModal,
        dispTextSettingModal : DispTextSettingModal,
        dispFigureSettingModal : DispFigureSettingModal,
        imgSelect : ImgSelect,
        mediaFigureFactory : MediaFigureFactory,
        mediaTextFactory : MediaTextFactory,
        audioSelect : AudioSelect,
        movieSetting : MovieSetting,
        contentsFieldSetting : ContentsFieldSetting,
        mediaSetting : MediaSetting,
        figureUpdate : FigureUpdate,
        imgProperty : ImgProperty,
        textProperty : TextProperty,
        stereoPhonicArrange : StereoPhonicArrange,
        objectSelectMng : ObjectSelectMng,
        objectRotate : ObjectRotate,
        domResize : DomResize,
        overlay : Overlay,
        loading : Loading,
        
      },
    },
    {
      path : ':id',
      name : 'show',
      components : {
        switchToEditMode : SwitchToEditMode,
        mediaCommentMng : MediaCommentMng,
        dispMediaLike : DispMediaLike,
        dispMediaInfo : DispMediaInfo,
        dispMediaComment : DispMediaComment,
      },
    },
  ]
}

