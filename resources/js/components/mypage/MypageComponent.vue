<template>
  <div class="mypage-content-wrapper">
    <!-- profile -->
    <user-page-profile class="for-pc-tablet"></user-page-profile>

    <!-- Media -->
    <div class="mypage-action-menu">
      <div class="menu-inner flex a-end">
        <!-- Media作成 -->
        <div class="mypage-action-item media-create-wrapper" v-show="!isSelectMode">
          <a class="linkTo-createMedia" href="/media/create">
            <i class="fas fa-plus media-create-icon"></i>
          </a>
          <span class="action-item-subtitle">作成</span>
        </div>
        <!-- 〇選択モードの切り替えボタン -->
        <div class="mypage-action-item select-mode-switch" v-show="!isSelectMode">
          <i id="change-select-mode" class="fas fa-hand-point-up" @click="toggleSelectMode"></i>
          <!-- <i v-show="isSelectMode" id="change-select-mode" class="far fa-window-close fa-3x" @click="toggleSelectMode"></i> -->
          <span class="action-item-subtitle">{{selectModeButtonMessage}}</span>
        </div>

        <!-- Mediaの選択モード -->
        <!-- 〇選択したMedia削除ボタン -->
        <div class="select-mode-item-wrapper flex" :class='{"is-black": isSelectMode}'>
          <selected-media-delete-button-component
          class="mypage-action-item select-mode-item"
          v-show="isSelectMode"
          v-on:set-is-delete="setIsDelete">
          </selected-media-delete-button-component>

          <!-- 〇選択をすべて解除するボタン -->
          <div class="mypage-action-item select-mode-item" v-show="isSelectMode">
            <i class="far fa-square select-uncheck-icon" @click="unCheckAllMedia"></i>
            <span class="action-item-subtitle">リセット</span>
          </div>

          <!-- 選択モードキャンセル -->
          <div class="mypage-action-item select-mode-switch" v-show="isSelectMode">
            <i id="change-select-mode" class="far fa-window-close" @click="toggleSelectMode"></i>
            <span class="action-item-subtitle">{{selectModeButtonMessage}}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- 作成済みMediaのプレビュー -->
    <section v-show="isShowCreatedMediaPreview" class="mypage-section created-media-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
      <i class="fas fa-tools category-icon"></i>
        <h3 class="section-title">作成したメディア</h3>
        <!-- {{-- Media作成 --}} -->

        <span class="view-more" @click="addCreatedMediaPreviewInfos">
          ▼more
        </span>
      </div>
      <!-- {{-- 作成済みmedia一覧 --}} -->
      <media-preview-component class="media-preview"
        :media-preview-infos="createdMediaPreviewInfos"
        :is-show-cover="isShowCoverOnCreateMedia"
        :is-select-mode="isSelectMode"
        v-on:set-is-delete="setIsDelete"
        @changeIsCheckedMedia="changeIsCheckedCreatedMedia"
        ref="createdMediaPreview">
      </media-preview-component>
    </section>

    <!-- いいねしたMediaのプレビュー -->
    <section v-show="isShowLikedMediaPreview" class="mypage-section liked-media-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <i class="fas fa-thumbs-up category-icon"></i>
        <h3 class="section-title">いいねしたメディア</h3>
        <span class="view-more" @click="addLikedMediaPreviewInfos">
          ▼more
        </span>
      </div>
      <!-- いいねしたmedia一覧 -->
      <media-preview-component class="media-preview"
        :media-preview-infos="likedMediaPreviewInfos"
        :is-show-cover="isShowCoverOnLikeMedia"
        :is-select-mode="isSelectMode"
        @changeIsCheckedMedia="changeIsCheckedLikedMedia"
        ref="likedMediaPreview">
      </media-preview-component>
    </section>      

    <mypage-menu-bar-component>
    </mypage-menu-bar-component>

    <overlay></overlay>
    <div v-show="isDeleting">
      <loading
      message="メディアを削除中です...">
      </loading>
    </div>

  </div>




</template>

<script>
import MediaPreview from '../MediaPreviewComponent.vue';
import MediaListPreview from '../MediaListPreviewComponent.vue';
import MypageMenuBar from './MypageMenuBarComponent.vue';
import MediaListCreateButton from '../MediaListCreateButtonComponent.vue';
import SelectedMediaDeleteButton from './SelectedMediaDeleteButtonComponent.vue';
import UserPageProfile from './UserPageProfileComponent.vue';
import Overlay from '../common/OverlayComponent.vue';
import Loading from '../common/LoadingComponent.vue';
import { showOverLay, hideOverLay } from '../../functions/overlayDispHelper';


