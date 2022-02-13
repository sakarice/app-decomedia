<template>
  <div id="mypage-wrapper" class="flex">
    <!-- profile -->
    <!-- <user-page-profile class="for-pc-tablet"></user-page-profile> -->

    <!-- カテゴリメニュー -->
    <nav id="category-wrapper" class="flex column">
      <!-- メディア表示切り替えボタン -->
      <div class="mypage-category media-disp-switch flex a-center"
       @click="changeActiveCategory('media')"
        v-show="!isSelectMode" :class="{'isActive':isShowMedia}">
        <i id="change-media-mode" class="fas fa-bullhorn"></i>
        <span class="action-item-subtitle">メディア</span>
      </div>
      <!-- フォロー中/フォロワーユーザ表示切り替えボタン -->
      <div class="mypage-category follow-user-disp-switch flex a-center"
       @click="changeActiveCategory('user')"
        v-show="!isSelectMode" :class="{'isActive':isShowUser}">
        <i id="change-follow-user-mode" class="fas fa-user-friends"></i>
        <span class="action-item-subtitle">ユーザー</span>
      </div>
    </nav>

    <div id="mypage-contents-wrapper" class="flex column">
      <!-- Media -->
      <div class="mypage-action-menu">
        <div class="menu-inner flex j-center a-end">

          <!-- Mediaの選択モード -->
          <!-- 選択したMedia削除ボタン -->
          <div class="select-mode-item-wrapper flex" v-show="isSelectMode">
            <selected-media-delete-button-component
            class="select-mode-item"
            v-on:set-is-delete="setIsDelete">
            </selected-media-delete-button-component>
            <!-- 選択をすべて解除するボタン -->
            <div class="select-mode-item uncheck-all" @click="unCheckAllMedia">
              <i class="fas fa-undo select-mode-icon"></i>
              <span class="select-mode-item-subtitle">リセット</span>
            </div>
            <!-- 選択モードキャンセル -->
            <div class="select-mode-item select-mode-cancel" @click="toggleSelectMode">
              <i class="far fa-window-close select-mode-icon"></i>
              <span class="select-mode-item-subtitle">{{selectModeButtonMessage}}</span>
            </div>
          </div>

        </div>
      </div>

      <!-- フォロワーとフォロー中ユーザ -->
      <follower-and-following v-show="isShowUser">
      </follower-and-following>


      <!-- メディアの操作ボタン -->
      <div class="media-action-wrapper flex j-center a-start" :class="{'Transparent':(!isShowMedia || isSelectMode)}" >
        <!-- 選択モードの切り替えボタン -->
        <div class="action-btn-wrapper select-mode-on flex column a-center" @click="toggleSelectMode">
          <i class="fas fa-check select-mode-on-icon"></i>
          <span class="media-action-btn-label select-mode-on-label">{{selectModeButtonMessage}}</span>
        </div>
        <!-- Media作成 -->
        <a class="action-btn-wrapper media-create link flex a-center" v-show="isShowMedia && !isSelectMode" href="/media/create">
          <i class="fas fa-plus media-create-icon"></i>
          <span class="media-action-btn-label to-create-media-label">新しいメディア</span>
        </a>
      </div>

      <!-- 作成済みMediaのプレビュー -->
      <section class="mypage-section created-media-list"
      v-show="isShowMedia">
        <!-- 説明やもっと見るの表示 -->
        <div class="section-top-wrapper"
        @click="isShowCreatedMedia=!isShowCreatedMedia">
          <div class="flex a-end">
            <i class="fas fa-tools category-icon"></i>
            <h3 class="section-title">作成済み</h3>
            <span class="view-more ml15 z1" @click.stop="addCreatedMediaPreviewInfos">
              さらに表示
            </span>
          </div>
          <span class="show-hide-icon mr15 deg-90" :class="{'deg90':!isShowCreatedMedia}">≫</span>
        </div>

        <!-- {{-- 作成済みmedia一覧 --}} -->
        <media-preview-component class="media-preview"
          v-show="isShowCreatedMedia"
          :media-preview-infos="createdMediaPreviewInfos"
          :is-show-cover="isShowCoverOnCreateMedia"
          :is-select-mode="isSelectMode"
          v-on:set-is-delete="setIsDelete"
          @changeIsCheckedMedia="changeIsCheckedCreatedMedia"
          ref="createdMediaPreview">
        </media-preview-component>
      </section>

      <!-- いいねしたMediaのプレビュー -->
      <section class="mypage-section liked-media-list"
      v-show="isShowMedia">
        <!-- 説明やもっと見るの表示 -->
        <div class="section-top-wrapper"
        @click="isShowLikedMedia=!isShowLikedMedia">
          <div class="flex a-end">
            <i class="fas fa-thumbs-up category-icon"></i>
            <h3 class="section-title">お気に入り</h3>
            <span class="view-more z1" @click.stop="addLikedMediaPreviewInfos">
              さらに表示
            </span>
          </div>
          <span class="show-hide-icon mr15 deg-90" :class="{'deg90':!isShowLikedMedia}">≫</span>
        </div>
        <!-- いいねしたmedia一覧 -->
        <media-preview-component class="media-preview"
          v-show="isShowLikedMedia"
          :media-preview-infos="likedMediaPreviewInfos"
          :is-show-cover="isShowCoverOnLikeMedia"
          :is-select-mode="isSelectMode"
          @changeIsCheckedMedia="changeIsCheckedLikedMedia"
          ref="likedMediaPreview">
        </media-preview-component>
      </section>

      <overlay></overlay>
      <div v-show="isDeleting">
        <loading
        message="メディアを削除中です...">
        </loading>
      </div>

    </div>

  </div>




