<template>
  <div class="mypage-content-wrapper">

    <!-- 作成済みMediaのプレビュー -->
    <section v-show="isShowCreatedMediaPreview" class="mypage-section created-media-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <h3 class="section-title">作成済みMedia</h3>
        <!-- {{-- Media作成 --}} -->

        <span class="view-more" @click="addCreatedMediaPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- {{-- 作成済みmedia一覧 --}} -->
      <media-preview-component
        :media-preview-infos="createdMediaPreviewInfos"
        :is-show-cover="isShowCoverOnCreateMedia"
        :is-select-mode="isSelectMode"
        @changeIsCheckedMedia="changeIsCheckedCreatedMedia"
        ref="createdMediaPreview">
      </media-preview-component>
    </section>

    <!-- いいねしたMediaのプレビュー -->
    <section v-show="isShowLikedMediaPreview" class="mypage-section liked-media-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <h3 class="section-title">いいねしたMedia</h3>
        <span class="view-more" @click="addLikedMediaPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- いいねしたmedia一覧 -->
      <media-preview-component
        :media-preview-infos="likedMediaPreviewInfos"
        :is-show-cover="isShowCoverOnLikeMedia"
        :is-select-mode="isSelectMode"
        @changeIsCheckedMedia="changeIsCheckedLikedMedia"
        ref="likedMediaPreview">
      </media-preview-component>
    </section>
    
    <mypage-menu-bar-component>
    </mypage-menu-bar-component>


  </div>


</template>

<script>
import MediaPreview from './MediaPreviewComponent.vue';
import MypageMenuBar from './mypage/MypageMenuBarComponent.vue';

export default {
  components : {
    MediaPreview,
    MypageMenuBar,
  },
  props : [
    'createdMediaPreviewInfosFromParent',
    'likedMediaPreviewInfosFromParent',
  ],
  data : () => {
    return {
      'createdMediaPreviewInfos' : "",
      'likedMediaPreviewInfos' : "",
      'isShowCoverOnCreateMedia' : true,
      'isShowCoverOnLikeMedia' : false,
      'isShowCreatedMediaPreview' : true,
      'isShowLikedMediaPreview' : true,
      'isUpdateCreatedMediaPreviewInfo' : false,
      'isUpdateLikedMediaPreviewInfo' : false,
      'totalSelectedCount' : 0,
    }
  },
  methods : {
    addCreatedMediaPreviewInfos(){
      let url = '/addCreatedMediaPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.createdMediaPreviewInfos = response.data.createdMediaPreviewInfos;
        tmpThis.isUpdateCreatedMediaPreviewInfo = true;
      })
      .catch(error => {
        alert('media情報の取得に失敗しました')
      })
    },
    addLikedMediaPreviewInfos(){
      let url = '/addLikedMediaPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.likedMediaPreviewInfos = response.data.likedMediaPreviewInfos;
        tmpThis.isUpdateLikedMediaPreviewInfo = true;
      })
      .catch(error => {
        alert('media情報の取得に失敗しました')
      })
    },

  },
  created() {},
  mounted() {
    this.createdMediaPreviewInfos = this.createdMediaPreviewInfosFromParent;
    this.likedMediaPreviewInfos = this.likedMediaPreviewInfosFromParent;
  },
  watch : {},
  computed : {
  }

}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";


.mypage-content-wrapper {
  margin-left: 70px;
}

.select-mode-switch {
  margin-bottom: 30px;
}

.select-mode-item-wrapper {
  display: flex;
  flex-wrap: wrap;
}

.select-mode-description {
  font-size: 10px;
  margin-top: 7px;
}

.select-mode-item {
  margin: 0 10px 10px 0;
}

.mypage-section {
  padding: 5px;
  width: 100%;
  max-width: 1200px;
}

.section-top-wrapper {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  width: 90%
}

.section-title {
  display: inline-block;
  margin: 0 20px 0 0;
  font-family: "Yu Gothic", "游ゴシック", YuGothic, "游ゴシック体";
}


.media-create-wrapper {
  display: inline-block;
  margin-bottom: 25px;
}
.media-create-wrapper:hover {
  transform: translate(0.7px, 0.5px);
}

.linkTo-createMedia {
  text-decoration: none;
  font-size: 18px;
  font-weight: bold;
  font-family: "Meiryo";
  color: white;
  background-color: rgb(25,95,150);
  padding: 3px 20px;
  border-radius: 4px;
  box-shadow: 2px 2px 4px grey;
}

.linkTo-createMedia:hover {
  background-color: rgb(245,50,110);
  box-shadow: 1.5px 1.5px 3px darkgrey;

}

.preview-img {
  width: 100px;
  height: 100px;
  border: 2px #aaaaaa solid;
  border-radius: 50%;
  margin-right: 20px;
}

.view-more {
  color: blue;
}

.view-more:hover {
  cursor: pointer;
  color: aqua;
}


/* スマホ以外 */
@media screen and (min-width: 481px) {
  .for-mobile {
    display: none;
  }
  .select-mode-item-wrapper {
    margin: 12px 0;
  }
 
}


/* スマホ */
@media screen and (max-width: 480px) {
  .for-pc-tablet {
    display: none;
  }
  .select-mode-item-wrapper {
    position: fixed;
    bottom: 0;
    width: 100%;
    margin-left: -20px;
    padding: 10px 0;
    background-color: black;
    color: white;
    z-index: 15;

    justify-content: center;
  }
  .select-mode-item {
    margin: 2px 15px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }
  .mypage-content-wrapper {
    margin-left: 20px;
  }
  .mypage-section {
      margin-left: 0;
      padding: 5px;
  }
  .is-black {
    background-color: black;
  }
  .linkTo-createMedia {
    font-size: 14px;
    padding: 6px 18px;
  }
  
}

</style>