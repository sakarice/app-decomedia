<template>
  <div id="change-disp-audio-wrapper" v-show="isShowAudios">
    <i @click="changeDispAudioState()" class="fas fa-music fa-2x change-disp-audio" v-show="!isShowAudio"></i>
    <i @click="changeDispAudioState()" class="fas fa-times fa-2x change-disp-audio" v-show="isShowAudio"></i>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
    data : ()=>{
      return {
        isShowAudio : false,
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      mediaAudioNum : function(){
        return this.getMediaAudios.length;
      },
      isShowAudios(){
        return this.mediaAudioNum > 0 ? true : false;
      },
      isEditMode(){
        const mode = this.$route.name;
        if((mode=="create") || (mode=="edit")){
          return true;
        } else {
          return false;
        }
      },
    },
    watch: {
      isShowAudios : function(newVal){
        if(newVal == 0){ this.hideAudios(); }
      },
    },
    methods : {
      changeDispAudioState(){
        this.isShowAudio = !(this.isShowAudio);
        const event = new CustomEvent('changeDispAudioState', {detail:this.isShowAudio} );
        document.body.dispatchEvent(event);
      },
      hideAudios(){
        const event = new CustomEvent('changeDispAudioState', {detail:false} );
        document.body.dispatchEvent(event);
        this.isShowAudio = false;
      }
    }
  }

</script>

<style scoped>

  #change-disp-audio-wrapper {
    position: absolute;
    bottom: 10px;
    right: 10px;
    z-index: 12;
    height: 60px;
    width: 60px;

    display: flex;
    justify-content: center;
    align-items: center;
  }


  .change-disp-audio {
    color: lightgrey;
  }

  .change-disp-audio {
    cursor: pointer;
  }

  .is-reverse{
    transform: scale(-1, 1);
  }


  @media screen and (min-width:481px){
    #change-disp-audio-wrapper {
      border-radius: 50%;
      background-color: rgba(20,20,20,0.8);
    }
    #change-disp-audio-wrapper:hover {
      background-color: rgba(0,110,110, 0.5);
    }

  }



  @media screen and (max-width:480px){
    #change-disp-audio-wrapper {
      bottom: 50px;
      right: 7;
      width: 45px;
      height: 45px;
      background: linear-gradient(
        -45deg
        , #333333 50%
        , #333333
        , transparent 50%
      );
    }

    .change-disp-audio {
    padding: 10px;
    }

  }

</style>