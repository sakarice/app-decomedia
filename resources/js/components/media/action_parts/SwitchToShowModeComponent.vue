<template>
  <router-link :to="'/media/'+getMediaId" class="change-mode action-trigger-wrapper link-to-show-media">
    <div class="action-trigger mode-icon-wrapper">
      <i class="fas fa-tv fa-lg show-mode-icon"></i>
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
.change-mode {
  position: absolute;
  bottom: 15px;
  left: 15px;
  z-index: 3;
  width: 90px;
  height: 90px;
  background-color: rgba(0,0,0,0.2);
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  box-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}
.change-mode:hover {
  cursor: pointer;
  background-color: rgba(0,0,0,0.8);
}

.show-mode-icon {
  display : none;
}

.action-trigger-subtitle::after {
  content: '編集モード';
}
.change-mode:hover .edit-mode-icon {
  display: none;
}
.change-mode:hover .show-mode-icon {
  display: inline-block;
}
.change-mode:hover .action-trigger-subtitle::after {
  content: '閲覧モードへ';
}

  /* @import "../../../../css/button.css"; */
  @import "/resources/css/button.css";
</style>