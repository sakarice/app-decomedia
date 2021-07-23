<template>

    <div class="action-button-wrapper">
      <a href="#" onclick="javascript:window.history.back(-1); return false;">
        <button class="cancel-button">
          キャンセル
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

    methods : {
      // sendToLink() {
      //   window.location.href = this.linkUrl;
      // },

    },

  }


</script>


<style scoped>

  .action-button-wrapper {
    width: 50%;
    height: 50%;
    position: absolute;
    top: 0;
    right: 0;
  }

  .cancel-button {
    position: absolute;
    top : 20px;
    right: 60px;
    z-index: 1;
    font-family: Inter,Noto Sans JP;
    border-radius: 4px;
    border: solid 1px grey;
    box-shadow: 0.5px 0.5px 1px lightslategrey;
  }

  .cancel-button:hover {
    background-color: aqua;
  }

</style>