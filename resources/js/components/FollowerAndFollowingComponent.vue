<template>
  <section id="follower-and-following-wrapper">
    <!-- フォロワーかフォロー中ユーザどちらを表示するか選択するためのカテゴリ -->
    <div class="category-wrapper flex a-center j-center">
      <div id="category-follower" class="category-area w50 pt10 pb10 text-center"
      @click="isShowFollower=!isShowFollower">
        <span class="category" :class="{isActive:isShowFollower}">フォロワー</span>
      </div>
      <div id="category-following" class="category-area w50 pt10 pb10 text-center"
      @click="isShowFollower=!isShowFollower">
        <span class="category" :class="{isActive:isShowFollowing}">フォロー中</span>
      </div>
    </div>

    <!-- フォロワーとフォロー中ユーザ一覧表示領域 -->
    <!-- フォロワー -->
    <div class="following-and-follower-area">
      <follower-list v-show="isShowFollower"></follower-list>
      <following-list v-show="isShowFollowing"></following-list>
    </div>

  </section>


</template>

<script>
import followerList from './FollowerListComponent.vue'
import followingList from './FollowingListComponent.vue'

export default {

  components : {
    followerList,
    followingList,
  },
  props : [
  ],
  data : () => {
    return {
      isShowFollower : true,
    }
  },
  computed : {
    isShowFollowing:function(){ return !this.isShowFollower}
  },
  methods : {},
  created() {},
  mounted() {},
  watch : {},

}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

#follower-and-following-wrapper {
  margin-top: 60px;
}
.category-wrapper {
  border-bottom: 1px solid lightgrey;
}
.category-area {
  /* padding : 10px 0; */
}
.category-area:hover {
  background-color: rgb(248,248,248);
}

.category {
}

.isActive {
  font-weight: bold;
  border-bottom: 2px solid greenyellow;
}

</style>