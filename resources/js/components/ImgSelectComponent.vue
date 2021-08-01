<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">
        <div id="drop-zone" 
        :class="{show: isDragEnter, hidden: !(isDragEnter)}"
        @dragleave = "dragLeave"
        @dragover.prevent
        @drop.prevent = "dropFile">
        </div>
        <div id="contents-wrapper"
        @dragenter = "dragEnter">
          <div id="toggle-wrapper">
            <button id="file-category-toggle" tabindex=1 @click="changeFileCategory" :class="{isDefault: isDefault, isUpload: !(isDefault)}">
              <div id="toggle-state-icon"></div>
            </button>
            <div id="category-type"><span>{{fileCategory}}</span></div>
          </div>          
          <div id="upload-input-wrapper">
            <!-- <div id="loading-icon"></div> -->
            <label id="upload-label" for="upload-input" tabindex=2 @keydown.enter="startInput" v-show="!(isDefault)">
              <i class="fas fa-upload" style="margin-right: 5px"></i>
              <span>アップロード</span>
              <input id="upload-input" style="display: none" @change="selectedFile" type="file" accept="image/*" name="img">
            </label>
            <div id="loading-display-wrapper" v-show="isLoading">
              <p class="loading-message">{{loadingMessage}}</p>
              <div id="uploading-dot" :class="{'copy-to-right': isLoading}"></div>
            </div>
          </div>
          <ul id="img-wrapper">
            <!-- uploads -->
            <li :id="index" v-show="!(isDefault)" class="img-list" v-for="(userOwnImg, index) in userOwnImgs" :key="userOwnImg.url">
              <img class="user-own-img" :src="userOwnImg['url']" :alt="userOwnImg['url']" />
              <div class="icon-cover" v-on:click="setRoomImg">
                <i id="delete-img-icon" class="fas fa-times fa-2x" v-on:click.stop="deleteImg"></i>
                <i id="add-img-icon" class="fas fa-plus fa-2x"></i>
              </div>
            </li>
            <!-- default -->
            <li :id="index" v-show="isDefault" class="img-list" v-for="(defaultImg, index) in defaultImgs" :key="defaultImg.url">
              <img class="default-img" :src="defaultImg['url']" :alt="defaultImg['url']" />
              <div class="icon-cover" v-on:click="setRoomImg">
                <!-- <i id="delete-img-icon" class="fas fa-times fa-2x" v-on:click="deleteImg"></i> -->
                <i id="add-img-icon" class="fas fa-plus fa-2x"></i>
              </div>
            </li>

          </ul>

        </div>
      </div>
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-left fa-3x"></i>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props : ['transitionName'],
  data : () => {
    return {
      popMessage : 'メッセージです',
      isDefault : true,
      fileCategory : "default",
      isDragEnter : false,
      uploadFile : "",
      isLoading : false,
      loadingMessage : "",
      userOwnImgs : [],
      // imgFileUrls : [],
      defaultImgs : []
      // defaultImgUrls : []
    }
  },
  methods : {
    changeFileCategory(){
      this.isDefault = !(this.isDefault);
      if(this.isDefault == true){
        this.fileCategory = "default";
      } else {
        this.fileCategory = "uploads";
      }
    },
    getUserOwnImgs(){
      const url = '/ajax/getUserOwnImgs';
      axios.get(url)
        .then(response => {
          // alert(response.data.urls[0]);
          response.data.file_datas.forEach(file_data => {            
            this.userOwnImgs.unshift(file_data);
          });
        })
        .catch(error => {
          alert('画像取得失敗');
        })
    },
    getDefaultImgs(){
      const url = '/ajax/getDefaultImgs';
      axios.get(url)
        .then(response => {
          // alert(response.data.urls[0]);
          response.data.file_datas.forEach(file_data => {            
            this.defaultImgs.unshift(file_data);
          });
        })
        .catch(error => {
          alert('画像取得失敗');
        })
    },
    dragEnter: function() {
      this.isDragEnter = true;
    },
    dragLeave: function() {
      this.isDragEnter = false;
    },
    // 右上の×ボタンクリック時のイベント
    closeModal() {
      this.$emit('close-modal');
    },
    setRoomImg: function(event){
      let imgUrl = event.target.previousElementSibling.getAttribute('src');
      let imgTypeLabel = event.target.previousElementSibling.getAttribute('class');
      let imgType;
      if(imgTypeLabel == 'default-img'){
        imgType = 1;
      } else if(imgTypeLabel == 'user-own-img'){
        imgType = 2;
      }
      let imgId = this.findImgIdTiedUpWithUrl(imgType, imgUrl);      
      
      this.$emit('set-room-img', imgType, imgId, imgUrl);
    },
    findImgIdTiedUpWithUrl(imgType, imgUrl){
      let targetModel;  // 検索対象のVueモデル
      switch(imgType){ // 指定されたタイプに応じてVueモデルを決定
        case 1:
          targetModel = this.defaultImgs;
          break;
        case 2:
          targetModel = this.userOwnImgs;
          break;
      }
      let imgNum = targetModel.length; // =検索ループ回数
      let imgId;
      for(let i=0; i < imgNum; i++){
        if(targetModel[i]['url'] == imgUrl){
          imgId = targetModel[i]['id'];
        }
      }

      return imgId;
    },
    startInput(event){
      let target = document.getElementById('upload-input');
      target.click();
    },
    // アップロードされたファイルをdataに保存する
    selectedFile: function(e) {
      e.preventDefault();
      let files = e.target.files;
      this.uploadFile = files[0];
      this.uploadImg();
    },
    dropFile() {
      this.uploadFile = [...event.dataTransfer.files][0];
      this.uploadImg();
      this.isDragEnter = false;
    },
    // ajaxで画像ファイルを送る
    uploadImg() {
      const url = '/ajax/uploadImg';
      let formData = new FormData();
      formData.append('img', this.uploadFile);
      this.loadingMessage = 'アップロード中'
      this.isLoading = true;
      // アイコンを回転させてローディング中であることを表現
      // const loading_icon = document.getElementById('loading-icon');
      // loading_icon.classList.add('rotate');
      axios.post(url, formData)
        .then(response => {
          this.userOwnImgs.unshift(response.data.img_file_info);
          this.uploadFile = "";
          this.loadingMessage = '';
          this.isLoading = false;
          // loading_icon.classList.remove('rotate');
          this.dragLeave();
        })
        .catch(error => {
          alert('アップロード失敗');
          this.loadingMessage = ''
          this.isLoading = false;  
        });
    },
    // 画像ファイルを削除する
    deleteImg:function(event) {
      let index = event.target.parentNode.parentNode.getAttribute('id');
      let imgUrl = this.userOwnImgs[index]['url'];
      let imgId = this.findImgIdTiedUpWithUrl('1', imgUrl);
      const url = '/ajax/deleteImg'
      const params = {
        'imgId' : +imgId, // +を付けて文字列⇒数値に型変換
        'imgUrl' : imgUrl
      }
      this.loadingMessage = '削除中'
      this.isLoading = true;
      axios.post(url, params)
        .then(response => {
          alert(response.data);
          // 画面に即自反映するため、画像URLをdataから削除
          this.userOwnImgs.splice(index,1);
          this.loadingMessage = ''
          this.isLoading = false;
          // Room画像と同じだった場合は削除する必要があるので、親コンポーネントに通知
          this.$emit('img-del-notice', imgUrl);
        })
        .catch(error => {
          alert('画像削除失敗');
          this.loadingMessage = ''
          this.isLoading = false;

        })

    },
    
  },
  mounted() {
    this.getUserOwnImgs();
    this.getDefaultImgs();
  },

}
</script>


