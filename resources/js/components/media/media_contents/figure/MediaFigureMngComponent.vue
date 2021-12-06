<template>

  <div class="figures-wrapper">
    <media-figure v-for="(figure, index) in getMediaFigures" :key="index"
    :index="index"
    ref="figures"
    @re-render-all="reRenderAll">
    </media-figure>
  </div>

</template>



<script>
  import { mapGetters, mapMutations } from 'vuex';
  import MediaFigure from './MediaFigureComponent.vue';


  export default {
    components : {
      MediaFigure,
    },
    props:[
      'index',
    ],
    data : ()=>{
      return {
      }
    },
    computed : {
      // ...mapGetters('mediaFigures', ['getMediaFigure']),
      ...mapGetters('mediaFigures', ['getMediaFigures']),
    },
    watch : {},
    methods : {
      // ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      reRenderAll(){
        console.log('called reRenderAll');
        this.$refs.figures.forEach(figure => {figure.init(); });
      },
    },
    created(){
    },
    mounted(){

      document.addEventListener('keydown', (e)=> {
        if(e.code=="Delete"){
          const figures_reverse = this.$refs.figures.reverse();
          figures_reverse.forEach(figure => {figure.delete(); });
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
    width: 100vw;
    height: 100vh;
  }

  /* .figure-list-item {
    position: absolute;
  }
  .figure-list-item:hover {
    cursor: pointer;
  } */



</style>