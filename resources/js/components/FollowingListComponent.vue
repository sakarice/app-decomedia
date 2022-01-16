<template>
  <section id="following-list-wrapper" class="flex column a-center">
    <user-info v-for="(following, index) in getFollowings" :key="index"
    :user_info="following" ref="followings">
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
    ...mapGetters('followings', ['getFollowings']),
  },
  methods : {
    ...mapMutations('followings', ['addFollowingsObjectItem']),
    
    init(){
      const url = '/followings';
      axios.get(url)
      .then(res=>{
        const followings = res.data.followings;
        followings.forEach(following => {
          this.addFollowingsObjectItem(following);
        });
      })
      .catch(error=>{
        console.log('フォロー中ユーザ情報の取得に失敗しました。')
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