<style scoped>

@import "../../css/roomEditModals.css";

  #img-wrapper {
    /* モーダル内の画像サムネの配置 */
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    align-items: center;
    justify-content: space-between;

    width: 92%;
    height: 80vh;
    /* margin-top: 20px; */
    padding-left: 0;
    overflow-y: scroll;
  }

  .img-list {
    position: relative;
    width: 49.5%;
    height: 140px;
    margin-bottom: 2px;
    border-radius: 5px;
    list-style: none;
    transition: transform 0.3s;
    background-color: grey;
  }

  .img-list:hover {
    cursor: pointer;
    transform: scale(0.98,0.98);
  }

  .img-list:hover .icon-cover {
    z-index: 2;
    background-color: rgba(130, 130, 130, 0.6);
  }


  .icon-cover {
    position: absolute ;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 100%;
    background-color: rgba(130, 130, 130, 0);

    /* 要素の配置 */
    display: flex;
    align-items: center;
    justify-content: center;

  }

  #delete-img-icon {
    position: absolute;
    top: 0;
    left: 0;
    margin-left: 5px;
    margin-top: 5px;
    color: rgba(255, 255, 255, 0.7);
  }

  #add-img-icon {
    color: rgba(255, 255, 255, 0.7);
    pointer-events: none;
  }

  .user-own-img,.default-img{
    width: 100%;
    height: 140px;
  }


</style>