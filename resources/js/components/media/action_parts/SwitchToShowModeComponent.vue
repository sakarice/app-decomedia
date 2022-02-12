<template>
  <router-link v-if="isShow" :to="'/media/'+getMediaId" class="change-mode action-trigger-wrapper link-to-show-mode">
    <div class="action-trigger mode-icon-wrapper">
      <i class="fas fa-tv show-mode-icon"></i>
    </div>
    <span class="action-trigger-subtitle"></span>
  </router-link>
</template>

<script>
import { mapGetters } from 'vuex';

  export default {
    data : ()=>{
      return {}
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('loginState', ['getIsLogin']),
      ...mapGetters('media', ['getIsMyMedia']),
      isShow:function(){
        return this.getIsLogin && this.getIsMyMedia ? true:false
      },
    },
    methods : {},
  }

</script>

<style scoped>
/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

.show-mode-icon {
  display: inline-block;
}
.link-to-show-mode {
  color:black;
  padding: 1px 4px;
}
.link-to-show-mode:hover {
  background-color: rgb(238,238,240);
}

.action-trigger-subtitle::after {
  content: '閲覧モードへ';
}

@media screen and (max-width:480px) {
  .change-mode {
    bottom: 45px;
    left: 5px;
  }
  
  .mode-icon-wrapper {
    margin-bottom: -4px;
  }
  
}

</style>