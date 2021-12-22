<template>
  <!-- Media画像-->
  <div class="media-imgs-wrapper">

    <media-img v-for="(img, index) in getMediaImgs" :key="index"
    :index="index"
    @add-active-index="addActiveIndex(index)"
    @del-active-index="delActiveIndex(index)"
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
    data : ()=>{
      return {
        'active_indexs' : [],
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaImgs', ['getMediaImgs']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
    },
    methods : {
      ...mapMutations('mediaImgs', ['addMediaImgsObjectItem']),
      ...mapMutations('mediaImgs', ['updateIsInitializedImgs']),
      ...mapMutations('mediaImgs', ['deleteMediaImgsObjectItem']),
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
      reRender(index){ this.$refs.imgs[index].init(); },
      reRenderAll(){ 
        this.$refs.imgs.forEach(img => { img.init(); }); 
      },
      addActiveIndex(index){ this.active_indexs.push(index);},
      delActiveIndex(index){
        const del_index = this.active_indexs.indexOf(index);
        this.active_indexs.splice(del_index, 1);
      },
    },
    mounted(){
      document.addEventListener('keydown', (e)=> {
        if(e.code=="Delete"){
          // this.active_indexs.forEach(index=>{
          //   this.deleteMediaImgsObjectItem(index);
          //   console.log(index);
          //   this.delActiveIndex(index);
          // })
          this.reRenderAll();
        }
      })
    },
  }

</script>



<style scoped>
  #media-imgs-wrapper {
    /* pointer-events: none; */
  }

</style>