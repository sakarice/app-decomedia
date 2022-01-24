<template>
  <div :id="audio_obj_with_index" class="audio-obj pos-a border-r-50per flex column a-center"
  :style="objTranslate" @mousedown.stop="calcDiff($event)" @touchstart.stop="calcDiff($event)">
    <!-- オーディオのアイコン -->
    <div class="audio-icon-wrapper border-r-50per">
      <img :src="audio['thumbnail_url']" class="w50px h50px border-r-50per" alt="?">
    </div>
    <span class="pos-a font-11 white">{{index+1}}</span>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import {setDistanceLimit, calcDiffStart} from '../../../functions/calcDiffBetweenAandBHelper'

  export default {
    components: {
    },
    props : [
      "index",
      "center_x",
      "center_y",
      "radius",
    ],

    data : () => {
      return {
        own_elem : "",
        audio : "",
        diff_x : 0,
        diff_y : 0,
      }
    },
    computed : {
      ...mapGetters('mediaAudios', ['getIsInitializedAudios']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      ...mapGetters('mediaAudios', ['getMediaAudio']),
      audio_obj_with_index:function(){ return 'audio-obj-'+this.index; },
      objTranslate:function(){
        const style = {
          "transform" : "translate(" + this.diff_x + "px," + this.diff_y + "px)",
          // "transform" : "translateX(" + this.diff_x + "px)"
        }
        return style;
      },
      // panner_x:function(){ return this.diff_x / this.radius; },
      panner_x:function(){ return Math.round(100*this.diff_x / this.radius) / 20; },
      // ★y軸方向の変化をWebAudioAPIで採用している右手系に合わせz軸方向に設定する
      // panner_z:function(){ return this.diff_y / this.radius; },
      panner_z:function(){ return Math.round(100*this.diff_y / this.radius) / 20; },
    },
    methods : {
      ...mapMutations('mediaAudios', ['setTargetObjectIndex']),
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),
      getOneAudio(){
        this.setTargetObjectIndex(this.index);
        this.audio = Object.assign({},this.getMediaAudio);
      },
      initDiff(){
        this.diff_x = this.audio['positionX'] ? this.audio['positionX']/5*this.radius : 0;
        this.diff_y = this.audio['positionZ'] ? this.audio['positionZ']/5*this.radius : 0;
      },
      calcDiff(e){
        setDistanceLimit(this.radius);
        calcDiffStart(e, this.own_elem, this.center_x, this.center_y);
        this.own_elem.addEventListener('calcDiffBetweenAandB',this.updateDiffAndPanner, false);
        this.own_elem.addEventListener('calcDiffFinish',this.removeCalcDiffEvent, false);
      },
      updateDiffAndPanner(event){
        this.updateDiff(event);
        this.updatePanner();
      },
      updateDiff(event){
        this.diff_x = event.detail.diff_x;
        this.diff_y = event.detail.diff_y;
      },
      updatePanner(){
        this.updateMediaAudiosObjectItem({index:this.index, key:"positionX", value:this.panner_x});
        this.updateMediaAudiosObjectItem({index:this.index, key:"positionZ", value:this.panner_z});
      },
      removeCalcDiffEvent(){
        this.own_elem.removeEventListener('calcDiffBetweenAandB',this.updateDiffAndPanner, false);
        this.own_elem.removeEventListener('calcDiffFinish',this.removeCalcDiffEvent, false);
      }
    },
    watch : { 
    },
    created(){
      this.getOneAudio();
    },
    mounted(){
      this.initDiff();
      this.own_elem = document.getElementById(this.audio_obj_with_index);
    }

  }

</script>

<style scoped>
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";

.audio-obj {
  top: calc(50% - 20px);
  left: calc(50% - 20px);
}

.audio-icon-wrapper {
  outline: 1px solid black;
}

.white { color: white; }

</style>