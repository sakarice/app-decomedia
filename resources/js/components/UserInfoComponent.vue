<template>
  <section id="user-info-wrapper">
    <span>{{index}},</span><br>
    <span>{{follower_info['name']}},</span><br>
    <span>{{follower_info['profile']}},</span><br>
    <span>{{follower_info['profile_img_url']}},</span><br>
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


</style>