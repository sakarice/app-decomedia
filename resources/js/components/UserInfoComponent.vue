<template>
  <section id="user-info-wrapper" class="w80 p10 flex">
    <div class="left w80px h80px">
      <img :src="follower_info['profile_img_url']" alt="?"
      class="profile_img border-r-50per w100 h100">
    </div>
    <div class="right ml10 pt10 flex column">
      <span>{{follower_info['name']}}</span>
      <span>{{follower_info['profile']}}</span>
    </div>

  </section>
</template>

<script>
import {mapGetters, mapMutations} from 'vuex';

export default {
  components : {
  },
  props : [
    "index"
  ],
  data : () => {
    return {
      follower_info : "",
    }
  },
  computed : {
    ...mapGetters('followers', ['getFollowers']),
    ...mapGetters('followers', ['getFollower']),
  },
  methods : {
    ...mapMutations('followers', ['setTargetObjectIndex']),
    getOneFollowerData(){
      this.setTargetObjectIndex(this.index);
      return this.getFollower;
    },
    init(){
      this.follower_info = this.getOneFollowerData();
    }
  },
  created() { this.init(); },
  mounted() {},
  watch : {},

}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.profile_img {
  outline: 1px solid;
}


</style>