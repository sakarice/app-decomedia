<template>
  <div class="mypage-content-wrapper">
    <!-- profile -->
    <user-page-profile class="for-pc-tablet"></user-page-profile>

    <!-- Media -->
    <div class="mypage-action-menu">
      <div class="menu-inner flex j-center a-end">

        <!-- メディア表示切り替えボタン -->
        <div class="mypage-category media-disp-switch"
         v-show="!isSelectMode" :class="{'isActive':isShowMedia}">
          <i id="change-media-mode" class="fas fa-object-group" @click="changeActiveCategory('media')"></i>
          <span class="action-item-subtitle">メディア</span>
        </div>
        <!-- フォロー中/フォロワーユーザ表示切り替えボタン -->
        <div class="mypage-category follow-user-disp-switch"
         v-show="!isSelectMode" :class="{'isActive':isShowUser}">
          <i id="change-follow-user-mode" class="fas fa-user-friends" @click="changeActiveCategory('user')"></i>
          <span class="action-item-subtitle">ユーザー</span>
        </div>

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
    <span class="media-action-title for-pc-tablet" v-show="isShowMedia">メディア操作</span>
    <div class="media-action-wrapper flex j-center a-center">
      <!-- Media作成 -->
      <a class="action-btn-wrapper media-create link flex a-center" v-show="isShowMedia && !isSelectMode" href="/media/create">
        <i class="fas fa-plus media-create-icon"></i>
        <span class="media-action-btn-label for-pc-tablet">作成</span>
      </a>
      <!-- 選択モードの切り替えボタン -->
      <div class="action-btn-wrapper select-mode-on flex a-center" v-show="isShowMedia && !isSelectMode" @click="toggleSelectMode">
        <i class="fas fa-check-square select-mode-on-icon"></i>
        <span class="media-action-btn-label select-mode-on-label">{{selectModeButtonMessage}}</span>
      </div>
    </div>

    <!-- 作成済みMediaのプレビュー -->
    <section v-show="isShowMedia && isShowCreatedMedia" class="mypage-section created-media-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <div class="flex a-end">
          <i class="fas fa-tools category-icon"></i>
          <h3 class="section-title">作成したメディア</h3>
          <span class="view-more ml15" @click="addCreatedMediaPreviewInfos">
            ▼さらに表示
          </span>
        </div>

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
    <section v-show="isShowMedia && isShowLikedMedia" class="mypage-section liked-media-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <div class="flex a-end">
          <i class="fas fa-thumbs-up category-icon"></i>
          <h3 class="section-title">いいねしたメディア</h3>
          <span class="view-more" @click="addLikedMediaPreviewInfos">
            ▼さらに表示
          </span>
        </div>
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
      let url = '/addCreatedMediaPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.createdMediaPreviewInfos = response.data.createdMediaPreviewInfos;
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
    setIsDelete(isDelete){this.isDeleting = isDelete;},
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
  border-top: 1.4px dotted lightgrey;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
}

.category-icon {
  z-index: -1;
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
  padding: 2px 0px;
}
.menu-inner{
  border-bottom: 1px solid grey;
}

.mypage-category {
  display: flex;
  flex-direction: column;
  align-items: center;
  color: rgba(0,0,0,0.3);
  font-size: 2em;
}
.mypage-category:hover {
  cursor: pointer;
}


.mypage-section {
  margin-top: 20px;
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



.action-item-subtitle {
  margin-top: 5px;
  font-size:11px;
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
  position: fixed;
  bottom: 10px;
  right: 10px;
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
  padding-top: 5px;
}

.action-btn-wrapper {
  padding: 6px 16px;
  margin: 0 15px;
  color: grey;
  font-size: 1.8em;
  border-radius: 2px;
  box-shadow: 1.5px 1.5px 1px slategrey;
}
.action-btn-wrapper:hover {
  cursor: pointer;
}

.media-create {
  background-color: rgb(100,250,60);
  color: black;
  text-decoration-line: none;
}
.media-create:hover {
  color: white;
}

.select-mode-on {
  background-color: rgb(240,245,245);
}
.select-mode-on:hover {
  color: blue;
}
.select-mode-on-icon{}

.media-action-btn-label{
  color: black;
  margin-left: 8px;
  margin-top: 3px;
  font-size: 14px;
}

.isActive {
  color: blue;
}


/* スマホ以外 */
@media screen and (min-width: 481px) {
  .for-mobile {
    display: none;
  }

  .mypage-category {
    margin: 0 10px;
    padding: 15px 15px 0 15px;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
  }

  .action-item-subtitle {
    color:dimgrey;
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
    padding: 6px 10px 2px 10px;
    background-color: rgb(245,245,245);
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
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


  .mypage-content-wrapper {
    margin-top: 80px;
  }


  .section-title {
    font-size: 15px;
  }


  .mypage-category {
    margin: 0 18px;
    font-size: 1.7em;
  }

  .mypage-content-wrapper {
    width: 90%;
  }

  .media-action-wrapper{
    padding-top: 10px;
  }

  .mypage-section {
    margin-top: 15px;
    margin-bottom: 70px;
  }
  .bg-black {
    background-color: black;
  }

  .media-create {
    position: fixed;
    bottom: 22px;
    right: 0;
    font-size: 22px;
    padding: 7px 8px;
    z-index: 30;
  }

  .liked-media-list {
    margin-bottom: 100px;
  }

  .select-mode-on-wrapper {
    font-size: 1.5em;
  }

  .select-mode-on {
    padding: 2px 15px;
  }
  .select-mode-on-icon {
    font-size: 20px;
  }
  .select-mode-on-label {
    font-size: 13px;
  }


}

</style>