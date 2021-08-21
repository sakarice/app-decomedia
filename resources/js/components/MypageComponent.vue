<template>
  <div>

    <div class="select-mode-wrapper">
      <button id="change-select-mode" class="select-mode-item" @click="toggleSelectMode">
        {{selectModeButtonMessage}}
      </button>
      <button class="select-mode-item" v-show="isSelectMode" @click="unCheckAllRoom">
        全ての選択を解除
      </button>
      <room-list-create-button-component
      class="select-mode-item"
      v-show="isSelectMode">
      </room-list-create-button-component>
    </div>

    <section v-show="isShowCreatedRoomPreview" class="mypage-section created-room-list">
      <div class="section-top-wrapper">
        <h3 class="section-title">作成済みRoom</h3>
        <!-- {{-- Room作成 --}} -->
        <div class="room-create-wrapper">
          <a class="linkTo-createRoom" href="room/create">Room作成</a>
        </div>
        <span class="view-more" @click="addCreatedRoomPreviewInfos">
          もっと見る
        </span>
      </div>

      <!-- {{-- 作成済みroom一覧 --}} -->
      <room-list-component
        :room-preview-infos="createdRoomPreviewInfos"
        :is-show-cover="isShowCoverOnCreateRoom"
        :is-select-mode="isSelectMode"
        @changeIsCheckedRoom="changeIsCheckedCreatedRoom"
        ref="createdRoomPreview">
      </room-list-component>
      
    </section>

    <section v-show="isShowLikedRoomPreview" class="mypage-section liked-room-list">
      <div class="section-top-wrapper">
        <h3 class="section-title">いいねしたRoom</h3>
        <span class="view-more" @click="addLikedRoomPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- いいねしたroom一覧 -->

      <room-list-component
        :room-preview-infos="likedRoomPreviewInfos"
        :is-show-cover="isShowCoverOnLikeRoom"
        :is-select-mode="isSelectMode"
        @changeIsCheckedRoom="changeIsCheckedLikedRoom"
        ref="likedRoomPreview">
      </room-list-component>

    </section>
      

    <left-bar-component>
    </left-bar-component>


  </div>




</template>

<script>
import RoomList from './RoomListComponent.vue';
import LeftBar from './LeftBarComponent.vue';
import RoomListCreateButton from './RoomListCreateButtonComponent.vue';

export default {
  components : {
    RoomList,
    LeftBar,
    RoomListCreateButton,
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
      'isShowCoverOnCreateRoom' : true,
      'isShowCoverOnLikeRoom' : false,
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
      return "/home/room/" + id + "/edit";
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
    deleteRoom(room_id){
      let room_data = {
        'room_id' : room_id,
      }
      const url = '/home/room/delete';
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
        return 'Roomを複数選択する';
      }
    }
  }

}
</script>


<style scoped>

.select-mode-wrapper {
  margin-left: 100px;
  display: flex;
  height: 25px;
}

.select-mode-item {
  margin-right: 10px;
}

.mypage-section {
  margin-left: 50px;
  padding: 10px 10px 10px 50px;
  width: 100%;
  max-width: 1200px;
}


.section-title {
  display: inline-block;
  margin-right: 20px;
}


.room-create-wrapper {
  display: inline-block;
  margin: 5px 20px;
}

.linkTo-createRoom {
  font-size: 18px;
  text-decoration: none;
  color: black;
  background-color: aquamarine;
  padding: 3px 20px;
  border-radius: 3px;
  box-shadow: 0.5px 0.5px 3px grey;
}

.linkTo-createRoom:hover {
  color:aquamarine;
  background-color: black;
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



</style>