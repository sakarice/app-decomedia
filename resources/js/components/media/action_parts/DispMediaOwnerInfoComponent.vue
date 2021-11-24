<template>
  <div id="disp-media-owner-modal-wrapper" class="icon-wrapper" v-on:click.stop="showModal('mediaOwnerInfo')">
    <i class="fas fa-user-circle fa-2x"></i>
    <!-- ユーザプロフィール -->
    <!-- <media-owner-info-component
    v-show="isShowModal['mediaOwnerInfo']">
    </media-owner-info-component> -->
  </div>
</template>

<script>
// import { mapGetters } from 'vuex';
// import MediaOwnerInfoComponent from '../../MediaOwnerInfoComponent.vue';

  export default {
    components : {
      // MediaOwnerInfoComponent,
    },
    computed : {
      // ...mapGetters('loginState', ['getIsLogin']),
      // ...mapGetters('media', ['getIsMyMedia']),
    },
    methods : {
      showModal(target){
        this.$emit('show-modal',target);
      }
    }
  }

</script>

<style scoped>

  .icon-wrapper {
    padding: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>