export default {
  components : {
    MediaPreview,
    MediaListPreview,
    MypageMenuBar,
    MediaListCreateButton,
    SelectedMediaDeleteButton,
    UserPageProfile,
    Overlay,
    Loading,
  },
  props : [
    'createdMediaPreviewInfosFromParent',
    'likedMediaPreviewInfosFromParent',
  ],
  data : () => {
    return {
      'createdMediaPreviewInfos' : "",
      'likedMediaPreviewInfos' : "",
      'isSelectMode' : false,
      'isShowCoverOnCreateMediaList' : true,
      'isShowCoverOnCreateMedia' : true,
      'isShowCoverOnLikeMedia' : false,
      'isShowCreatedMediaListPreview' : true,
      'isShowCreatedMediaPreview' : true,
      'isShowLikedMediaPreview' : true,
      'isUpdateCreatedMediaPreviewInfo' : false,
      'isUpdateLikedMediaPreviewInfo' : false,
      'totalSelectedCount' : 0,
      'isDeleting' : false,
    }
  },
  computed : {
    selectModeButtonMessage : function(){
      if(this.isSelectMode){
        return 'キャンセル';
      } else {
        return '選択';
      }
    }
  },
  methods : {
    toggleSelectMode(){
      this.isShowCoverOnCreateMedia = this.isSelectMode
      this.isSelectMode = !this.isSelectMode;
    },
    mediaEditLink : function(id) {
      return "/media/" + id + "/edit";
    },
    addCreatedMediaPreviewInfos(){
      let url = '/addCreatedMediaPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.createdMediaPreviewInfos = response.data.createdMediaPreviewInfos;
        tmpThis.isUpdateCreatedMediaPreviewInfo = true;
      })
      .catch(error => {
        alert('media情報の取得に失敗しました')
      })
    },
    addLikedMediaPreviewInfos(){
      let url = '/addLikedMediaPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.likedMediaPreviewInfos = response.data.likedMediaPreviewInfos;
        tmpThis.isUpdateLikedMediaPreviewInfo = true;
      })
      .catch(error => {
        alert('media情報の取得に失敗しました')
      })
    },
    addCreatedMediaListPreviewInfos(){
      this.$refs.createdMediaListPreview.addCreatedMediaListPreviewInfos(300);
    },
    deleteMedia(media_id){
      let media_data = {
        'media_id' : media_id,
      }
      const url = '/media/delete';
      this.isDeleting = true;
      axios.post(url, media_data)
      .then(response => {
        alert(response.data.message);
        this.isDeleting = false;        
        location.reload();
      })
      .catch(error => {
        alert('media削除に失敗しました。');
        this.isDeleting = false;
      })
    },
    setIsDelete(isDelete){
      this.isDeleting = isDelete;

    },
    changeIsCheckedCreatedMedia(isChecked, index){
      if(isChecked == true){
        this.increaseCreatedMediaSelectedCount(index);
      } else if(isChecked == false){
        let unSelectedOrderNum = this.createdMediaPreviewInfos[index]['selectedOrderNum'];
        this.createdMediaPreviewInfos[index]['selectedOrderNum'] = 0;
        this.decreseMediaSelectedCount(unSelectedOrderNum);
      }
    },
    changeIsCheckedLikedMedia(isChecked, index){
      if(isChecked == true){
        this.increaseLikedMediaSelectedCount(index);
      } else if(isChecked == false){
        let unSelectedOrderNum = this.likedMediaPreviewInfos[index]['selectedOrderNum'];
        this.likedMediaPreviewInfos[index]['selectedOrderNum'] = 0;
        this.decreseMediaSelectedCount(unSelectedOrderNum);
      }
    },
    increaseCreatedMediaSelectedCount(index){
      this.createdMediaPreviewInfos[index]['selectedOrderNum'] = this.totalSelectedCount+1;
      this.totalSelectedCount ++;
    },
    increaseLikedMediaSelectedCount(index){
      this.likedMediaPreviewInfos[index]['selectedOrderNum'] = this.totalSelectedCount+1;
      this.totalSelectedCount ++;
    },
    decreseMediaSelectedCount(unSelectedOrderNum){
      for(let i=0; i < this.createdMediaPreviewInfos.length; i++){
        if(this.createdMediaPreviewInfos[i]['selectedOrderNum'] > unSelectedOrderNum){
          this.createdMediaPreviewInfos[i]['selectedOrderNum'] --;
        }
      }
      for(let i=0; i < this.likedMediaPreviewInfos.length; i++){
        if(this.likedMediaPreviewInfos[i]['selectedOrderNum'] > unSelectedOrderNum){
          this.likedMediaPreviewInfos[i]['selectedOrderNum'] --;
        }
      }
      this.totalSelectedCount --;
    },
    unCheckAllMedia(){
      this.$refs.createdMediaPreview.unCheckAllMedia();
      this.$refs.likedMediaPreview.unCheckAllMedia();
      for(let i=0; i < this.createdMediaPreviewInfos.length; i++){
        this.createdMediaPreviewInfos[i]['selectedOrderNum'] = 0;
      }
      for(let i=0; i < this.likedMediaPreviewInfos.length; i++){
        this.likedMediaPreviewInfos[i]['selectedOrderNum'] = 0;
      }
      this.totalSelectedCount = 0;
    }

  },
  created() {
    },
  mounted() {
    this.createdMediaPreviewInfos = this.createdMediaPreviewInfosFromParent;
    this.likedMediaPreviewInfos = this.likedMediaPreviewInfosFromParent;
  },
  watch : {
    isDeleting:function(val){
      if(val==true){
        showOverLay();
      } else {
        hideOverLay();
      }
    }
  },

}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.bg-black { background-color: black;}
.bg-lightgrey { background-color: rgb(252,252,252);}
.white { color: white;}

