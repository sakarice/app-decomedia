<template>
  <!-- Media画像-->
  <div class="media-texts-wrapper">

    <media-text v-for="(text, index) in getMediaTexts" :key="index"
    :index="index"
    ref="texts">
    </media-text>

  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import mediaText from './MediaTextComponent.vue';
  export default {
    components: {
      mediaText,
    },
    props : [
    ],
    data : ()=>{
      return {
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaTexts', ['getMediaText']),
      ...mapGetters('mediaTexts', ['getMediaTexts']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
    },
    methods : {
      ...mapMutations('mediaTexts', ['addMediaTextsObjectItem']),
      ...mapMutations('mediaTexts', ['updateIsInitializedTexts']),
      ...mapMutations('mediaTexts', ['deleteMediaTextsObjectItem']),
      getMediaTextFromDB(){
        return new Promise((resolve, reject) => {
          const url = '/mediaText/'+this.getMediaId;
          axios.get(url)
          .then(response=>{
            return resolve(response.data);
          })
          .catch(error=>{});
        })
      },
      initText(){
        this.getMediaTextFromDB()
        .then(datas=>{
          datas.forEach(data=>{
            this.addMediaTextsObjectItem(data);
          })
          this.updateIsInitializedTexts(true);
        });
      },
      reRender(index){ this.$refs.texts[index].init(); },
      reRenderAll(){ 
        this.$refs.texts.forEach((text) => { text.init(); }); 
      },
    },
    mounted(){
      document.body.addEventListener('objectDeleted', (e)=> {
        if(this.$refs.texts){
          this.reRenderAll();
        }
      })
    },
  }

</script>



<style scoped>
  #media-texts-wrapper {
    /* pointer-events: none; */
  }




</style>