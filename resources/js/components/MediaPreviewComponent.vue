<template>
  <ul id="media-wrapper">
    <li v-for="(mediaPreviewInfo,index) in mediaPreviewInfos" :key="mediaPreviewInfo.id">
      <div class="upper-block">
        <div class="preview-media" :id="mediaPreviewInfo['id']">
          <a class="media-link" :href="mediaShowLink(mediaPreviewInfo['id'])">
            <div class="media-thumbnail-wrapper">
              <img class="media-thumbnail" :src="mediaPreviewInfo['preview_img_url']" alt="">
            </div>
          </a>

          <div class="cover-menu" v-show="isShowCover">
            <a :href="mediaShowLink(mediaPreviewInfo['id'])" class="cover-menu-link show-media">
              <i class="fas fa-tv fa-lg show-mode-icon"></i>
              <span class="link-title">閲覧</span>
            </a>
            <a :href="mediaEditLink(mediaPreviewInfo['id'])" class="cover-menu-link edit-media">
              <i class="fas fa-pen fa-lg edit-mode-icon"></i>
              <span class="link-title">編集</span>
            </a>
          </div>
          <i class="fas fa-trash del-icon" @click="deleteMedia(mediaPreviewInfo['id'])" v-show="isShowCover"></i>

          <!-- Media選択用チェックボックス -->
          <div class="check-box-cover" v-show="isSelectMode">
            <input type="checkbox" class="media-select-check" name="" @change="changeIsCheckedMedia($event, index)">
          </div>

          <!-- 選択順 -->
          <div class="selected-order-num-wrapper"
          v-show="isSelectMode && mediaPreviewInfo['selectedOrderNum'] > 0">
            <span class="selected-order-num">{{mediaPreviewInfo['selectedOrderNum']}}</span>
          </div>

        </div>
        <p class="media-title">{{mediaPreviewInfo['name']}}</p>

      </div>

      <!-- 下段のブロック -->
      <div class="bottom-block">
        <div class="has-contents-wrapper">
          <div v-if="mediaPreviewInfo['has_audio']" class="has-contents has-audio">
            <i class="fas fa-headphones-alt has-contents-icon audio-icon"></i>
          </div>
          <div v-if="mediaPreviewInfo['has_movie']" class="has-contents has-movie">
            <i class="fab fa-youtube has-contents-icon youtube-icon"></i>
          </div>
        </div>
      </div>

    </li>
  </ul>  

</template>

<script>
export default {
  props : [
    'mediaPreviewInfos',
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
    mediaShowLink : function(id) {
      return "/media/" + id;
    },
    mediaEditLink : function(id) {
      return "/media/" + id + "/edit";
    },
    deleteMedia(media_id){
      let media_data = {
        'media_id' : media_id,
      }
      const url = '/media/delete';
      this.$emit('set-is-delete', true);
      axios.post(url, media_data)
      .then(response => {
        alert(response.data.message);
        this.$emit('set-is-delete', false);
        location.reload();
      })
      .catch(error => {
        alert('media削除に失敗しました。');
        this.$emit('set-is-delete', false);
      })
    },
    changeIsCheckedMedia(event, index){
      let isChecked = event.target.checked;
      this.$emit('changeIsCheckedMedia', isChecked, index);
    },
    unCheckAllMedia(){
      let checkBoxList = document.querySelectorAll(".media-select-check");
      for(let i=0; i < checkBoxList.length; i++){
        checkBoxList[i].checked = false;
      }
    },
    judgeIsChecked(mediaPreviewInfo){
      if(mediaPreviewInfo['selectedOrderNum'] > 0){
        return true;
      }
    },

  },
  mounted : function(){},
  watch : {
  },
  computed : {}

}
</script>


<style scoped>

#media-wrapper {
  width: 90%;
  padding-left: 0;
  padding: 5px 1px;
  /* background-color: white; */
  max-width: 1200px;
  display: flex;
  justify-content:flex-start;
  flex-wrap: wrap;
}

/* ★★flex-boxで横並び感覚を等間隔にした場合の設定 */
/* #media-wrapper::after {
  display: block;
  content:"";
  width: 180px;
} */

#media-wrapper li {
  list-style: none;
  background-color: white;
  width: 18%;
  margin: 2px;
  padding: 5px 2px 0 2px;
  box-shadow: 1px 1px 2px lightgrey;
  border-radius: 3px;

  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
}

.upper-block, .bottom-block {
  width: 100%;
}

.preview-media {
  position: relative;
  text-align: center;
  margin: 0 3px 5px 3px;
  opacity: 0.8;
  transition: 0.2s;
  outline: 1px solid lightgrey;
  border-radius: 5px;
}

.preview-media:hover {
  opacity: 1;
  transform: scale(0.98,0.98);

}

.preview-media:hover .cover-menu {
  opacity: 0.7;
  z-index: 1;
}

.preview-media:hover .del-icon {
  opacity: 0.5;
  z-index: 2;
}

.media-thumbnail-wrapper {
  position: relative;
  width: 100%;
  height: 0px;
  padding: 0 0 100%;
}

.media-thumbnail {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
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
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  /* background-color: rgba(100, 100, 100, 0.5); */
  color : black;
  background-color: seashell;
  opacity: 0.5;
  text-decoration: none;
}

.link-title {
  font-size: 15px;
  margin-top: 4px;
}

.cover-menu-link:hover {
  /* background-color: rgba(100, 100, 100, 0.8); */
  opacity: 0.8;
  color: blue;
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

.media-select-check {
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

.media-title {  
  text-align: center;
  font-size: 16px;
  line-height: 1.4rem;
  margin: 0 0;
  font-family: 'Yu Mincho';
  overflow-wrap: break-word;
}

.has-contents-wrapper{
  margin-top: 10px;
  padding: 5px;
  display: flex;
  justify-content: flex-end;
  align-items: center;
}

.has-contents-icon {
  color: dimgray;
  font-size: 0.8em;
  margin-left: 7px;
}


/* タブレット、スマホ */
@media screen and (max-width: 780px) {
  #media-wrapper li {
    width: 30%;
    margin: 2px;
    padding: 5px 2px 20px 2px;
  }
  .preview-media {
    margin: 0 2px 2px 2px;
  }
  
}

/* スマホのみ */
@media screen and (max-width: 420px) {
  #media-wrapper {
    width: 100%;
    padding: 10px 2px;
  }
  #media-wrapper li{
    width: 30%;
    margin: 1px;
    padding: 5px 3px 0px 3px;
    border-radius: 3px;
  }

  .has-contents-wrapper {
    padding: 0 3px;
  }

  .media-title {
    font-size: 13px;
    color: rgba(100,100,100,1);
  }
  .link-title {
    font-size: 17px;
  }
  .show-media {
    display: none;
  }
  .edit-media {
    width: 100%;
  }

  .del-icon {
    top: -8px;
    right: -5px;
    font-size: 1.2em;
  }
  .media-select-check {
    top: 3px;
    left: 3px;
    transform: scale(2);
  }
  .selected-order-num{
    font-size: 30px;
  }


}





</style>