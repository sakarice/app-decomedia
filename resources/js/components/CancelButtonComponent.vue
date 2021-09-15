<template>

    <div class="action-button-wrapper">
      <a href="#" onclick="javascript:window.history.back(-1); return false;">
        <button class="cancel-button">
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

  .cancel-button {
    /* position: absolute; */
    top : 20px;
    left: 70px;
    z-index: 1;
    font-family: Inter,Noto Sans JP;
    border-radius: 4px;
    border: solid 1px grey;
    box-shadow: 0.5px 0.5px 1px lightslategrey;
  }

  .cancel-button:hover {
    background-color: aqua;
  }

  @media screen and (max-width: 480px) {
    .action-button-wrapper {
      display: none;
    }
  }

</style>