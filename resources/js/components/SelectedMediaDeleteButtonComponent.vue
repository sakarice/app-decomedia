<template>
  <div class="action-button-wrapper">
    <i class="fas fa-trash fa-3x media-delete-icon" @click="deleteSelectedMedia"></i>
    <span class="action-item-subtitle">削除</span>
    <p v-show="deleteSelectedMediaMessage">
      {{deleteSelectedMediaMessage}}
    </p>
  </div>
</template>

<script>
  export default {
    props : [],
    data : () => {
      return {
        'deleteSelectedMediaMessage' : "",
      }
    },

    methods : {
      getSelectedMediaId(){
        // 作成したMediaといいねしたMediaを結合
        let allMediaInfos = [];
        for(let i=0;i < this.$parent.createdMediaPreviewInfos.length; i++){
          allMediaInfos.push(this.$parent.createdMediaPreviewInfos[i]);
        }
        for(let i=0;i < this.$parent.likedMediaPreviewInfos.length; i++){
          allMediaInfos.push(this.$parent.likedMediaPreviewInfos[i]);
        }
        // 選択されたMediaのみ抽出
        let selectedMediaInfos = allMediaInfos.filter(function(mediaInfo){
          return mediaInfo['selectedOrderNum'] > 0;
        })
        // 選択された順番(昇順)で並べ替え
        selectedMediaInfos.sort(function(a,b){
          if(a.selectedOrderNum < b.selectedOrderNum) return -1;
          if(a.selectedOrderNum > b.selectedOrderNum) return  1;
          return 0;
        });
        // MediaIdを抽出した配列を作成
        // [id1, id2, id3,...] (一つ前の処理で選択順にsort済み)
        const selectedMediaIds
         = selectedMediaInfos.map(
           selectedMediaInfo => selectedMediaInfo.id
          );
        
        return selectedMediaIds;
      },
      deleteSelectedMedia() {
        const selectedMediaIds = this.getSelectedMediaId();
        selectedMediaIds.forEach(selectedMediaId => {
          alert(selectedMediaId);
        });
        const mediaInfo = {
          'selectedMediaIds' : selectedMediaIds,
        }
        const url = '/medias/destroy';
        axios.post(url, mediaInfo)
          .then(response =>{
            alert(response.data.message);
            this.deleteSelectedMediaMessage = "";
            location.reload();
          })
          .catch(error => {
            alert('failed!');
            this.deleteSelectedMediaMessage = "";
          })
      }

    },

  }


</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

.media-delete-icon:hover {
  color: red;
}

.action-item-subtitle {
  margin-top: 5px;
  font-size:11px;
  color:dimgrey;  
}

</style>