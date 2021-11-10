<template>
  <div class="action-button-wrapper">
    <button class="medialist-create-button" @click="createMediaList">
      Mediaリストを作成
    </button>
    <p v-show="createMediaListMessage">
      {{createMediaListMessage}}
    </p>
  </div>
</template>

<script>
  export default {
    props : [],
    data : () => {
      return {
        'createMediaListMessage' : "",
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
      createMediaList() {
        let selectedMediaIds = this.getSelectedMediaId();
        selectedMediaIds.forEach(selectedMediaId => {
          alert(selectedMediaId);
        });
        const mediaInfo = {
          'selectedMediaIds' : selectedMediaIds,
        }
        const url = '/medialists/store';
        // this.createMediaListMessage = "mediaリスト情報を保存中です...";
        axios.post(url, mediaInfo)
          .then(response =>{
            alert(response.data.message);
            this.createMediaListMessage = "";
          })
          .catch(error => {
            alert('mediaリストの作成に失敗しました');
            this.createMediaListMessage = "";
          })
      }

    },

  }


</script>


<style scoped>

  /* .action-button-wrapper {
    width: 50%;
    height: 50%;
    position: absolute;
    top: 0;
    right: 0;
  } */

  .medialist-create-button {
    /* position: absolute;
    top : 20px;
    right: 150px; */
    z-index: 1;
    font-family: Inter,Noto Sans JP;
    border-radius: 4px;
    border: solid 1px grey;
    box-shadow: 0.5px 0.5px 1px lightslategrey;
  }

  .medialist-create-button:hover {
    background-color: aqua;
  }

</style>