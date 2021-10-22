<template>

  <div class="back-icon-wrapper">
    <span class="back-icon-subtitle">戻る</span>
    <a href="#" onclick="javascript:window.history.back(-1); return false;">
      <i class="fas fa-long-arrow-alt-left fa-3x back-icon"></i>
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


  .back-icon-wrapper {
    display: flex;
    align-items: center;
    margin: 0 20px;
    color: white;
  }

  .back-icon {
    color: white;
  }
  .back-icon:hover {
    color: deepskyblue;
  }

  .back-icon-subtitle {
    font-size:12px;
    color: grey;
    margin-right: 7px; 
  }

  @media screen and (max-width: 480px) {
    .back-icon-wrapper {
      display: none;
    }
  }

</style>