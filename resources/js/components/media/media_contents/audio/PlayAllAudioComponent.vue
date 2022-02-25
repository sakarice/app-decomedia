<template>
  <div class="all-audio-controller all-audio-play-wrapper" @click="playAllAudio">
    <div class="size-Adjust-box">
      <i id="play-all-icon" class="fas fa-caret-right fa-3x"></i>
    </div>
  </div>  
</template>


<script>
  import { mapGetters, mapMutations} from 'vuex';
  export default {
    computed : {
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      audioNum : function(){ return this.getMediaAudios.length; },
    },
    methods : {
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),

      playAllAudio(){
        for(let i=0; i<this.audioNum; i++){
          this.updateMediaAudiosObjectItem({index:i, key:'isPlay', value:true});
        }
      },
    },
  }

</script>

<style scoped>

  .all-audio-controller {
    color: ghostwhite;
    min-width: 70px;
    margin: 0 5px;
    padding: 7px;
    font-size: 11px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }
  .all-audio-controller:hover {
    background-color: rgb(50,50,50);
    cursor: pointer;
  }

  #play-all-icon {
    color: green;
  }
  
</style>