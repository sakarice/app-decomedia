<template>
  <div class="mypage-content-wrapper">
    <!-- profile -->
    <user-page-profile></user-page-profile>

    <!-- Room -->
    <div class="mypage-action-menu">
      <!-- Room作成 -->
      <div class="mypage-action-item room-create-wrapper">
        <a class="linkTo-createRoom" href="/room/create">
          <i class="fas fa-plus fa-3x room-create-icon"></i>
        </a>
        <span class="action-item-subtitle">Room作成</span>
      </div>
      <!-- Roomの選択モード -->
      <!-- 〇選択モードの切り替えボタン -->
      <div class="mypage-action-item select-mode-switch">
        <i id="change-select-mode" class="fas fa-hand-point-up fa-3x" @click="toggleSelectMode"></i>
        <span class="action-item-subtitle">{{selectModeButtonMessage}}</span>
      </div>
      <!-- 〇選択したRoom削除ボタン -->
      <div class="select-mode-item-wrapper" :class='{"is-black": isSelectMode}'>
        <selected-room-delete-button-component
        class="mypage-action-item select-mode-item"
        v-show="isSelectMode">
        </selected-room-delete-button-component>

        <!-- 〇選択をすべて解除するボタン -->
        <div class="mypage-action-item select-mode-item" v-show="isSelectMode">
          <i class="far fa-square fa-3x select-uncheck-icon" @click="unCheckAllRoom"></i>
          <span class="action-item-subtitle">リセット</span>
        </div>
      </div>
      
    </div>

    <!-- 作成済みRoomのプレビュー -->
    <section v-show="isShowCreatedRoomPreview" class="mypage-section created-room-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <h3 class="section-title">作成済みRoom</h3>
        <!-- {{-- Room作成 --}} -->

        <span class="view-more" @click="addCreatedRoomPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- {{-- 作成済みroom一覧 --}} -->
      <room-preview-component
        :room-preview-infos="createdRoomPreviewInfos"
        :is-show-cover="isShowCoverOnCreateRoom"
        :is-select-mode="isSelectMode"
        @changeIsCheckedRoom="changeIsCheckedCreatedRoom"
        ref="createdRoomPreview">
      </room-preview-component>
    </section>

    <!-- いいねしたRoomのプレビュー -->
    <section v-show="isShowLikedRoomPreview" class="mypage-section liked-room-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <h3 class="section-title">いいねしたRoom</h3>
        <span class="view-more" @click="addLikedRoomPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- いいねしたroom一覧 -->
      <room-preview-component
        :room-preview-infos="likedRoomPreviewInfos"
        :is-show-cover="isShowCoverOnLikeRoom"
        :is-select-mode="isSelectMode"
        @changeIsCheckedRoom="changeIsCheckedLikedRoom"
        ref="likedRoomPreview">
      </room-preview-component>
    </section>      

    <mypage-menu-bar-component>
    </mypage-menu-bar-component>


  </div>




</template>

<script>
import RoomPreview from './RoomPreviewComponent.vue';
import RoomListPreview from './RoomListPreviewComponent.vue';
import MypageMenuBar from './MypageMenuBarComponent.vue';
import RoomListCreateButton from './RoomListCreateButtonComponent.vue';
import SelectedRoomDeleteButton from './SelectedRoomDeleteButtonComponent.vue';
import UserPageProfile from './UserPageProfileComponent.vue';

