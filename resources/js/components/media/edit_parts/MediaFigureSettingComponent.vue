<template>
  <!-- Media図形-->
  <div id="media-figure-setting-wrapper">
    <div class="width-input-wrapper">
      <span>横幅:</span>
      <input type="text" @input="updateMediaFigureObjectItem({key:'width', value:$event.target.value})">
    </div>
    <div class="height-input-wrapper">
      <span>縦幅:</span>
      <input type="text" @input="updateMediaFigureObjectItem({key:'height', value:$event.target.value})">
    </div>

  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    data : ()=>{
      return {}
    },
    computed : {
      ...mapGetters('mediaFigure', ['getMediaFigure']),
    },
    methods : {
      ...mapMutations('mediaFigure', ['updateMediaFigureObjectItem']),
    },
    created(){
    },
    mounted(){
    },
  }

</script>

<style scoped>
#media-figure-setting-wrapper{
  position: absolute;
  bottom: 20%;
  left: 50%;
}



</style>