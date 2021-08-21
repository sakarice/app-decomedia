<template>
  <ul class="room-wrapper">
    <li v-for="(roomPreviewInfo,index) in roomPreviewInfos" :key="roomPreviewInfo.id">
      <div class="preview-room" :id="roomPreviewInfo['id']">
        <a :href="roomShowLink(roomPreviewInfo['id'])">
          <img class="room-thumbnail" :src="roomPreviewInfo['preview_img_url']" alt="">
        </a>

        <div class="cover-menu" v-show="isShowCover">
          <a :href="roomShowLink(roomPreviewInfo['id'])" class="cover-menu-link">
            <span class="link-title">閲覧</span>
          </a>
          <a :href="roomEditLink(roomPreviewInfo['id'])" class="cover-menu-link">
            <span class="link-title">編集</span>
          </a>
        </div>
        <i class="fas fa-trash del-icon" @click="deleteRoom(roomPreviewInfo['id'])" v-show="isShowCover"></i>

        <!-- Room選択用チェックボックス -->
        <div class="check-box-cover" v-show="isSelectMode">
          <input type="checkbox" class="room-select-check" name="" @change="changeIsCheckedRoom($event, index)">
        </div>

        <!-- 選択順 -->
        <div class="selected-order-num-wrapper"
        v-show="isSelectMode && roomPreviewInfo['selectedOrderNum'] > 0">
          <span class="selected-order-num">{{roomPreviewInfo['selectedOrderNum']}}</span>
        </div>

        <p class="room-title">{{roomPreviewInfo['name']}}</p>
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  props : [
    'roomPreviewInfos',
    'isShowCover',
    'isSelectMode',
  ],
  data : () => {
    return {
      'isShowSelectedOrderNum' : false,
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
    },
    changeIsCheckedRoom(event, index){
      let isChecked = event.target.checked;
      this.$emit('changeIsCheckedRoom', isChecked, index);
    },
    unCheckAllRoom(){
      let checkBoxList = document.querySelectorAll(".room-select-check");
      for(let i=0; i < checkBoxList.length; i++){
        checkBoxList[i].checked = false;
      }
    },
    judgeIsChecked(roomPreviewInfo){
      if(roomPreviewInfo['selectedOrderNum'] > 0){
        return true;
      }
    },

  },
  mounted : function(){},
  watch : {
  },
  computed : {

  }

}
</script>


<style scoped>

.room-wrapper {
  display: flex;
  justify-content:flex-start;
  flex-wrap: wrap;
  width: 100%;
  max-width: 1200px;
}

/* ★★flex-boxで横並び感覚を等間隔にした場合の設定 */
/* .room-wrapper::after {
  display: block;
  content:"";
  width: 180px;
} */

li {
  list-style: none;
}

.preview-room {
  position: relative;
  text-align: center;
  /* font-size: 50px; */
  margin-right: 40px;
  margin-bottom: 80px;
  width: 180px;
  height: 180px;
  opacity: 0.8;
}

.preview-room:hover {
  opacity: 1;
  transform: scale(0.98,0.98);

}

.preview-room:hover .cover-menu {
  opacity: 0.7;
  z-index: 1;
}

.preview-room:hover .del-icon {
  opacity: 0.5;
  z-index: 2;
}


.room-thumbnail {
  /* position: absolute; */
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.cover-menu {
  position: absolute;
  top : 0;
  z-index: -10;
  width: 100%;
  height: 100%;
  opacity: 0%;
  background-color: grey;
  display: flex;
}

.cover-menu-link {
  width: 50%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  /* background-color: rgba(100, 100, 100, 0.5); */
  background-color: seashell;
  opacity: 0.5;
  text-decoration: none;
}

.link-title {
  font-size: 25px;
}

.cover-menu-link:hover {
  /* background-color: rgba(100, 100, 100, 0.8); */
  opacity: 0.8;
}

.cover-menu-link:hover .link-title {
  color: aqua;
}

.del-icon {
  position: absolute;
  top: 10px;
  right: 10px;
  opacity: 0%;
  z-index: -10;
  font-size: 1.5em;
}

.del-icon:hover {
  color: red;
  opacity: 0.8;
}

.check-box-cover {
  position: absolute;
  top : 0;
  z-index: 10;
  width: 100%;
  height: 100%;
  opacity: 0.6;
  background-color: grey;
  display: flex;  
}
.check-box-cover:hover{
  opacity: 0.8;
}

.room-select-check {
  position: absolute;
  top: 10px;
  left: 10px;
  z-index: 10;
  transform: scale(3);
}

.selected-order-num-wrapper {
  position: absolute;
  top: 0px;
  left: 0px;
  z-index: 10;
  width: 100%;
  height: 100%;
  pointer-events: none;

  display: flex;
  justify-content: center;
  align-items: center;
}

.selected-order-num{
  font-size: 80px;
  color: aquamarine;
}

.room-title {
  text-align: center;
  font-size: 25px;
  margin: 0 0;
  font-family: 'Yu Mincho';
}


</style>