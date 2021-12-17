<template>
  <div id="change-disp-audio-wrapper" v-show="isShowAudios">
    <span v-if="isEditMode" class="media-audio-num">{{mediaAudioNum}}</span>
    <i @click="changeDispAudioState()" class="fas fa-chevron-left fa-3x change-disp-audio for-pc-tablet" v-bind:class="{'is-reverse': isShowAudio}"></i>
    <i @click="changeDispAudioState()" class="fas fa-music fa-2x change-disp-audio for-mobile" v-show="!isShowAudio"></i>
    <i @click="changeDispAudioState()" class="fas fa-times fa-2x change-disp-audio for-mobile" v-show="isShowAudio"></i>
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
    height: auto;
    border-radius: 50%;
    background-color: rgba(20,20,20,0.8);

    display: flex;
    justify-content: center;
    align-items: center;
  }

  .change-disp-audio {
  color: lightgrey;
  padding: 10px 19px 10px 15px;
  /* margin: 0 10px 10px 0;
  border-radius: 50%;
  background-color: rgba(0,0,0, 0.5); */
  }
  .change-disp-audio:hover {
    background-color: rgba(0,110,110, 0.5);
    cursor: pointer;
  }

  .media-audio-num {
    z-index: 1;
    background-color: rgba(50, 110, 110, 0.7);
    color: white;
    border-radius: 50%;
    position: absolute;
    left: -10px;
    top: -10px;
    padding: 0 8px;
    font-size: 16px;
  }

  .is-reverse{
    transform: scale(-1, 1);
  }



  @media screen and (max-width:480px){
    #change-disp-audio-wrapper {
      bottom: 35px;
      right: 0;
      width: 55px;
      height: 55px;
    }

    .change-disp-audio {
    padding: 10px;
    }

    .media-audio-num {
      padding: 0 6px;
      top: -3px;
      left: -3px;
      font-size: 13px;    
    }

  }

</style>