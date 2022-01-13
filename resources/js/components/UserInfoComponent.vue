<template>
  <section id="user-info-wrapper" class="w70 p15 flex">

    <div class="img-wrapper left w60px h60px border-r-50per">
      <img :src="user_info['profile_img_url']" alt="?"
      class="profile_img w100 h100">
    </div>

    <div class="right pt3 pl15 flex column">
      <div class="right-upper-box w100 flex j-s-between a-center">
        <span>{{user_info['name']}}</span>
        <follow-btn :user_id="user_info['id']"></follow-btn>
      </div>
      <span>{{user_info['profile']}}</span>
    </div>

  </section>
</template>

<script>
import {mapGetters, mapMutations} from 'vuex';
import FollowBtn from './common/FollowBtnComponent.vue';


export default {
  components : {
    FollowBtn,
  },
  props : [
    "user_info"
  ],
  data : () => {
    return {
      follower_info : "",
    }
  },
  computed : {
    // ...mapGetters('followers', ['getFollowers']),
    // ...mapGetters('followers', ['getFollower']),
  },
  methods : {
    // ...mapMutations('followers', ['setTargetObjectIndex']),
    // getOneFollowerData(){
    //   this.setTargetObjectIndex(this.index);
    //   return this.getFollower;
    // },
    // init(){
    //   this.follower_info = this.getOneFollowerData();
    // }
  },
  created() { 
    // this.init(); 
  },
  mounted() {},
  watch : {},

}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

#user-info-wrapper  {
  max-width: 600px;
}

.img-wrapper {
  outline: 1px solid;
}

.right {
  width: calc(100% - 70px);
}


</style>