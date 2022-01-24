<template>
  <div id="stereo-phonic-arrange-field-wrapper" v-show="isShowField">
    <div id="stereo-phonic-arrange-field" class="flex column"
    :style="fieldSize">

      <!-- 閉じるボタン -->
      <div class="hide-field-icon-wrapper flex a-center p5">
        <i class="fas fa-times fa-2x hide-field-icon p10" @click="hideField"></i>
      </div>

      <!-- アイコン配置による定位設定エリア -->
      <div class="arrange-field-area w100 h100 flex a-center j-center">
        <div class="arrange-field border-r-50per flex a-center j-center"
        :style="arrangeAreaSize">

        <div></div>
          <div class="arrange-object-wrapper pos-r w100 h100 border-r-50per">
            <!-- 中心点 -->
            <!-- <div class="center-point pos-a w10px h10px border-r-50per"></div> -->
            <img src="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/%E3%83%98%E3%83%83%E3%83%89%E3%83%95%E3%82%A9%E3%83%B3%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B35.svg" alt=""
            class="center-point pos-a w30px h30px border-r-50per">

            <!-- オーディオのアイコン -->
            <audio-object v-for="(audio, index) in getMediaAudios" :key="index"
            :index="index" :center_x="center_x" :center_y="center_y" :radius="radius">
            </audio-object>
          </div>

        </div>
      </div>

    </div>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import audioObject from './AudioObjectComponent.vue'

  export default {
    components: {
      audioObject,
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
      ...mapGetters('mediaContentsField', ['getMediaContentsField']),
      ...mapGetters('mediaAudios', ['getIsInitializedAudios']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      fieldSize:function(){
        const style = {
          'width' : this.getMediaContentsField['width'] + "px",
          'height' : this.getMediaContentsField['height'] + "px",
        }
        return style;
      },
      shorter:function(){ // 縦幅と横幅を比較し短い方の値を返す
        const width = this.getMediaContentsField['width'];
        const height = this.getMediaContentsField['height'];
        return width < height ? width : height;
      },
      radius:function(){ return (this.shorter - 100)/2; },
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
      getCenterPosition(){
        this.center_x = window.innerWidth/2;
        this.center_y = window.innerHeight/2 + 70;
      },
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
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
  width: 100%;
  margin-top: 70px;
  margin-bottom: 10px;
  display: flex;
  justify-content: center;
  align-items: center;
}

#stereo-phonic-arrange-field{
  background-color: rgba(50,50,50,0.8);
  border-radius: 5px;
}

.hide-field-icon-wrapper {
  color: red;
}

.hide-field-icon:hover {
  cursor: pointer;
}

.arrange-field {
  outline: 3px solid white;
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
  outline: 2px solid greenyellow;
  border-radius: 50%;
}

.arrange-obj {
  top: calc(50% - 20px);
  left: calc(50% - 20px);
}

.audio-icon-wrapper {
  outline: 1px solid black;
}

</style>