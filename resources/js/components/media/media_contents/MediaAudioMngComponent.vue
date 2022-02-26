<template>
  <div id="stereo-phonic-arrange-field-wrapper" v-show="isShowField">
    <div id="stereo-phonic-arrange-field" class="flex column">

      <div class="upper-menu pos-r flex j-center a-center p10">
        <!-- 閉じるボタン -->
        <i class="fas fa-times fa-2x hide-field-icon" @click="hideField"></i>
        <!-- 立体音響設定トグル -->
        <div class="flex a-center">
          <switch-stereo-monaural-all-toggle></switch-stereo-monaural-all-toggle>
        </div>
      </div>

      <!-- 設定画面表示切替ボタン -->
      <div id="change-disp-setting-wrapper">
        <i @click="changeDispSetting()" class="fas fa-cog fa-2x change-disp-setting"></i>
      </div>

      <!-- アイコン配置による定位設定エリア -->
      <stereo-phonic-arrange-field></stereo-phonic-arrange-field>

      <li :id="index" v-for="(mediaAudio, index) in getMediaAudios" :key="index" style="list-style:none">
        <monaural-audio :mediaAudioIndex="index">
          <template #default></template>
        </monaural-audio>

        <stereo-audio :mediaAudioIndex="index">
          <template #default></template>
        </stereo-audio>
      </li>


      <!-- 全オーディオのコントローラー -->
      <!-- オーディオ再生・停止 -->
      <div v-show="isEditMode" class="all-audio-controll-wrapper">
        <play-all-audio></play-all-audio>
        <pause-all-audio></pause-all-audio>
        <change-master-volume></change-master-volume>
      </div>


    </div>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import audioObject from './audio/AudioObjectComponent.vue';
  import MonauralAudio from './audio/MonauralAudioComponent.vue';
  import StereoAudio from './audio/StereoAudioComponent.vue';
  import stereoPhonicArrangeField from './StereoPhonicArrangeFieldComponent.vue';
  import switchStereoMonauralAllToggle from './audio/SwitchStereoMonauralAllToggleComponent.vue';
  import playAllAudio from './audio/PlayAllAudioComponent.vue';
  import pauseAllAudio from './audio/PauseAllAudioComponent.vue';
  import changeMasterVolume from './audio/ChangeMasterVolumeComponent.vue';

  export default {
    components: {
      audioObject,
      MonauralAudio,
      StereoAudio,
      stereoPhonicArrangeField,
      switchStereoMonauralAllToggle,
      playAllAudio,
      pauseAllAudio,
      changeMasterVolume,
    },
    props : [
    ],

    data : () => {
      return {
        isShowField : false,
        center_x : 0,
        center_y : 0,
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('media', ['getMode']),
      ...mapGetters('mediaContentsField', ['getMediaContentsField']),
      ...mapGetters('mediaAudios', ['getIsInitializedAudios']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      isEditMode:function(){
        return (this.getMode==1 || this.getMode==2) ? true : false;
      },
      fieldSize:function(){
        const style = {
          'width' : this.shorter + "px",
          'height' : this.shorter + 50 + "px",
        }
        return style;
      },
      shorter:function(){ // 縦幅と横幅を比較し短い方の値を返す
        const width = this.getMediaContentsField['width'];
        const height = this.getMediaContentsField['height'];
        return width < height ? width : height;
      },
      radius:function(){ return (this.shorter - 50)/2; },
      arrangeAreaSize:function(){
        const style = {
          'width' : this.radius*2 + "px",
          'height' : this.radius*2 + "px",
        }
        return style;
      },
      centerPosition:function(){
        const style = {
          "left" : this.center_x + "px",
          "top" : this.center_y + "px",
          "background-color" : "red",
          "z-index" : 10,
        }
        return style;
      },
    },
    methods : {
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),
      changeDispField(){
        if(this.isShowField){
          this.hideField();
        } else {
          this.showField();
        }
      },
      showField(){
        console.log('show stereo arrange field');
        this.isShowField = true;
      },
      hideField(){
        console.log('hide stereo arrange field');
        this.isShowField = false;
      },
      changeDispSetting(){
        const event = new CustomEvent('changeDispSetting' );
        document.body.dispatchEvent(event);
      },
      getCenterPosition(){
        this.center_x = window.innerWidth/2;
        this.center_y = window.innerHeight/2 + 70;
      },
      playAllAudios(){
        for(let i=0; i<this.getMediaAudios.length; i++){
          this.updateMediaAudiosObjectItem({index:i, key:'isPlay', value:true});
        }
      },
      changeStereoMonaural(isStereo){this.isStereo = isStereo;},     
    },
    watch : { 
    },
    created(){
      document.body.addEventListener('changeDispStereoPhonicArrangeField',this.changeDispField, false);
      document.body.addEventListener('showStereoPhonicArrangeField',this.showField, false);
      document.body.addEventListener('hideStereoPhonicArrangeField',this.hideField, false);
      
    },
    mounted(){
      this.getCenterPosition();
    }

  }

</script>

<style scoped>
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";

#stereo-phonic-arrange-field-wrapper{
  z-index: 11;
  position: fixed;
  top: 0;
  bottom: 5px;
  margin-top: 80px;
  display: flex;
  justify-content: center;
  align-items: center;
}

#stereo-phonic-arrange-field{
  position: relative;
  background-color: rgba(0,30,50,1);
  border-radius: 5px;
}

.hide-field-icon {
  color: rgb(150,0,0);
  position: absolute;
  top : 0;
  left : 0;
  padding: 4px 8px;
  border-top-left-radius: 5px;
}

.hide-field-icon:hover {
  cursor: pointer;
  background-color: rgba(0,0,0,0.3);
}

#change-disp-setting-wrapper {
  position: absolute;
  bottom: 50px;
  right: 10px;
  margin-right: 10px;
  margin-bottom: 10px;
  z-index: 12;
  height: 60px;
  width: 60px;
  border-radius: 50%;

  display: flex;
  justify-content: center;
  align-items: center;

  transition: background-color 0.5s;
}
.change-disp-setting { color: white;}

.arrange-field-area {
  padding: 30px;
}

.arrange-field {
  outline:1px solid rgba(255,255,255,0.3);
}

.arrange-field::before {
  content: "";
  position: absolute;
  top : 15%;
  bottom: 15%;
  left: 15%;
  right: 15%;
  outline:1px solid rgba(255,255,255,0.5);
  border-radius: 50%;
}

.center-point {
  background-color: greenyellow;
  padding: 2px;
  top: calc(50% - 15px);
  left: calc(50% - 15px);
}

.arrange-object-wrapper::before {
  content: "";
  position: absolute;
  top : 30%;
  bottom: 30%;
  left: 30%;
  right: 30%;
  outline:1px solid rgba(255,255,255,0.7);
  border-radius: 50%;
}

.arrange-object-wrapper::after {
  content: "";
  position: absolute;
  top : 50%;
  bottom: 50%;
  left: 50%;
  right: 50%;
  outline: 1px solid white;
  border-radius: 50%;
}


.arrange-obj {
  top: calc(50% - 20px);
  left: calc(50% - 20px);
}

.audio-icon-wrapper {
  outline: 1px solid black;
}

/* 全オーディオの再生停止コントローラー */
.all-audio-controll-wrapper {
  padding: 2px 0;
  width: 100%;
  background-color: rgba(0,0,0,0.3);
  border-radius: 2px;

  display: flex;
  justify-content: center;
}


</style>