</template>

<script>
import MediaPreview from '../MediaPreviewComponent.vue';
import MediaListPreview from '../MediaListPreviewComponent.vue';
import MediaListCreateButton from '../MediaListCreateButtonComponent.vue';
import SelectedMediaDeleteButton from './SelectedMediaDeleteButtonComponent.vue';
import UserPageProfile from './UserPageProfileComponent.vue';
import FollowerAndFollowing from '../FollowerAndFollowingComponent.vue';
import Overlay from '../common/OverlayComponent.vue';
import Loading from '../common/LoadingComponent.vue';
import { showOverLay, hideOverLay } from '../../functions/overlayDispHelper';


export default {
  components : {
    MediaPreview,
    MediaListPreview,
    MediaListCreateButton,
    SelectedMediaDeleteButton,
    UserPageProfile,
    FollowerAndFollowing,
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
      'activeCategory' : "media",
      'isSelectMode' : false,
      'isShowCreatedMedia' : true,
      'isShowLikedMedia' : true,
      'isShowCoverOnLikeMedia' : false,
      'totalSelectedCount' : 0,
      'isDeleting' : false,
    }
  },
  computed : {
    selectModeButtonMessage : function(){
      return this.isSelectMode ? 'キャンセル' : '選択';
    },
    isShowCoverOnCreateMedia : function(){
      return this.isSelectMode;
    },
    isShowUser:function(){ return this.activeCategory=="user" ? true : false; },
    isShowMedia:function(){ return this.activeCategory=="media" ? true : false; },
  },
  methods : {
    toggleSelectMode(){this.isSelectMode = !this.isSelectMode;},
    changeActiveCategory(category){
      this.activeCategory = category;
    },
    mediaEditLink : function(id) {
      return "/media/" + id + "/edit";
    },
    addCreatedMediaPreviewInfos(){
      const url = '/addCreatedMediaPreviewInfos';
      const tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.createdMediaPreviewInfos = response.data.createdMediaPreviewInfos;
      })
      .catch(error => {
        alert('media情報の取得に失敗しました')
      })
    },
    addLikedMediaPreviewInfos(){
      const url = '/addLikedMediaPreviewInfos';
      const tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.likedMediaPreviewInfos = response.data.likedMediaPreviewInfos;
      })
      .catch(error => {
        alert('media情報の取得に失敗しました')
      })
    },
    addCreatedMediaListPreviewInfos(){
      this.$refs.createdMediaListPreview.addCreatedMediaListPreviewInfos(300);
    },
    deleteMedia(media_id){
      const media_data = {
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
    setIsDelete(isDelete){this.isDeleting = isDelete;},
    changeIsCheckedCreatedMedia(isChecked, index){
      if(isChecked == true){
        this.increaseCreatedMediaSelectedCount(index);
      } else if(isChecked == false){
        const unSelectedOrderNum = this.createdMediaPreviewInfos[index]['selectedOrderNum'];
        this.createdMediaPreviewInfos[index]['selectedOrderNum'] = 0;
        this.decreseMediaSelectedCount(unSelectedOrderNum);
      }
    },
    changeIsCheckedLikedMedia(isChecked, index){
      if(isChecked == true){
        this.increaseLikedMediaSelectedCount(index);
      } else if(isChecked == false){
        const unSelectedOrderNum = this.likedMediaPreviewInfos[index]['selectedOrderNum'];
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

.Transparent { opacity: 0;}
.bg-black { background-color: black;}
.bg-lightgrey { background-color: rgb(252,252,252);}
.white { color: white;}

.media-preview {
  margin-top: 0;
  padding-top: 25px;
  border-top: 1.4px dotted lightgrey;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}

#category-wrapper {
  width: 200px;
  height: 100vh;
  padding: 15px 10px 15px 0px;
  margin-right: 20px;
  border-right: 1px solid black;
}

.category-icon {
  margin-left: 1px;
  margin-right: 10px;
  padding: 8px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
  background-color: black;
  color: white;
}

#mypage-wrapper {
  width: 100%;
  margin: 0 auto;
  margin-top: 90px;
}

.mypage-action-menu {
  padding: 2px 0px;
}

.mypage-category {
  color:dimgray;
}

.mypage-category:hover {
  cursor: pointer;
}


.mypage-section {
  margin-top: 20px;
  margin-bottom: 20px;
  width: 100%;
  max-width: 1200px;
}

.section-top-wrapper {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
  justify-content: space-between;
  width: 100%
}
.section-top-wrapper:hover {
  background-color: rgb(240,240,245);
  cursor: pointer;
}
.section-top-wrapper:hover .show-hide-icon{
  color: red;
}

.section-title {
  display: inline-block;
  margin: 0 20px 0 0;
  font-size: 17px;
  font-family: serif;
}

.uncheck-all:hover .select-mode-icon{
  color: blue;
}
.select-mode-cancel:hover .select-mode-icon{
  color: black;
}

#mypage-contents-wrapper {
  width: 80%;
  height: 100vh;
}


.action-item-subtitle {
  font-size:16px;
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
  font-size: 13px;
}

.view-more:hover {
  cursor: pointer;
}

.select-mode-item-wrapper {
  position: absolute;
  padding: 6px 10px 2px 10px;
  background-color: dimgray;
  color: aliceblue;
  border-radius: 5px;
  z-index: 15;
  justify-content: center;
  flex-direction: row;
}

.select-mode-item {
  margin: 0 18px;
  padding: 5px 0;
  min-width: 40px;
  font-size: 22px;
  color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.select-mode-item:hover {
  cursor: pointer;
}

.select-mode-item-subtitle{
  margin-top: 5px;
  font-size: 11px;
}

.media-action-title {
  color: darkslategrey;
  font-size: 13px;
}

.media-action-wrapper{
  padding: 10px 0 25px 0;
  justify-content: space-between;
}

.action-btn-wrapper {
  padding: 4px 18px;
  color: grey;
  border-radius: 2px;
  box-shadow: 0.5px 0.5px 1px lightgrey;
}
.action-btn-wrapper:hover {
  cursor: pointer;
}

.media-create {
  background-color: rgb(100,250,250);
  margin-right: 20px;
  color: black;
  text-decoration-line: none;
}
.media-create:hover {
  color: white;
}

.media-create-icon, .select-mode-on-icon {
  font-size: 16px;
}

.select-mode-on {
  border-radius: 0%;
  box-shadow: 1px 1px 1px 1px lightgrey;
}
.select-mode-on:hover {
  color: red;
}
.select-mode-on-label {
  margin-top: 3px;
  font-size: 12px;
}

.to-create-media-label {
  margin-left: 7px;
  margin-top: 3px;
  font-size: 14px;
  font-weight: bold;
}

.media-action-btn-label{
  color: black;
}

.isActive {
  /* color: blue; */
  background-color: black;
  color: white;
}

.deg-90 {
  transform: rotate(-90deg);
}
.deg90 {
  transform: rotate(90deg);
}


/* スマホ以外 */
@media screen and (min-width: 481px) {

  .for-mobile {
    display: none;
  }

  #mypage-contents-wrapper {
    overflow-y: scroll;
  }

  .mypage-category {
    margin: 0 0 10px 0;
    padding: 10px 20px;
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
  }

  .select-mode-item-wrapper {
    top: 100px;
  }

  .action-item-subtitle {
    margin-left: 15px;
    font-family: monospace;
  }

}


/* スマホ */
@media screen and (max-width: 480px) {
  .for-pc-tablet {
    display: none;
  }

  .media-preview {
    padding-top: 15px;
    padding-left: 0;
  }

  .menu-inner {
    width: 100%;
    padding: 6px 10px 2px 10px;
    /* background-color: rgb(245,245,245); */
    background-color: black;
    border:none;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    justify-content: space-around;
  }

  #category-wrapper {
    position: fixed;
    left: 0;
    bottom : 0;
    z-index: 1;
    height: auto;
    width: 100%;
    padding: 8px 10px 8px 10px;
    background-color: black;
    border:none;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    flex-direction: row;
    align-items: center;
    justify-content: space-around;
  }

  .select-mode-item-wrapper {
    bottom: 0;
    left: 0;
    right: 0;
    overflow-x: scroll;
  }

  .select-mode-item {
    min-width: 60px;
  }


  .mypage-action-menu {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;    
    padding: 0;
    z-index: 2;
    display: flex;
    justify-content: center;
  }


  #mypage-wrapper {
    margin-top: 80px;
  }

  .section-title {
    font-size: 15px;
  }

  .mypage-category {
    flex-direction: column;
    width: 45%;
    font-size: 1.4em;
  }

  #mypage-wrapper {
    width: 90%;
    margin-top: 70px;
    align-items: center;
  }

  .media-action-wrapper{
    padding: 3px;
    flex-direction: row-reverse;
  }

  .action-btn-wrapper {
    /* flex-direction: column; */
  }

  .mypage-section {
    margin-bottom: 0px;
  }
  .bg-black {
    background-color: black;
  }

  .action-item-subtitle {
    margin-top: 2px;
    font-size: 10px;
  }

  .media-create {
    position: fixed;
    bottom: 62px;
    right: -10px;
    z-index: 11;
    padding: 12px 14px;
    border-radius: 50%;
  }

  .media-create-icon {
    font-size: 26px;
  }

  .to-create-media-label{
    display: none;
  }

  .liked-media-list {
    margin-bottom: 60px;
  }

  .select-mode-on-wrapper {
    font-size: 1.5em;
  }

  .select-mode-on {
    padding: 1px 9px;
    margin-right: 5px;
  }
  .select-mode-on-icon {
    font-size: 20px;
  }
  .select-mode-on-icon {
    font-size: 12px;
    margin-top: 3px;
  }

  #mypage-contents-wrapper {
    width: 100%
  }

  .isActive {
    color: white;
  }



}

</style>