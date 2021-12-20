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
        <div id="contents-wrapper" class="contents-img"
        @dragenter = "dragEnter">
          <div id="toggle-wrapper">
            <div id="category-type">
              <span @click="changeFileCategory"
              class="category category-default"
               :class="{active_category: isDefault}">
               default
               </span>
              <span @click="changeFileCategory"
              class="category category-upload"
               :class="{active_category: !isDefault}">
               upload
               </span>
            </div>
          </div>
          <div id="upload-input-wrapper">
            <!-- <div id="loading-icon"></div> -->
            <label id="upload-label" for="upload-input" tabindex=2 @keydown.enter="startInput" v-show="!(isDefault)">
              <i class="fas fa-upload" style="margin-right: 5px"></i>
              <span class="upload-label-text"></span>
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
              <div class="icon-cover" v-on:click="setMediaImg">
                <i id="delete-img-icon" class="fas fa-times fa-2x" v-on:click.stop="deleteImg"></i>
                <i id="add-img-icon" class="fas fa-plus fa-2x"></i>
              </div>
            </li>
            <!-- default -->
            <li :id="index" v-show="isDefault" class="img-list" v-for="(defaultImg, index) in defaultImgs" :key="defaultImg.url">
              <img class="default-img" :src="defaultImg['url']" :alt="defaultImg['url']" />
              <div class="icon-cover" v-on:click="setMediaImg">
                <!-- <i id="delete-img-icon" class="fas fa-times fa-2x" v-on:click="deleteImg"></i> -->
                <i id="add-img-icon" class="fas fa-plus fa-2x"></i>
              </div>
            </li>

          </ul>

        </div>
      </div>

      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>

<script>
  import { mapState, mapMutations, mapGetters } from 'vuex';
  import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'
  import closeModalIcon from '../change_display_parts/CloseModalIconComponent.vue'

  export default {
    components : {
      closeModalBar,
      closeModalIcon,
    },
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
        defaultImgs : [],
        mediaImgBaseData : {
          'id' : 0,
          'type' : 99,
          'groupNo' : 0,
          'img_id' : 0,
          'img_type': "",
          'url' : "",
          'top' : 100,
          'left' : 100,
          'width' : 300,
          'height' : 220,
          'degree' : 0,
          'opacity' : 1,
          'layer' : 1,
        },

      }
    },
    computed : {
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImgs']),
    },
    methods : {
      ...mapMutations('mediaImgs', ['setTargetObjectIndex']),
      ...mapMutations('mediaImgs', ['addMediaImgsObjectItem']),
      ...mapMutations('mediaImgs', ['deleteMediaImgsObjectItem']),
      ...mapMutations('mediaImgs', ['updateMediaImgsObjectItem']),

      changeFileCategory(){
        this.isDefault = !(this.isDefault);
      },
      getUserOwnImgs(){
        const url = '/ajax/getUserOwnImgs';
        axios.get(url)
          .then(response => {
            response.data.file_datas.forEach(file_data => {            
              this.userOwnImgs.unshift(file_data);
            });
          })
          .catch(error => {
            alert('画像取得失敗');
          })
      },
      getDefaultImgs(){
        const url = '/ajax/getPublicImgs';
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
      setMediaImg: function(event){
        const imgUrl = event.target.previousElementSibling.getAttribute('src');
        const imgTypeLabel = event.target.previousElementSibling.getAttribute('class');
        let imgType;
        if(imgTypeLabel == 'default-img'){
          imgType = 1;
        } else if(imgTypeLabel == 'user-own-img'){
          imgType = 2;
        }
        const imgId = this.findImgIdTiedUpWithUrl(imgType, imgUrl);      
        let mediaImgData = Object.assign({}, this.mediaImgBaseData);
        mediaImgData['img_id'] = imgId;
        mediaImgData['img_type'] = imgType;
        mediaImgData['url'] = imgUrl;
        this.addMediaImgsObjectItem(mediaImgData);
        this.mediaImgBaseData['top'] += 30;
        this.mediaImgBaseData['left'] += 30;
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
        let imgId = this.findImgIdTiedUpWithUrl(2, imgUrl);
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
            // Media画像に設定していた場合は削除する
            const mediaImgs = this.getMediaImgs;
            const tmpThis = this;
            mediaImgs.forEach(function(mediaImg,index){
              if(mediaImg['url']==imgUrl){
                tmpThis.deleteMediaImgsObjectItem(index);
              }
            })
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

@import "/resources/css/mediaEditModals.css";

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
    /* height: 140px; */
    margin-bottom: 2px;
    list-style: none;
    transition: transform 0.3s;
    background-color: grey;
  }
  .img-list:before {
    content: "";
    display: block;
    padding-top: 100%;
  }

  .img-list:hover {
    cursor: pointer;
    transform: scale(0.98,0.98);
  }

  .img-list:hover .icon-cover {
    z-index: 2;
    background-color: rgba(130, 130, 130, 0.6);
  }

  .user-own-img,.default-img{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    margin: auto;
    object-fit: cover;
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

  .upload-label-text::after {
    content: "アップロード"
  }


  @media screen and (max-width:480px) {
    .img-list {
      border-radius: 5px;
    }
  }


  @media screen and (max-width:480px) {

    .contents-img {
      padding: 15px 0 15px 0;
    }

    .img-list {
      width: 33%;
    }
    
    .upload-label-text::after {
      content: "追加"
    }

  }


</style>