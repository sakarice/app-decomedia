<template>

  <div v-if="getIsLogin" class="action-trigger-wrapper mypage-icon-wrapper">
    <a class="action-trigger to-mypage-button-wrapper" href="/mypage">
      <span class="to-mypage-button">マイページへ</span>
    </a>
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

  .to-mypage-button-wrapper {
    color: black;
    outline: 1px solid lightgrey;
    padding: 1px 10px;
    border-radius: 3px;
    text-decoration: none;
  }

  .to-mypage-button-wrapper:hover {
    outline: 1px solid black;
  }

  .to-mypage-button {
    font-size: 12px;
  }
  .to-mypage-button::before {
    content: "<";
    margin-right: 5px;
  }


  @media screen and (max-width: 480px) {
    .mypage-icon-wrapper {
      display: none;
    }

  .to-mypage-button-wrapper {
    transform: scale(1);
  }

  }

</style>