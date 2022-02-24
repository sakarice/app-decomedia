<template>
  <div class="all-audio-controller all-audio-pause-wrapper" @click="pauseAllAudio">
    <div class="size-Adjust-box">
      <i id="pause-all-icon" class="fas fa-pause fa-2x"></i>
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
      pauseAllAudio(){
        for(let i=0; i<this.audioNum; i++){
          this.updateMediaAudiosObjectItem({index:i, key:'isPlay', value:false});
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
  }

  #pause-all-icon {
    color: lightgrey;
  }
  #pause-all-icon:hover {
    cursor: pointer;
  }
  
</style>