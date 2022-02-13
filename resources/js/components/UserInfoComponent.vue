<template>
  <section id="user-info-wrapper" class="p15 flex a-center">

    <div class="left-box img-wrapper border-r-50per">
      <img :src="user_info['profile_img_url']" alt="?"
      class="profile_img w100 h100">
    </div>

    <div class="right-box pt3 flex column">
      <div class="right-upper-box w100 flex j-s-between">
        <span>{{user_info['name']}}</span>
        <follow-btn :user_id="user_info['id']"></follow-btn>
      </div>
      <span>{{user_info['profile']}}</span>
    </div>

  </section>
</template>

<script>
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
  methods : {},

}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

#user-info-wrapper  {
  width: 90%;
  max-width: 600px;
  min-width: 270px;
}

.img-wrapper {
  outline: 1px solid;
  width: 60px;
  height: 60px;
}

.right-box {
  width: calc(100% - 70px);
  padding-left: 15px;
}


/* モバイル用 */
@media screen and (max-width:480px) {
  #user-info-wrapper  {
    width: 100%;
  }

  .img-wrapper {
    width: 40px;
    height: 40px;
  }  

  .right-box {
    font-size: 13px;
    width: calc(100% - 45px);
    padding-left: 10px;
  }
}

</style>