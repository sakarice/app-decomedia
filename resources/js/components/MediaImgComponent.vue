<template>
  <!-- Media画像-->
  <div id="media-img-wrapper"
  v-bind:style="{'z-index' : getMediaImg['layer']}">

    <div id="media-img-frame"
      v-show="getMediaSetting['isShowImg']"
      v-on:click="$emit('parent-action', 'imgModal')"
      v-bind:style="{width: mediaImgWidth, height: mediaImgHeight, opacity: getMediaImg['opacity']}">
      <p v-show="!(getMediaImg['url'])"></p>
      <img id="media-img"
       :src="getMediaImg['url']"
       v-show="getMediaImg['url']" alt="画像が選択されていません"
       v-bind:style="{width: mediaImgWidth, height: mediaImgHeight, opacity: getMediaImg['opacity']}">
    </div>

  </div>
</template>

<script>
  import { mapGetters } from 'vuex';
  export default {
    props : [
    ],
    computed : {
      ...mapGetters('mediaImg', ['getMediaImg']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      // ↓storeの値には単位[px]が付いてないので追加する
      mediaImgWidth() { return this.$store.getters['mediaImg/getMediaImg']['width'] + "px"; },
      mediaImgHeight() { return this.$store.getters['mediaImg/getMediaImg']['height'] + "px"; },
    },
  }

</script>



<style scoped>
  #media-img-wrapper {
    pointer-events: none;
  }
  #media-img-frame {
    display: flex;
    justify-content: center;
    align-items: center;
  }

</style>