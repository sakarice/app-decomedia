<template>
  <router-link :to="'/media/'+getMediaId+'/edit'" class="change-mode action-trigger-wrapper link-to-edit-mode">
    <div class="action-trigger mode-icon-wrapper">
      <i class="fas fa-pen fa-lg edit-mode-icon"></i>
    </div>
    <span class="action-trigger-subtitle"></span>
  </router-link>
</template>

<script>
import { mapGetters } from 'vuex';

  export default {
    computed : {
      ...mapGetters('media', ['getMediaId']),
    },
    methods : {}
  }

</script>

<style scoped>
/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

.edit-mode-icon {
  display: inline-block;
}

.action-trigger-subtitle::after {
  content: '編集モードへ';
}

@media screen and (max-width:480px) {
  .change-mode {
    bottom: 45px;
    left: 5px;
  }
}

</style>