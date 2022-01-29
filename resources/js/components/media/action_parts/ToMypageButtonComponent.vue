<template>

  <div v-if="getIsLogin" class="action-trigger-wrapper mypage-icon-wrapper">
    <a class="action-trigger" href="/mypage">
      <i class="far fa-user fa-lg mypage-icon"></i>
    </a>
    <span class="action-trigger-subtitle">マイページへ</span>
  </div>

</template>

<script>
  import {mapGetters} from 'vuex';

  export default {
    props : [],
    data : () => {
      return {}
    },
    computed : {
      ...mapGetters('loginState',['getIsLogin']),
    },
    methods : {},
  }

</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

  .mypage-icon {
    color: white;
  }
  .mypage-icon:hover {
    color: yellow;
  }


  @media screen and (max-width: 480px) {
    .mypage-icon-wrapper {
      display: none;
    }
  }

</style>