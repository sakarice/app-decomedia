<template>
  <div>

    <section class="mypage-section created-room-list">
      <div class="section-top-wrapper">
        <h3 class="section-title">作成済みRoom</h3>
        <!-- {{-- Room作成 --}} -->
        <div class="room-create-wrapper">
          <a class="linkTo-createRoom" href="room/create">Room作成</a>
        </div>
        <a href="/mypage/myRoomList">もっと見る</a>
      </div>

      <!-- {{-- 作成済みroom一覧 --}} -->
      <room-list-component
        :room-preview-infos='createdRoomPreviewInfos'
        :is-show-cover="true">
      </room-list-component>
      
    </section>

    <section class="mypage-section liked-room-list">
      <div class="section-top-wrapper">
        <h3 class="section-title">いいねしたRoom</h3>
        <a href="/mypage/likedRoomList">もっと見る</a>
      </div>
      <!-- いいねしたroom一覧 -->

      <room-list-component
        :room-preview-infos='likedRoomPreviewInfos'
        :is-show-cover="false">
      </room-list-component>

    </section>
      

  </div>




</template>

<script>
import RoomList from './RoomListComponent.vue';

export default {
  component : {
    RoomList,
  },
  props : [
    'createdRoomPreviewInfos',
    'likedRoomPreviewInfos',
  ],
  data : () => {
    return {
    // 'isShowCover' : true
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
    roomShowLink : function(id) {
      return "/home/room/" + id;
    },
    roomEditLink : function(id) {
      return "/home/room/" + id + "/edit";
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
  mounted : function(){},
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



</style>