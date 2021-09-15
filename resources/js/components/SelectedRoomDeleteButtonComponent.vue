<template>
  <div class="action-button-wrapper">
    <button class="selected-room-delete-button" @click="deleteSelectedRoom">
      選択したRoomを削除
    </button>
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
        for(let i=0;i < this.$parent.createdRoomPreviewInfos.length; i++){
          allRoomInfos.push(this.$parent.createdRoomPreviewInfos[i]);
        }
        for(let i=0;i < this.$parent.likedRoomPreviewInfos.length; i++){
          allRoomInfos.push(this.$parent.likedRoomPreviewInfos[i]);
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

  .selected-room-delete-button {
    z-index: 1;
    font-family: Inter,Noto Sans JP;
    border-radius: 4px;
    border: solid 1px grey;
    box-shadow: 0.5px 0.5px 1px lightslategrey;
  }

  .selected-room-delete-button:hover {
    background-color: aqua;
  }

</style>