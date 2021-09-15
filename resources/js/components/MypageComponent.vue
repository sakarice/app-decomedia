<template>
  <div class="mypage-content-wrapper">
    <!-- Room -->
    <!-- Roomの選択モード -->
    <div class="room-create-wrapper">
      <a class="linkTo-createRoom" href="/room/create">Room作成</a>
    </div>
    <div class="select-mode-wrapper">
      <!-- 〇選択モードの切り替えボタン -->
      <div class="select-mode-item">
        <button id="change-select-mode" class="mypage-button" @click="toggleSelectMode">
          {{selectModeButtonMessage}}
        </button>
      </div>
      <!-- 〇選択をすべて解除するボタン -->
      <div class="select-mode-item">
        <button class="mypage-button" v-show="isSelectMode" @click="unCheckAllRoom">
          全ての選択を解除
        </button>
      </div>
      <!-- 〇選択したRoomからRoomリストを作成するボタン -->
      <room-list-create-button-component
      class="select-mode-item"
      v-show="isSelectMode">
      </room-list-create-button-component>
      <!-- 〇選択したRoom削除ボタン -->
      <selected-room-delete-button-component
      class="select-mode-item"
      v-show="isSelectMode">
      </selected-room-delete-button-component>
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

    <!-- Roomリスト -->
    <!-- 作成済みRoomリストのプレビュー -->
    <section v-show="isShowCreatedRoomListPreview" class="mypage-section created-room-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <h3 class="section-title">作成済みRoomリスト</h3>
        <span class="view-more" @click="addCreatedRoomListPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- {{-- 作成済みroom一覧 --}} -->
      <room-list-preview-component
        :first-preview-num="3"
        :is-show-cover="isShowCoverOnCreateRoomList"
        :is-select-mode="isSelectMode"
        ref="createdRoomListPreview">
      </room-list-preview-component>
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

export default {
  components : {
    RoomPreview,
    RoomListPreview,
    MypageMenuBar,
    RoomListCreateButton,
    SelectedRoomDeleteButton,
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

.mypage-content-wrapper {
  margin-left: 70px;
}

.select-mode-wrapper {
  display: flex;
  flex-wrap: wrap;
  margin-bottom: 25px;
}

.mypage-button {
  z-index: 1;
  font-family: Inter,Noto Sans JP;
  border-radius: 4px;
  border: solid 1px grey;
  box-shadow: 0.5px 0.5px 1px lightslategrey;
}
.mypage-button:hover {
  background-color: aqua;
}

.select-mode-item {
  margin: 0 10px 10px 0;
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
}


.room-create-wrapper {
  display: inline-block;
  margin-bottom: 25px;
}
.room-create-wrapper:hover {
  transform: translate(0.7px, 0.5px);
}

.linkTo-createRoom {
  text-decoration: none;
  font-size: 18px;
  font-weight: bold;
  font-family: "Meiryo";
  color: white;
  background-color: rgb(25,95,150);
  padding: 3px 20px;
  border-radius: 4px;
  box-shadow: 2px 2px 4px grey;
}

.linkTo-createRoom:hover {
  background-color: rgb(245,50,110);
  box-shadow: 1.5px 1.5px 3px darkgrey;

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
}


/* スマホ */
@media screen and (max-width: 480px) {
  .select-mode-wrapper {
    width: 85%;
  }
  .select-mode-item {
    width: 45%;
    margin: 0 10px 10px 0;
  }
  .mypage-content-wrapper {
    margin-left: 20px;
  }
  .mypage-section {
      margin-left: 0;
      padding: 5px;
  }
.linkTo-createRoom {
  font-size: 14px;
  padding: 6px 18px;
}
  
}

</style>