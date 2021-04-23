<template>
  <transition name="right-slide">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="clickEvent()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="all-wrapper">
        <div id="drop-zone" 
        :class="{show: isDragEnter, hidden: !(isDragEnter)}"
        @dragleave = "dragLeave"
        @dragover.prevent
        @drop.prevent = "dropFile">
        </div>
        <div id="contents-wrapper"
        @dragenter = "dragEnter">
          <div id="header-icons-wrapper">
            <div id="loading-icon"></div>
            <label id="upload-label" for="upload-input">
              <i class="fas fa-upload" style="margin-right: 5px"></i>
              <span>アップロード</span>
              <input id="upload-input" style="display: none" v-on:change="selectedFile" type="file" accept="image/*" name="img">
            </label>
          </div>
          <ul id="img-wrapper">
            <li class="img-list" v-for="imgFileUrl in imgFileUrls" :key="imgFileUrl.id">
              <img class="user-own-img" :src="imgFileUrl" :alt="imgFileUrl" />
              <div class="icon-cover">
                <i id="delete-img-icon" class="fas fa-times fa-2x" v-on:click="deleteImg"></i>
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
      isDragEnter : false,
      uploadFile : "",
      imgFileUrls : []
    }
  },
  methods : {
    resizeAction(){
      alert('resized');
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
    dragEnter: function() {
      console.log('dragenter');
      this.isDragEnter = true;
    },
    dragLeave: function() {
      console.log('dragleave');
      this.isDragEnter = false;
    },
    // 右上の×ボタンクリック時のイベント
    clickEvent() {
      this.$emit('from-child');
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
      // アイコンを回転させてローディング中であることを表現
      const loading_icon = document.getElementById('loading-icon');
      loading_icon.classList.add('rotate');
      axios.post(url, formData)
        .then(response => {
          this.imgFileUrls.unshift(response.data.url);
          alert(response.data.url);
          this.uploadFile = "";
          loading_icon.classList.remove('rotate');
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
      const loading_icon = document.getElementById('loading-icon');
      loading_icon.classList.add('rotate');
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
          loading_icon.classList.remove('rotate');
        })
        .catch(error => {
          alert('画像削除失敗');
        })

    }


  },
  mounted() {
    this.getUserOwnImgs()
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

  #header-icons-wrapper {
    width: 100%;
    margin: 5px 0;
    padding: 0 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
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

  .close-icon-wrapper{
    padding: 10px;
    border-top-left-radius: 50%;
    border-bottom-left-radius: 50%;
    background-color: white;
  }

  #close-modal-icon {
    /* position: absolute;
    top: 200px;
    left: -20px; */
    cursor: pointer;
  }
  
  #all-wrapper {
    position: relative;
    width: 90%;
    height: 100%;
    background-color: white;

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

  #upload-label {
    padding: 5px 30px;
    background-color: rgba(100, 200, 250, 0.4);
    border-radius: 20px;
    margin-bottom: 0;
  }
  #upload-label:hover {
    cursor: pointer;
    background-color: rgba(100, 200, 250, 0.8);
    /* opacity: 0.7; */
  }

  #img-wrapper {
    /* モーダル内の画像サムネの配置 */
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    align-items: center;
    justify-content: space-between;

    width: 92%;
    height: 85vh;
    margin-top: 20px;
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