export default {
  components : {
    RoomPreview,
    RoomListPreview,
    MypageMenuBar,
    RoomListCreateButton,
    SelectedRoomDeleteButton,
    UserPageProfile,
  },
  props : [
    'createdRoomPreviewInfosFromParent',
    'likedRoomPreviewInfosFromParent',
  ],
  data : () => {
    return {
      'createdRoomPreviewInfos' : "",
      'likedRoomPreviewInfos' : "",
      'isSelectMode' : false,
      'isShowCoverOnCreateRoomList' : true,
      'isShowCoverOnCreateRoom' : true,
      'isShowCoverOnLikeRoom' : false,
      'isShowCreatedRoomListPreview' : true,
      'isShowCreatedRoomPreview' : true,
      'isShowLikedRoomPreview' : true,
      'isUpdateCreatedRoomPreviewInfo' : false,
      'isUpdateLikedRoomPreviewInfo' : false,
      'totalSelectedCount' : 0,
    }
  },
  methods : {
    toggleSelectMode(){
      this.isShowCoverOnCreateRoom = this.isSelectMode
      this.isSelectMode = !this.isSelectMode;
    },
    roomEditLink : function(id) {
      return "/room/" + id + "/edit";
    },
    addCreatedRoomPreviewInfos(){
      let url = '/addCreatedRoomPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.createdRoomPreviewInfos = response.data.createdRoomPreviewInfos;
        tmpThis.isUpdateCreatedRoomPreviewInfo = true;
      })
      .catch(error => {
        alert('room情報の取得に失敗しました')
      })
    },
    addLikedRoomPreviewInfos(){
      let url = '/addLikedRoomPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.likedRoomPreviewInfos = response.data.likedRoomPreviewInfos;
        tmpThis.isUpdateLikedRoomPreviewInfo = true;
      })
      .catch(error => {
        alert('room情報の取得に失敗しました')
      })
    },
    addCreatedRoomListPreviewInfos(){
      this.$refs.createdRoomListPreview.addCreatedRoomListPreviewInfos(300);
    },
    deleteRoom(room_id){
      let room_data = {
        'room_id' : room_id,
      }
      const url = '/room/delete';
      axios.post(url, room_data)
      .then(response => {
        alert(response.data.message);
        location.reload();
      })
      .catch(error => {
        alert('room削除に失敗しました。');
      })
    },
    changeIsCheckedCreatedRoom(isChecked, index){
      if(isChecked == true){
        this.increaseCreatedRoomSelectedCount(index);
      } else if(isChecked == false){
        let unSelectedOrderNum = this.createdRoomPreviewInfos[index]['selectedOrderNum'];
        this.createdRoomPreviewInfos[index]['selectedOrderNum'] = 0;
        this.decreseRoomSelectedCount(unSelectedOrderNum);
      }
    },
    changeIsCheckedLikedRoom(isChecked, index){
      if(isChecked == true){
        this.increaseLikedRoomSelectedCount(index);
      } else if(isChecked == false){
        let unSelectedOrderNum = this.likedRoomPreviewInfos[index]['selectedOrderNum'];
        this.likedRoomPreviewInfos[index]['selectedOrderNum'] = 0;
        this.decreseRoomSelectedCount(unSelectedOrderNum);
      }
    },
    increaseCreatedRoomSelectedCount(index){
      this.createdRoomPreviewInfos[index]['selectedOrderNum'] = this.totalSelectedCount+1;
      this.totalSelectedCount ++;
    },
    increaseLikedRoomSelectedCount(index){
      this.likedRoomPreviewInfos[index]['selectedOrderNum'] = this.totalSelectedCount+1;
      this.totalSelectedCount ++;
    },
    decreseRoomSelectedCount(unSelectedOrderNum){
      for(let i=0; i < this.createdRoomPreviewInfos.length; i++){
        if(this.createdRoomPreviewInfos[i]['selectedOrderNum'] > unSelectedOrderNum){
          this.createdRoomPreviewInfos[i]['selectedOrderNum'] --;
        }
      }
      for(let i=0; i < this.likedRoomPreviewInfos.length; i++){
        if(this.likedRoomPreviewInfos[i]['selectedOrderNum'] > unSelectedOrderNum){
          this.likedRoomPreviewInfos[i]['selectedOrderNum'] --;
        }
      }
      this.totalSelectedCount --;
    },
    unCheckAllRoom(){
      this.$refs.createdRoomPreview.unCheckAllRoom();
      this.$refs.likedRoomPreview.unCheckAllRoom();
      for(let i=0; i < this.createdRoomPreviewInfos.length; i++){
        this.createdRoomPreviewInfos[i]['selectedOrderNum'] = 0;
      }
      for(let i=0; i < this.likedRoomPreviewInfos.length; i++){
        this.likedRoomPreviewInfos[i]['selectedOrderNum'] = 0;
      }
      this.totalSelectedCount = 0;
    }

  },
  created() {
    },
  mounted() {
    this.createdRoomPreviewInfos = this.createdRoomPreviewInfosFromParent;
    this.likedRoomPreviewInfos = this.likedRoomPreviewInfosFromParent;
  },
  watch : {
  },
  computed : {
    selectModeButtonMessage : function(){
      if(this.isSelectMode){
        return '選択モードを解除';
      } else {
        return 'Roomを選択する';
      }
    }
  }

}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

.mypage-content-wrapper {
  margin-left: 70px;
  width: 80%;
  margin: 0 auto;
}

.mypage-action-menu {
  display: flex;
  align-items: flex-end;
  margin-bottom: 30px;
}

.mypage-action-item {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-right: 30px;
}
.mypage-action-item:hover {
  cursor: pointer;
}

#change-select-mode {
  color: darkgrey;
  transition: 0.2s;
}
#change-select-mode:hover {
  color: black;
}

.select-mode-item-wrapper {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-end;
}

.select-mode-description {
  font-size: 10px;
  margin-top: 7px;
}

.mypage-section {
  padding: 5px;
  width: 100%;
  max-width: 1200px;
}

.section-top-wrapper {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  width: 90%
}

.section-title {
  display: inline-block;
  margin: 0 20px 0 0;
  font-family: "Yu Gothic", "游ゴシック", YuGothic, "游ゴシック体";
}

.select-uncheck-icon {
  color: black;
}
.select-uncheck-icon:hover {
  color: blue;
}

.room-create-icon {
  color:white;
  background-color: aquamarine;
  padding: 6px 8px;
  border-radius: 50%;
  box-shadow: 1px 1px 3px grey;
  transition: 0.2s;
}
.room-create-icon:hover {
  background-color: darkturquoise;
  transform: scale(1.01);
  box-shadow: 2px 2px 7px grey;
}
.action-item-subtitle {
  margin-top: 5px;
  font-size:11px;
  color:dimgrey;
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
}

.view-more:hover {
  cursor: pointer;
  color: aqua;
}


/* スマホ以外 */
@media screen and (min-width: 481px) {
  .for-mobile {
    display: none;
  }
 
}


/* スマホ */
@media screen and (max-width: 480px) {
  .for-pc-tablet {
    display: none;
  }
  .select-mode-item-wrapper {
    position: fixed;
    bottom: 0;
    width: 100%;
    margin-left: -20px;
    padding: 10px 0;
    background-color: black;
    color: white;
    z-index: 15;

    justify-content: center;
  }
  .select-mode-item {
  }

  .mypage-action-menu {
    margin-bottom: 15px;
  }

  .mypage-action-item {
    margin-right: 15px;
    transform: scale(0.7);
  }

  .mypage-content-wrapper {
    margin-left: 20px;
  }
  .mypage-section {
      margin-left: 0;
      padding: 5px;
  }
  .is-black {
    background-color: black;
  }
  .select-uncheck-icon {
    color: white;
  }  

}

</style>