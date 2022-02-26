<template>
  <div class="all-audio-controller all-audio-pause-wrapper" @click="switchStereoMonauralAll">
    <div class="size-Adjust-box">
      <i id="switch-to-stereo-icon" class="fas fa-headphones fa-2x headphone" :class="{'green':isStereo}"></i>
    </div>
  </div>
</template>


<script>
  import { mapGetters, mapMutations} from 'vuex';
  export default {
    data : () => {
      return {
        isStereo : true,
      }
    },
    computed : {
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      audioNum : function(){ return this.getMediaAudios.length; },
    },
    methods : {
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),
      switchStereoMonauralAll(){
        for(let i=0; i<this.audioNum; i++){
          this.updateMediaAudiosObjectItem({index:i, key:'panningFlag', value:!this.isStereo});
        }
        this.isStereo = !this.isStereo;
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

  .headphone {
    color: grey;
  }
  .headphone:hover {
    cursor: pointer;
  }

  .green { color: greenyellow}
  
</style>