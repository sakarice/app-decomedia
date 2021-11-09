<template>
  <div class="action-button-wrapper">
    <i class="fas fa-trash fa-3x room-delete-icon" @click="deleteSelectedRoom"></i>
    <span class="action-item-subtitle">削除</span>
    <p v-show="deleteSelectedRoomMessage">
      {{deleteSelectedRoomMessage}}
    </p>
  </div>
</template>

<script>
  export default {
    props : [],
    data : () => {
      return {
        'deleteSelectedRoomMessage' : "",
      }
    },

    methods : {
      getSelectedRoomId(){
        // 作成したRoomといいねしたRoomを結合
        let allRoomInfos = [];
        for(let i=0;i < this.$parent.createdMediaPreviewInfos.length; i++){
          allRoomInfos.push(this.$parent.createdMediaPreviewInfos[i]);
        }
        for(let i=0;i < this.$parent.likedMediaPreviewInfos.length; i++){
          allRoomInfos.push(this.$parent.likedMediaPreviewInfos[i]);
        }
        // 選択されたRoomのみ抽出
        let selectedRoomInfos = allRoomInfos.filter(function(roomInfo){
          return roomInfo['selectedOrderNum'] > 0;
        })
        // 選択された順番(昇順)で並べ替え
        selectedRoomInfos.sort(function(a,b){
          if(a.selectedOrderNum < b.selectedOrderNum) return -1;
          if(a.selectedOrderNum > b.selectedOrderNum) return  1;
          return 0;
        });
        // RoomIdを抽出した配列を作成
        // [id1, id2, id3,...] (一つ前の処理で選択順にsort済み)
        const selectedRoomIds
         = selectedRoomInfos.map(
           selectedRoomInfo => selectedRoomInfo.id
          );
        
        return selectedRoomIds;
      },
      deleteSelectedRoom() {
        const selectedRoomIds = this.getSelectedRoomId();
        selectedRoomIds.forEach(selectedRoomId => {
          alert(selectedRoomId);
        });
        const roomInfo = {
          'selectedRoomIds' : selectedRoomIds,
        }
        const url = '/rooms/destroy';
        axios.post(url, roomInfo)
          .then(response =>{
            alert(response.data.message);
            this.deleteSelectedRoomMessage = "";
            location.reload();
          })
          .catch(error => {
            alert('failed!');
            this.deleteSelectedRoomMessage = "";
          })
      }

    },

  }


</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

.room-delete-icon:hover {
  color: red;
}

.action-item-subtitle {
  margin-top: 5px;
  font-size:11px;
  color:dimgrey;  
}

</style>