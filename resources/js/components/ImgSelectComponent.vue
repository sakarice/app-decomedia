<template>
  <transition name="right-slide">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
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
            <li v-show="!(isDefault)" class="img-list" v-for="imgFileUrl in imgFileUrls" :key="imgFileUrl.id">
              <img class="user-own-img" :src="imgFileUrl" :alt="imgFileUrl" />
              <div class="icon-cover" v-on:click="sendImgFileUrl">
                <i id="delete-img-icon" class="fas fa-times fa-2x" v-on:click="deleteImg"></i>
                <i id="add-img-icon" class="fas fa-plus fa-2x"></i>
              </div>
            </li>
            <!-- default -->
            <li v-show="isDefault" class="img-list" v-for="defaultImgUrl in defaultImgUrls" :key="defaultImgUrl.id">
              <img class="user-own-img" :src="defaultImgUrl" :alt="defaultImgUrl" />
              <div class="icon-cover" v-on:click="sendImgFileUrl">
                <!-- <i id="delete-img-icon" class="fas fa-times fa-2x" v-on:click="deleteImg"></i> -->
                <i id="add-img-icon" class="fas fa-plus fa-2x"></i>
              </div>
            </li>

          </ul>

        </div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  data : () => {
    return {
      popMessage : 'メッセージです',
      isDefault : true,
      fileCategory : "default",
      isDragEnter : false,
      uploadFile : "",
      isLoading : false,
      loadingMessage : "",
      imgFileUrls : [],
      defaultImgUrls : []
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
          response.data.urls.forEach(url => {            
            this.imgFileUrls.unshift(url);
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
          response.data.urls.forEach(url => {            
            this.defaultImgUrls.unshift(url);
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
    sendImgFileUrl: function(event){
      let imgUrl = event.target.previousElementSibling.getAttribute('src');
      this.$emit('set-img-url', imgUrl);
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
          this.imgFileUrls.unshift(response.data.url);
          alert(response.data.url);
          this.uploadFile = "";
          this.loadingMessage = ''
          this.isLoading = false;
          // loading_icon.classList.remove('rotate');
          this.dragLeave();
        })
        .catch(error => {
          alert('アップロード失敗');
        });
    },
    // 画像ファイルを削除する
    deleteImg:function(event) {
      const url = '/ajax/deleteImg'
      let imgUrl = event.target.parentNode.previousElementSibling.getAttribute('src');
      const params = {
        'imgUrl' : imgUrl
      }
      this.loadingMessage = '削除中'
      this.isLoading = true;
      // const loading_icon = document.getElementById('loading-icon');
      // loading_icon.classList.add('rotate');
      // alert(imgUrl);
      axios.post(url, params)
        .then(response => {
          alert(response.data);
          // 画面に即自反映するため、画像URLをdataから削除
          // 削除対象URLが入っている配列のインデックスを取得
          let index = this.imgFileUrls.some(function(v, i){
            if(v==imgUrl) {
              return (i);
            };
          });
          // 配列から削除
          this.imgFileUrls.splice(index,1);
          this.loadingMessage = ''
          this.isLoading = false;
          // loading_icon.classList.remove('rotate');
          // Room画像と同じだった場合は削除する必要があるので、親コンポーネントに通知
          this.$emit('img-del-notice', imgUrl);
        })
        .catch(error => {
          alert('画像削除失敗');
        })

    }


  },
  mounted() {
    this.getUserOwnImgs();
    this.getDefaultImgs();
  }

}
</script>


<style>

  #select-modal {
    position: fixed;
    top: 0;
    right: 0;
    z-index: 2;
    width: 400px;
    height: 100vh;

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    flex-flow: row;

  }

  #toggle-wrapper {
    display: flex;
    margin-bottom: 20px;
  }

  #file-category-toggle {
    width: 50px;
    height: 24px;
    outline: none;
    border: none;
    border-radius: 50px;
    padding: 2px 2px;
    background-color: plum;
  }
  #file-category-toggle:focus {
    box-shadow: 0 0 0 1px grey;
  }

  #category-type {
    width: 60px;
    margin-left: 10px;
    color: grey;
    display: flex;
    align-items: center;
  }

  .isUpload {
    animation-name: change-toggle-left-to-right;
    animation-duration: 0.2s;
    animation-timing-function: ease-out;
    animation-fill-mode: forwards; 
  }
  @keyframes change-toggle-left-to-right{
    0% {
      background-color: plum;
      padding-left: 2px;
    }    
    100% {
      background-color:paleturquoise;
      padding-left: 28px;
    }
  }
  
  .isDefault {
    animation-name: change-toggle-right-to-left;
    animation-duration: 0.2s;
    animation-timing-function: ease-out;
    animation-fill-mode: forwards;
  }
  @keyframes change-toggle-right-to-left{
    0% {
      background-color:paleturquoise;
      padding-left: 28px;
    }
    100% {
      background-color: plum;
      padding-left: 2px;
    }    
  }

  #toggle-state-icon {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background-color: white;

    pointer-events: none;
  }


  .close-icon-wrapper{
    padding: 10px;
    border-top-left-radius: 50%;
    border-bottom-left-radius: 50%;
    background-color: white;
    box-shadow: 1px 1px 1px 1px grey;
  }

  #close-modal-icon {
    /* position: absolute;
    top: 200px;
    left: -20px; */
    cursor: pointer;
  }
  
  #area-wrapper {
    position: relative;
    width: 90%;
    height: 100%;
    background-color: white;
    box-shadow: 1px 1px 2px 1px rgba(130, 130, 130, 0.6);

    /* モーダル内の要素の配置 */
    display: flex;
    align-items: center;
    flex-flow: column;
  }

  #drop-zone {
    position: absolute;
    top: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background-color: blue;
  }

  .show {
    z-index: 3;
    opacity: 0.3;
  }
  .hidden {
    z-index: -3;
  }

  #contents-wrapper {
    z-index: 2;
    width: 100%;
    height: auto;
    padding: 10px;
  
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

  }


  #upload-input-wrapper {
    width: 100%;
    height: 50px;
    margin-bottom: 5px;
    padding: 0 10px;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
  }

  #loading-display-wrapper {
    display: flex;
    align-items: center;
    margin-left: 10px;
  }

  .loading-message {
    font-size: 0.5rem;
    margin-bottom: 0;
  }

  #uploading-dot {
    margin-left: 3px;
    width: 2px;
    height: 2px;
    border-radius: 50%;
    /* background-color: black; */
  }

  .copy-to-right {
    animation-name: dot-copy-to-right;
    animation-duration: 3s;
    animation-timing-function: steps(3, start);
    animation-iteration-count: infinite;
  }
  @keyframes dot-copy-to-right {
    /* ドットを右にコピーして増やしていく(影でコピーを表現) */
    33%   {box-shadow: 5px 0 0 0 black}
    66%   {box-shadow: 10px 0 0 0 black}
    100%  {box-shadow: 15px 0 0 0 black,16px 0 0 0 black;}
  }

  #loading-icon {
    width: 20px; 
    height: 20px;
    margin-right: 10px;
    background: linear-gradient(#05FBFF, #FF33aa);
    border-radius: 50%;
  }

  .rotate {
    animation: rotate-anime 2s linear infinite;
  }
  @keyframes rotate-anime {
    0%  {transform: rotate(0);}
    100%  {transform: rotate(360deg);}
  }

  #upload-label {
    padding: 5px 30px;
    background-color: rgba(100, 200, 250, 0.4);
    border-radius: 20px;
    margin-bottom: 0;
  }
  #upload-label:hover {
    cursor: pointer;
    background-color: rgba(100, 200, 250, 0.8);
  }

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


  .user-own-img {
    width: 100%;
    height: 140px;
  }



  /* アニメーション */

  /* .right-slide-enter-to, .right-slide-leave {
    transform: translate(0px, 0px);
  } */

  .right-slide-enter-active, .right-slide-leave-active {
    transform: translate(0px, 0px);
    transition: all 500ms
    /* cubic-bezier(0, 0, 0.2, 1) 0ms; */
  }

  .right-slide-enter, .right-slide-leave-to {
    transform: translateX(100vw) 
  }

</style>