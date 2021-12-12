<template>
  <!-- Media画像-->
  <div class="media-imgs-wrapper">

    <media-img v-for="(img, index) in getMediaImgs" :key="index"
    :index="index"
    ref="imgs">
    </media-img>

  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import mediaImg from './MediaImgComponent.vue';
  export default {
    components: {
      mediaImg,
    },
    props : [
    ],
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImgs']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
    },
    methods : {
      ...mapMutations('mediaImgs', ['addMediaImgsObjectItem']),
      ...mapMutations('mediaImgs', ['updateIsInitializedImgs']),
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
          datas.forEach(data=>{
            this.addMediaImgsObjectItem(data);
          })
          this.updateIsInitializedImgs(true);
          // this.initStatus += 1;
        });
      },
    }
  }

</script>



<style scoped>
  #media-imgs-wrapper {
    /* pointer-events: none; */
  }

</style>