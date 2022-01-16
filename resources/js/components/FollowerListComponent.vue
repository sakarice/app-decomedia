<template>
  <section id="follower-list-wrapper" class="flex column a-center">
    <user-info v-for="(follower, index) in getFollowers" :key="index"
    :user_info="follower" ref="followers">
    </user-info>
  </section>
</template>

<script>
import {mapGetters, mapMutations} from 'vuex';
import userInfo from './UserInfoComponent.vue';

export default {
  components : {
    userInfo,
  },
  props : [
  ],
  data : () => {
    return {
    }
  },
  computed : {
    ...mapGetters('followers', ['getFollowers']),
  },
  methods : {
    ...mapMutations('followers', ['addFollowersObjectItem']),
    
    init(){
      const url = '/followers';
      axios.get(url)
      .then(res=>{
        const followers = res.data.followers;
        followers.forEach(follower => {
          this.addFollowersObjectItem(follower);
        });
      })
      .catch(error=>{
        console.log('フォロワー情報の取得に失敗しました。')
      })
    }
  },
  created() {
    this.init();
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


</style>