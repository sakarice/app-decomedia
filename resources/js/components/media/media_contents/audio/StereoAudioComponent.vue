<template>
  <div>
    <audio-player-common>
      <template #default></template>
    </audio-player-common>
  </div>
</template>

<script>
  import { mapGetters, mapMutations} from 'vuex';
  import AudioPlayerCommon from './AudioPlayerCommonComponent.vue'

  export default {
    name : "StereoAudio",
    components : {
      AudioPlayerCommon,
    },
    props : [
      // storeから取得するオーディオオブジェクトのインデックス。
      'mediaAudioIndex'
    ],
    data : () => {
      return {
        player : "",
        ctx : "",
        inputNode : "",
        panner : "",
      }
    },
    computed : {
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      ...mapGetters('mediaAudios', ['getMasterVolume']),
      ...mapGetters('mediaAudios', ['getOneAudio']),
      audio        :function(){ return this.getOneAudio(this.mediaAudioIndex); },

      isPlay       :function(){ return this.audio['isPlay']; },
      loopSetting  :function(){ return this.audio['isLoop']; },
      masterVolume :function(){ return this.getMasterVolume; },
      volume       :function(){ return this.audio['volume']; },
      playerVolume :function(){ return this.masterVolume * this.volume;},
      currentTime  :function(){ return this.audio['currentTime']; },
      panningFlag  :function(){ return this.audio['panningFlag']; },
      panningModel :function(){ return this.audio['panningModel']; },
      panner_x     :function(){ return this.audio['positionX'] != 0 ? this.audio['positionX'] : 0; },
      panner_z     :function(){ return this.audio['positionZ'] != 0 ? this.audio['positionZ'] : 0; },
    },
    methods : {
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),

      play(){ this.player.play(); },
      pause(){ this.player.pause(); },
      updateLoopSetting(loopSetting){ this.player.loop = loopSetting},
      setDuration(duration){
        this.updateMediaAudiosObjectItem({index:this.mediaAudioIndex, key:'duration', value:duration});
      },
      finish(){ // 再生位置を終わりに設定して疑似的に再生終了を実現する
        this.player.currentTime = Math.floor(this.player.duration);
      },
      setPlayerInfo(){ // 親コンポーネントのmediaAudiosから再生情報を取得
        this.player.volume = this.getOneAudio(this.mediaAudioIndex)['volume'];
        this.player.loop = this.getOneAudio(this.mediaAudioIndex)['isLoop'];
      },
      setUpAudioContext(){
        const AudioContext = window.AudioContext || window.webkitAudioContext;
        const ctx = new AudioContext();
        this.ctx = ctx;
      },
      setUpPanner(ctx){
        const pannerOptions = {panningModel:"HRTF"};
        const panner = new PannerNode(ctx, pannerOptions);
        panner.positionX.value = this.panner_x;
        panner.positionZ.value = this.panner_z;
        this.panner = panner;
      },
      updatePannerPositionX(){ this.panner.positionX.value = this.panner_x;},
      updatePannerPositionZ(){ this.panner.positionZ.value = this.panner_z;},
      panningOn(){
        console.log('panning on');
        this.setUpAudioContext();
        this.setUpPanner(this.ctx);
        this.inputNode = this.ctx.createMediaElementSource(this.player);
        this.inputNode.connect(this.panner).connect(this.ctx.destination);
      },
    },
    created : function(){
      let tmpThis = this;
      const setAudioData = new Promise((resolve,reject)=>{
        tmpThis.player = new Audio();

        const setCrossOriginSetting = new Promise((resolve,reject)=>{
          tmpThis.player.crossOrigin = "anonymous";
          resolve();
        });
        setCrossOriginSetting.then(function(){
          tmpThis.player.src = tmpThis.getOneAudio(tmpThis.mediaAudioIndex)['audio_url'];
          tmpThis.setPlayerInfo();
          resolve();
        })
        tmpThis.player.onloadstart = function(){
          tmpThis.panningOn();
        }
      });
      setAudioData.then(function(){
        tmpThis.player.addEventListener('loadedmetadata', function(){
          const duration = tmpThis.player.duration;
          tmpThis.setDuration(duration);
        });
      })
    },
    watch : {
      loopSetting : function(val){
        this.updateLoopSetting(val);
      },
      playerVolume : function(val){
        this.player.volume = val;
      },
      currentTime : function(val){
        this.player.currentTime = val;
      },
      panningModel : function(val){
        this.panner.panningModel = val;
      },
      panner_x : function(){ this.updatePannerPositionX();},
      panner_z : function(){ this.updatePannerPositionZ();},
      isPlay : function(val){
        if(this.panningFlag){ // 立体音響モード
          if(val===true){ // 停止から再生に切り替わった時
            this.play();
          } else if(val===false){ // 再生から停止に切り替わった時
            this.pause();
            this.updateMediaAudiosObjectItem({index:this.mediaAudioIndex, key:'currentTime', value:this.player.currentTime});
          }
        }
      },
      panningFlag : function(val){
        if(this.isPlay){ // オーディオの再生中
          if(val===false){ // 立体音響からモノラルに切り替わった時
            this.pause();
            this.updateMediaAudiosObjectItem({index:this.mediaAudioIndex, key:'currentTime', value:this.player.currentTime});
          } else if(val===true){  // モノラルから立体音響に切り替わった時
            this.play();
          }
        }
      },
    },
    // render(){
    //   return this.$scopedSlots.default({});
    // },

  }

</script>