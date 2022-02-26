<script>
  import { mapGetters, mapMutations} from 'vuex';
  export default {
    name : "MonauralAudio",
    props : [
      // storeから取得するオーディオオブジェクトのインデックス。
      'mediaAudioIndex'
    ],
    data : () => {
      return {
        player : "",
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
      masterVolume : function(val){
        this.player.volume = val;
      },
      currentTime : function(val){
        this.player.currentTime = val;
      },
      isPlay : function(val){
        if(!this.panningFlag){ // モノラルモード
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
          if(val===true){ // モノラルから立体音響に切り替わった時
            this.pause();
            this.updateMediaAudiosObjectItem({index:this.mediaAudioIndex, key:'currentTime', value:this.player.currentTime});
          } else if(val===false){        // 立体音響からモノラルに切り替わった時
            this.play();
          }
        }
      },
    },
    render(){
      return this.$scopedSlots.default({
        // player : player,
      });
    },

  }

</script>