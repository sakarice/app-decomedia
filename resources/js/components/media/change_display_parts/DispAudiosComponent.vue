<template>
  <div id="change-disp-audio-wrapper" v-show="isShowAudios">
    <!-- <i @click="changeDispSterePhonicArrangeField()" class="fas fa-headphones-alt fa-2x change-disp-audio" v-show="!isShowAudio"></i>
    <i @click="changeDispSterePhonicArrangeField()" class="fas fa-times fa-2x change-disp-audio" v-show="isShowAudio"></i> -->
    <img class="change-disp-audio" style="width:40px" src="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/sround1.svg" alt=""
     @click="changeDispSterePhonicArrangeField()">
    <span class="audio-num">{{mediaAudioNum}}</span>
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
      // changeDispAudioState(){
      //   this.isShowAudio = !(this.isShowAudio);
      //   const event = new CustomEvent('changeDispAudioState', {detail:this.isShowAudio} );
      //   document.body.dispatchEvent(event);
      // },
      changeDispSterePhonicArrangeField(){
        const event = new CustomEvent('changeDispStereoPhonicArrangeField');
        document.body.dispatchEvent(event);
      },
      hideAudios(){
        const event = new CustomEvent('changeDispAudioState', {detail:false} );
        document.body.dispatchEvent(event);
        this.isShowAudio = false;
      },
      flashElem(){
        console.log('flashElem');
        const targetElem = document.getElementById('change-disp-audio-wrapper');
        targetElem.classList.add('flash');
        setTimeout(function(){
          targetElem.classList.remove('flash');
        }, 100);
      },
    },
    mounted(){
      const listenerElem = document.getElementById('change-disp-audio-wrapper');
      listenerElem.addEventListener('addMediaAudio',this.flashElem,false);
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
    border-radius: 50%;

    display: flex;
    justify-content: center;
    align-items: center;

    transition: background-color 0.5s;
  }

  #change-disp-audio-wrapper.flash {
    background-color: greenyellow;
    transition-duration: 0s;
  }


  /* .change-disp-audio {
    color: yellow;
  } */

  .change-disp-audio {
    cursor: pointer;
  }

  .audio-num {
    color: rgb(170,170,0);
    position: absolute;
    z-index: 1;
    bottom: 3px;
    right: 9px;
  }

  .is-reverse{
    transform: scale(-1, 1);
  }


  @media screen and (min-width:481px){
    #change-disp-audio-wrapper {
      background-color: rgba(20,20,20,0.7);
      opacity: 0.9;
    }
    #change-disp-audio-wrapper:hover {
      opacity: 1;
    }

  }



  @media screen and (max-width:480px){
    #change-disp-audio-wrapper {
      background-color: rgba(20,20,20,0.3);
      bottom: 30px;
      right: 0;
      width: 45px;
      height: 45px;
    }

    .change-disp-audio {
      padding: 10px;
    }

    .audio-num {
      bottom: -7px;
      right: 3px;
    }


  }

</style>