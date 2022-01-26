<template>
  <router-link :to="'/media/'+getMediaId" class="change-mode action-trigger-wrapper link-to-show-media">
    <div class="action-trigger mode-icon-wrapper">
      <i class="fas fa-tv fa-lg show-mode-icon"></i>
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

.show-mode-icon {
  display: inline-block;
}

.action-trigger-subtitle::after {
  content: '閲覧モードへ';
}

@media screen and (max-width:480px) {
  .change-mode {
    bottom: 45px;
    left: 5px;
  }
}

</style>