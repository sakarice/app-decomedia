<template>
  <div :id="audio_obj_with_index" class="audio-obj pos-a border-r-50per flex column a-center"
  :style="objTranslate" @mousedown.stop="calcDiff($event), judgeDelete($event)" @touchstart.stop="calcDiff($event), judgeDelete($event)">
    <!-- オーディオのアイコン -->
    <div class="audio-icon-wrapper border-r-50per">
      <img :src="audio['thumbnail_url']" class="w50px h50px border-r-50per" alt="?">
    </div>
    <span class="pos-a font-11 white">{{index+1}}</span>
    <i class="fas fa-caret-right fa-3x play-icon pos-a" v-on:click="play" v-show="!(isPlay)"></i>
    <i class="fas fa-pause fa-2x pause-icon pos-a" v-on:click="pause" v-show="isPlay"></i>    
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import {setDistanceLimit, calcDiffStart} from '../../../../functions/calcDiffBetweenAandBHelper'

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
      ...mapGetters('mediaAudios', ['getOneAudio']),
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
      isPlay:function(){ return this.getOneAudio(this.index)['isPlay']; },
    },
    methods : {
      ...mapMutations('mediaAudios', ['setTargetObjectIndex']),
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),
      initAudioData(){
        this.audio = Object.assign({},this.getOneAudio(this.index));
        this.initDiff();
      },
      play(){
        this.updateMediaAudiosObjectItem({index:this.index, key:'isPlay', value:true});
      },
      pause(){
        this.updateMediaAudiosObjectItem({index:this.index, key:'isPlay', value:false});
      },
      initDiff(){
        this.diff_x = this.audio['positionX'] ? this.audio['positionX']/5*this.radius : 0;
        this.diff_y = this.audio['positionZ'] ? this.audio['positionZ']/5*this.radius : 0;
      },
      calcDiff(e){
        setDistanceLimit(this.radius * 1.42); // 右上のゴミ箱アイコンまで届くように正方形の範囲に
        calcDiffStart(e, this.own_elem, this.center_x, this.center_y);
        this.own_elem.addEventListener('calcDiffBetweenAandB',this.moveActions, false);
        this.own_elem.addEventListener('calcDiffFinish',this.removeCalcDiffEvent, false);
      },
      moveActions(event){
        this.updateDiff(event);
        this.judgeDelete();
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
        this.own_elem.removeEventListener('calcDiffBetweenAandB',this.moveActions, false);
        this.own_elem.removeEventListener('calcDiffFinish',this.removeCalcDiffEvent, false);
      },
      judgeDelete(){
        this.$emit('moveObject', this.index, ...this.getOwnCenterAndRadius());
      },
      getOwnCenterAndRadius(){
        const rect = this.own_elem.getBoundingClientRect();
        const radius = Math.floor(rect.width) / 2;
        const center_x = Math.floor(rect.left) + radius;
        const center_y = Math.floor(rect.top) + radius;
        return [center_x, center_y, radius];
      }

    },
    watch : {
    },
    created(){
      this.initAudioData();
    },
    mounted(){
      // this.initDiff();
      this.own_elem = document.getElementById(this.audio_obj_with_index);
      document.body.addEventListener('deleteMediaAudio', this.initAudioData, false);
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
  outline: 1px solid darkgrey;
}

.play-icon, .pause-icon {
  color: rgb(0,255,0);
}

.play-icon {
  top:4px;
  left: 20px;
}
.pause-icon {
  top:11px;
}

.white { color: white; }

</style>