<template>
  <ul class="media-list-wrapper">
    <li v-for="(mediaListPreviewInfo,index) in mediaListPreviewInfos" :key="mediaListPreviewInfo.id">
      <div class="preview-media-list" :id="mediaListPreviewInfo['id']">
        <a :href="mediaListShowLink(mediaListPreviewInfo['id'])">
          <img class="media-list-thumbnail" :src="mediaListPreviewInfo['preview_img_url']" alt="">
        </a>

        <div class="cover-menu" v-show="isShowCover">
          <a :href="mediaListShowLink(mediaListPreviewInfo['id'])" class="cover-menu-link">
            <span class="link-title">閲覧</span>
          </a>
          <a :href="mediaListEditLink(mediaListPreviewInfo['id'])" class="cover-menu-link">
            <span class="link-title">編集</span>
          </a>
        </div>
        <i class="fas fa-trash del-icon" @click="deletemediaList(mediaListPreviewInfo['id'])" v-show="isShowCover"></i>

        <!-- mediaリスト選択用チェックボックス -->
        <div class="check-box-cover" v-show="isSelectMode">
          <input type="checkbox" class="media-list-select-check" name="" @change="changeIsCheckedmediaList($event, index)">
        </div>

        <!-- 選択順 -->
        <div class="selected-order-num-wrapper"
        v-show="isSelectMode && mediaListPreviewInfo['selectedOrderNum'] > 0">
          <span class="selected-order-num">{{mediaListPreviewInfo['selectedOrderNum']}}</span>
        </div>

        <p class="media-list-title">{{mediaListPreviewInfo['name']}}</p>
      </div>
    </li>
  </ul>
</template>

<script>
export default {
  props : [
    'firstPreviewNum',
    'isShowCover',
    'isSelectMode',
  ],
  data : () => {
    return {
      'mediaListPreviewInfos' : "",
      'isShowSelectedOrderNum' : false,
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
    mediaListShowLink : function(id) {
      return "/home/mediaList/" + id;
    },
    mediaListEditLink : function(id) {
      return "/home/mediaList/" + id + "/edit";
    },
    deletemediaList(mediaList_id){
      let mediaList_data = {
        'mediaList_id' : mediaList_id,
      }
      const url = '/mediaLists/delete';
      axios.post(url, mediaList_data)
      .then(response => {
        alert(response.data.message);
        location.reload();
      })
      .catch(error => {
        alert('mediaリスト削除に失敗しました。');
      })
    },
    changeIsCheckedmediaList(event, index){
      let isChecked = event.target.checked;
      this.$emit('changeIsCheckedmediaList', isChecked, index);
    },
    unCheckAllmediaList(){
      let checkBoxList = document.querySelectorAll(".media-list-select-check");
      for(let i=0; i < checkBoxList.length; i++){
        checkBoxList[i].checked = false;
      }
    },
    judgeIsChecked(mediaListPreviewInfo){
      if(mediaListPreviewInfo['selectedOrderNum'] > 0){
        return true;
      }
    },
    // 作成済みMediaリストのプレビュー情報を取得
    addCreatedMediaListPreviewInfos($num){
      let url = '/ajax/addCreatedMediaListPreviewInfos/'+$num;
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.mediaListPreviewInfos = response.data.mediaListPreviewInfos;
      })
      .catch(error => {
        console.log('mediaリスト情報の取得に失敗しました')
      })
    },

  },
  mounted : function(){
    this.addCreatedMediaListPreviewInfos(this.firstPreviewNum);
  },
  watch : {
  },
  computed : {

  }

}
</script>


<style scoped>

.media-list-wrapper {
  display: flex;
  justify-content:flex-start;
  flex-wrap: wrap;
  width: 100%;
  max-width: 1200px;
}

/* ★★flex-boxで横並び感覚を等間隔にした場合の設定 */
/* .media-list-wrapper::after {
  display: block;
  content:"";
  width: 180px;
} */

li {
  list-style: none;
}

.preview-media-list {
  position: relative;
  text-align: center;
  /* font-size: 50px; */
  margin-right: 40px;
  margin-bottom: 80px;
  width: 180px;
  height: 180px;
  opacity: 0.8;
}

.preview-media-list:hover {
  opacity: 1;
  transform: scale(0.98,0.98);

}

.preview-media-list:hover .cover-menu {
  opacity: 0.7;
  z-index: 1;
}

.preview-media-list:hover .del-icon {
  opacity: 0.5;
  z-index: 2;
}


.media-list-thumbnail {
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

.media-list-select-check {
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

.media-list-title {
  text-align: center;
  font-size: 25px;
  margin: 0 0;
  font-family: 'Yu Mincho';
}


</style>