<template>

  <div class="action-trigger-wrapper back-icon-wrapper">
    <a class="action-trigger" href="#" onclick="javascript:window.history.back(-1); return false;">
      <i class="fas fa-long-arrow-alt-left fa-2x back-icon"></i>
    </a>
    <span class="action-trigger-subtitle">戻る</span>
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
@import "/resources/css/button.css";

  .back-icon {
    color: white;
  }
  .back-icon:hover {
    color: deepskyblue;
  }


  @media screen and (max-width: 480px) {
    .back-icon-wrapper {
      display: none;
    }
  }

</style>