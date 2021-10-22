<template>
  <div class="follow-and-follower-wrapper">

    <!-- 作成済みRoomのプレビュー -->
    <section v-show="isShowCreatedRoomPreview" class="mypage-section created-room-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <h3 class="section-title">作成済みRoom</h3>
        <!-- {{-- Room作成 --}} -->

        <span class="view-more" @click="addCreatedRoomPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- {{-- 作成済みroom一覧 --}} -->
      <room-preview-component
        :room-preview-infos="createdRoomPreviewInfos"
        :is-show-cover="isShowCoverOnCreateRoom"
        :is-select-mode="isSelectMode"
        @changeIsCheckedRoom="changeIsCheckedCreatedRoom"
        ref="createdRoomPreview">
      </room-preview-component>
    </section>

    <!-- いいねしたRoomのプレビュー -->
    <section v-show="isShowLikedRoomPreview" class="mypage-section liked-room-list">
      <!-- 説明やもっと見るの表示 -->
      <div class="section-top-wrapper">
        <h3 class="section-title">いいねしたRoom</h3>
        <span class="view-more" @click="addLikedRoomPreviewInfos">
          もっと見る
        </span>
      </div>
      <!-- いいねしたroom一覧 -->
      <room-preview-component
        :room-preview-infos="likedRoomPreviewInfos"
        :is-show-cover="isShowCoverOnLikeRoom"
        :is-select-mode="isSelectMode"
        @changeIsCheckedRoom="changeIsCheckedLikedRoom"
        ref="likedRoomPreview">
      </room-preview-component>
    </section>
    
    <mypage-menu-bar-component>
    </mypage-menu-bar-component>


  </div>


</template>

<script>
import RoomPreview from './RoomPreviewComponent.vue';
import MypageMenuBar from './MypageMenuBarComponent.vue';

export default {
  components : {
    RoomPreview,
    MypageMenuBar,
  },
  props : [
    'createdRoomPreviewInfosFromParent',
    'likedRoomPreviewInfosFromParent',
  ],
  data : () => {
    return {
      'createdRoomPreviewInfos' : "",
      'likedRoomPreviewInfos' : "",
      'isShowCoverOnCreateRoom' : true,
      'isShowCoverOnLikeRoom' : false,
      'isShowCreatedRoomPreview' : true,
      'isShowLikedRoomPreview' : true,
      'isUpdateCreatedRoomPreviewInfo' : false,
      'isUpdateLikedRoomPreviewInfo' : false,
      'totalSelectedCount' : 0,
    }
  },
  methods : {
    addCreatedRoomPreviewInfos(){
      let url = '/addCreatedRoomPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.createdRoomPreviewInfos = response.data.createdRoomPreviewInfos;
        tmpThis.isUpdateCreatedRoomPreviewInfo = true;
      })
      .catch(error => {
        alert('room情報の取得に失敗しました')
      })
    },
    addLikedRoomPreviewInfos(){
      let url = '/addLikedRoomPreviewInfos';
      let tmpThis = this;
      axios.get(url)
      .then(response => {
        tmpThis.likedRoomPreviewInfos = response.data.likedRoomPreviewInfos;
        tmpThis.isUpdateLikedRoomPreviewInfo = true;
      })
      .catch(error => {
        alert('room情報の取得に失敗しました')
      })
    },

  },
  created() {},
  mounted() {
    this.createdRoomPreviewInfos = this.createdRoomPreviewInfosFromParent;
    this.likedRoomPreviewInfos = this.likedRoomPreviewInfosFromParent;
  },
  watch : {},
  computed : {}

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


.room-create-wrapper {
  display: inline-block;
  margin-bottom: 25px;
}
.room-create-wrapper:hover {
  transform: translate(0.7px, 0.5px);
}

.linkTo-createRoom {
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

.linkTo-createRoom:hover {
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
  .linkTo-createRoom {
    font-size: 14px;
    padding: 6px 18px;
  }
  
}

</style>