<template>
  <div>

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
        :is-show-cover="isShowCoverOnCreateRoom">
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
        :is-show-cover="isShowCoverOnLikeRoom">
      </room-list-component>

    </section>
      

    <left-bar-component>
    </left-bar-component>


  </div>




</template>

<script>
import RoomList from './RoomListComponent.vue';
import LeftBar from './LeftBarComponent.vue';

export default {
  component : {
    RoomList,
    LeftBar,
  },
  props : [
    'createdRoomPreviewInfosFromParent',
    'likedRoomPreviewInfosFromParent',
  ],
  data : () => {
    return {
      'createdRoomPreviewInfos' : "",
      'likedRoomPreviewInfos' : "",
      'isShowCoverOnCreateRoom' : true,
      'isShowCoverOnLikeRoom' : false,
      'isShowCreatedRoomPreview' : true,
      'isShowLikedRoomPreview' : true,
      'isUpdateCreatedRoomPreviewInfo' : false,
      'isUpdateLikedRoomPreviewInfo' : false,
    }
  },
  methods : {
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

  }

}
</script>


<style scoped>

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