.media-preview {
  margin-top: 0;
  padding-top: 25px;
  /* background-color: rgba(255,255,255,0.75);
  box-shadow: 0px 2px 3px lightgrey; */
  border-top: 1.4px dotted lightgrey;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}

.category-icon {
  z-index: -1;
  /* margin-bottom: -2.5px; */
  margin-left: 1px;
  margin-right: 10px;
  padding: 8px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  background-color: black;
  color: white;
}

.mypage-content-wrapper {
  margin-left: 70px;
  width: 80%;
  margin: 0 auto;
  margin-top: 90px;
}

.mypage-action-menu {
  padding: 10px 30px;
  background-color: rgb(255,255,255);
  box-shadow: 0.5px 0.5px 2px lightgrey;
  border-radius: 3px;
}

.mypage-action-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  font-size: 3em;
}
.mypage-action-item:hover {
  cursor: pointer;
}

.media-create-wrapper {
  font-size: 2em;
}

#change-select-mode {
  transition: 0.2s;
}

.select-mode-item-wrapper {
}

.mypage-section {
  margin-top: 60px;
  padding: 5px;
  width: 100%;
  max-width: 1200px;
}

.section-top-wrapper {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
  width: 90%
}

.section-title {
  display: inline-block;
  margin: 0 20px 0 0;
  font-size: 17px;
  font-family: serif;
  /* font-family: "Yu Gothic", "游ゴシック", YuGothic, "游ゴシック体"; */
}

.select-uncheck-icon {
  color: black;
}
.select-uncheck-icon:hover {
  color: blue;
}

.media-create-icon {
  color:white;
  background-color: aquamarine;
  padding: 6px 8px;
  border-radius: 50%;
  box-shadow: 1px 1px 3px grey;
  transition: 0.2s;
}
.media-create-icon:hover {
  background-color: darkturquoise;
  transform: scale(1.01);
  box-shadow: 2px 2px 7px grey;
}
.action-item-subtitle {
  margin-top: 5px;
  font-size:11px;
  color:grey;
}


.preview-img {
  width: 100px;
  height: 100px;
  border: 2px #aaaaaa solid;
  border-radius: 50%;
  margin-right: 20px;
}

.view-more {
  color: blue;
  font-size: 12px;
}

.view-more:hover {
  cursor: pointer;
}


/* スマホ以外 */
@media screen and (min-width: 481px) {
  .for-mobile {
    display: none;
  }

  .mypage-action-item {
    margin: 0 50px 0 0;
  }
 
}


/* スマホ */
@media screen and (max-width: 480px) {
  .for-pc-tablet {
    display: none;
  }

  .media-preview {
    padding-top: 5px;
    padding-left: 0;
  }

  .menu-inner {
    color: white;
    background-color: black;
    border-radius: 5px;
    padding: 7px 10px;
    justify-content: center;
  }

  .select-mode-item-wrapper {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 6px 10px;
    color: white;
    z-index: 15;
    justify-content: center;
    flex-direction: row;
    overflow-x: scroll;
  }

  .mypage-action-menu {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;    
    padding: 0;
    display: flex;
    justify-content: center;
  }


  .mypage-content-wrapper {
    margin-top: 80px;
  }


  .section-title {
    font-size: 13px;
  }


  .mypage-action-item {
    margin: 0 18px;
    font-size: 1.7em;
  }

  .mypage-content-wrapper {
    width: 90%;
  }
  .mypage-section {
    margin-top: 30px;
    padding: 0px;
  }
  .is-black {
    background-color: black;
  }
  .select-uncheck-icon {
    color: white;
  }

  .media-create-wrapper {
    position: fixed;
    bottom: 10px;
    right: 20px;
    margin-right: 0;
    transform: scale(1.2);
  }

  .liked-media-list {
    margin-bottom: 100px;
  }

}

</style>