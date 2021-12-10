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
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    props : [
      "index"
    ],
    computed : {
      ...mapGetters('media', ['getMediaId']),
      // ...mapGetters('mediaImg', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImgs']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      // ↓storeの値には単位[px]が付いてないので追加する
      mediaImgWidth() { return this.$store.getters['mediaImgs/getMediaImg']['width'] + "px"; },
      mediaImgHeight() { return this.$store.getters['mediaImgs/getMediaImg']['height'] + "px"; },
    },
    methods : {
      ...mapMutations('mediaImg', ['updateMediaImgObjectItem']),
      ...mapMutations('mediaImg', ['updateIsInitializedImg']),
      getMediaImgFromDB(){
        return new Promise((resolve, reject) => {
          const url = '/mediaImg/'+this.getMediaId;
          axios.get(url)
          .then(response=>{
            return resolve(response.data);
          })
          .catch(error=>{});
        })
      },
      initImg(){
        this.getMediaImgFromDB()
        .then(datas=>{
          for(let key in datas){
            this.updateMediaImgObjectItem({key:key, value:datas[key]});
          }
          this.updateIsInitializedImg(true);
          // this.initStatus += 1;
        });
      },
    }
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