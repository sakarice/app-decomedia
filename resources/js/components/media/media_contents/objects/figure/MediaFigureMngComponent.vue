<template>
  <div class="figures-wrapper">
    <media-figure v-for="(figure, index) in getMediaFigures" :key="index"
    :index="index"
    ref="figures"
    @show-editor="showEditor(index)"
    @change-figure-data="editorInit(index)"
    @re-render-all="reRenderAll">
    </media-figure>

    <figure-update v-show="isShowEditor"
     :index="editor_index"
     ref="Editor"
     @close-editor="closeEditor"
     @re-render="reRender">
    </figure-update>
  </div>
</template>


<script>
  import { mapGetters, mapMutations } from 'vuex';
  import MediaFigure from './MediaFigureComponent.vue';
  import figureUpdate from './FigureUpdateComponent.vue';

  export default {
    components : {
      MediaFigure,
      figureUpdate,
    },
    data : ()=>{
      return {
        "isShowEditor" : false,
        "editor_index" : -1,
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaFigures', ['getMediaFigures']),
    },
    watch : {},
    methods : {
      ...mapMutations('mediaFigures', ['updateIsInitializedFigures']),
      ...mapMutations('mediaFigures', ['addMediaFiguresObjectItem']),
            
      showEditor(index){
        this.isShowEditor = true;
        if(this.editor_index != index){
          this.editor_index = index;
          this.$refs.Editor.init(index);
        }
      },
      closeEditor(){ this.isShowEditor = false;},
      editorInit(index){this.$refs.Editor.init(index);},
      reRender(index){ this.$refs.figures[index].init(); },
      reRenderAll(){ this.$refs.figures.forEach(figure => {figure.init(); }); },

      getMediaFiguresFromDB(){
        return new Promise((resolve, reject) => {
          const url = '/mediaFigures/'+this.getMediaId;
          axios.get(url)
          .then(response=>{
            return resolve(response.data);
          })
          .catch(error=>{});
        })
      },
      initFigure(){
        this.getMediaFiguresFromDB()
        .then(datas=>{
          datas.forEach(data=>{
            this.addMediaFiguresObjectItem(data);
          })
          this.updateIsInitializedFigures(true);
          // this.initStatus += 2;
        });
      },
    },

    mounted(){
      document.addEventListener('objectDeleted', (e)=> {
        if(this.$refs.figures){
          // const figures_reverse = this.$refs.figures.reverse();
          // figures_reverse.forEach(figure => {figure.delete(); });
          this.reRenderAll();
        }
      })
    },

  }

</script>

<style scoped>

  .figures-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    /* width: 100vw;
    height: 100vh; */
  }

</style>