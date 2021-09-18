<template>

  <div class="action-button-wrapper">
    <a href="#" onclick="javascript:window.history.back(-1); return false;">
      <button class="action-button cancel-button">
        戻る
      </button>
    </a>
  </div>

</template>

<script>
  export default {
    props : [],
    data : () => {
      return {}
    },

    methods : {},

  }

</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

  @media screen and (max-width: 480px) {
    .action-button-wrapper {
      display: none;
    }
  }

</style>