<template>
  <!-- Media図形-->
  <!-- <ul class="figures-wrapper">
    <li class="figure-list-item" :id="index" v-for="(figure, index) in this.getMediaFigures" :key="index"
    :style="figureListCSSProperty(figure)">
      <media-figure
      :index="index">
      </media-figure>
    </li>
  </ul> -->

  <div class="figures-wrapper">
    <media-figure v-for="(figure, index) in this.getMediaFigures" :key="index"
    
    :index="index">
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
      figureListCSSProperty(figure){
        return {
          // left:figure['x_position']+'px'
          // ,top:figure['y_position']+'px'
          // ,width:figure['width']+'px'
          // ,height:figure['height']+'px'
          // ,transform:'rotate('+ figure['degree'] +'deg)'
        };
      }
    },
    created(){
    },
    mounted(){
    },
  }

</script>

<style scoped>

  /* .figures-wrapper {
    list-style: none;
    position: absolute;
    top: 0;
    left: 0;
    width : 100vw;
    height: 100vh;
  } */
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