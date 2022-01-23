<template>
  <!-- <transition name="right-slide"> -->
    <!-- Mediaオーディオ -->
  <div id="media-audio-wrapper" v-show="isShowAudio" :class="{'disp-front':isShowAudio}"
  @click="hideSetting" @touchstart="hideSetting">
    <div class="audio-num-wrapper">
      <span v-if="isEditMode" class="media-audio-num">{{mediaAudioNum}}/{{maxAudioNum}}</span>
    </div>

      <!-- 選択したオーディオ一覧 -->
    <div id="media-audio-frame">
      <!-- オーディオのサムネと各種アイコン -->
      <ul id="audios">
        <li class="audio-area" :id="index" v-for="(mediaAudio, index) in getMediaAudios" :key="index">
          <!-- ループ -->
          <i class="loop-icon fas fa-undo-alt fa-xs" @click.stop="updateLoopSetting(index)"
          v-show="isEditMode" :class="{'isLoop' : mediaAudio['isLoop']}"></i>

          <!-- オーディオアイコン -->
          <div class="audio-wrapper"
          @click.stop @touchstart.stop>
            <media-audio-player-component
            @setMediaAudioDuration="setMediaAudioDuration"
            @taskAfterAudioAdded="taskAfterAudioAdded"
            :mediaAudioIndex="index"
            :ref="'mediaAudioPlayer'">'
            </media-audio-player-component>
            <span class="audio-index">{{index+1}}</span>
          </div>
          <!-- オーディオ名 -->
          <div v-if="mediaAudio" class="media-audio-name-wrapper">
            <span class="media-audio-name">
              {{mediaAudio['name']}}
            </span>
          </div>

          
          <!-- 設定表示切替用アイコン -->
          <div class="setting-disp-wrapper" @click.stop @touchstart.stop>
            <!-- 音量、ループ、削除の設定 -->
            <div class="setting-disp-trigger" @click="toggleAudioSetting(index)">
              <i class="setting-icon fas fa-cog"></i>
            </div>
            <!-- 立体音響の設定 -->
            <div class="setting-disp-trigger" @click="togglePanningSetting(index)">
              <i class="setting-icon fas fa-headphones-alt" v-show="isEditMode"></i>
            </div>
          </div>

          <!-- オーディオの設定(ボリューム、ループ、削除) -->
          <div class="setting-wrapper audio-setting-wrapper" @click.stop @touchstart.stop>
            <div class="audio-settings setting-cover">
              <div class="del-and-loop-wrapper">
                <!-- 削除 -->
                <i class="media-audio-delete-icon setting-icon fas fa-trash" @click="taskWhenAudioDelete(index)" v-show="isEditMode"></i>
                <!-- ループ -->
                <i class="loop-setting-icon setting-icon fas fa-undo-alt" @click="updateLoopSetting(index)" v-show="isEditMode" :class="{'isLoop' : mediaAudio['isLoop']}"></i>
              </div>
              <!-- ボリューム -->
              <div class="audio-vol-wrapper audio-setting">
                <i class="media-audio-vol-icon fas fa-volume-off fa-2x"></i>
                <div class="vol-bar-wrapper">
                  <input type="range" :id="index" class="audio-vol-range" v-on:input="updateAudioVol(index,$event)" min="0" max="1" step="0.01">
                </div>
              </div>
            </div>
          </div>

          <!-- 立体音響の設定 -->
          <div class="setting-wrapper panning-setting-wrapper" @click.stop @touchstart.stop>
            <div class="panning-settings setting-cover">
              <span class="panning-label">立体音響</span>
              <i class="setting-icon fas fa-rss" @click="switchPanning(index)"
               v-show="isEditMode" :class="{'panning-on':mediaAudio['panningFlag']}"></i>
               <select id="select-panning-model" @input="changePanningModel(index, $event.target.value)">
                 <option value="HRTF">Type:A</option>
                 <option value="equalpower">Type:B</option>
               </select>
            </div>
          </div>

        </li>

        <li class="audio-area non-audio-frame" v-for="n in 5-(mediaAudioNum)" :key="5-n">
          <div class="dummy-audio-icon"></div>
        </li>
      </ul>
    </div>

      <!-- オーディオ再生・停止 -->
    <div v-show="isEditMode" class="all-audio-controll-wrapper">
      <!-- 全オーディオ再生開始 -->
      <div class="all-audio-controller all-audio-play-wrapper" @click="playAllAudio">
        <div class="size-Adjust-box">
          <i id="play-all-icon" class="fas fa-caret-right fa-3x"></i>
        </div>
      </div>
      <!-- 全オーディオ停止 -->
      <div class="all-audio-controller all-audio-finish-wrapper" @click="finishAllAudio">
        <div class="size-Adjust-box">
          <i id="finish-all-icon" class="fas fa-pause fa-2x"></i>
        </div>
      </div>
      <!-- 立体音響定位の設定画面を表示 -->
      <div class="all-audio-controller disp-panning-setting-wrapper" @click="dispSterePhonicArrangeField">
        <div class="size-Adjust-box">
          <i id="disp-panning-setting-icon" class="fas fa-headphones-alt fa-2x"></i>
        </div>
      </div>
    </div>


    <!-- <stereo-phonic-arrange v-show="isShowSterePhonicArrangeField">
    </stereo-phonic-arrange> -->


  </div>


  <!-- </transition> -->
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import MediaAudioPlayer from './MediaAudioPlayerComponent.vue';
  import StereoPhonicArrange from './StereoPhonicArrangeComponent.vue';

  export default {
    components: {
      MediaAudioPlayer,
      StereoPhonicArrange,
    },
    props : [
      'maxAudioNum',
    ],

    data : () => {
      return {
        isShowAudio : false,
        isEditMode : false,
        longestAudioDuration : 0,
        isShowAudioSettings : [false,false,false,false,false,],
        isShowPanningSettings : [false,false,false,false,false,],
        isShowSterePhonicArrangeField : false,
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaAudios', ['getIsInitializedAudios']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      mediaAudioNum : function(){
        return this.getMediaAudios.length;
      }
    },
    methods : {
      ...mapMutations('mediaAudios', ['updateIsInitializedAudios']),
      ...mapMutations('mediaAudios', ['addMediaAudiosObjectItem']),
      ...mapMutations('mediaAudios', ['deleteMediaAudiosObjectItem']),
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),
      ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
      getMediaAudiosFromDB(){
        return new Promise((resolve, reject) => {
          const url = '/mediaAudios/'+this.getMediaId;
          axios.get(url)
          .then(response=>{
            return resolve(response.data);
          })
          .catch(error=>{});
        })
      },
      initAudio(){
        this.getMediaAudiosFromDB()
        .then(datas=>{
          datas.forEach(data=>{
            this.addMediaAudiosObjectItem(data);
          })
          this.updateIsInitializedAudios(true);
          // this.initStatus += 2;
        });
      },
      // media閲覧時に最初に実行される
      hideAudio(){ this.isShowAudio = false; },
      hideSetting(){
        const audio_settings = document.getElementsByClassName('audio-settings');
        const panning_settings = document.getElementsByClassName('panning-settings');
        for(let i=0; i<audio_settings.length; i++){
          audio_settings[i].style.display = "none";
          panning_settings[i].style.display = "none";
          this.isShowAudioSettings[i] = false;
          this.isShowPanningSettings[i] = false;
        }
      },
      hideAudiogSetting(index){
        const audio_settings = document.getElementsByClassName('audio-settings');
        audio_settings[index].style.display = "none";
        this.isShowAudioSettings[index] = false;
      },
      hidePanningSetting(index){
        const panning_settings = document.getElementsByClassName('panning-settings');
        panning_settings[index].style.display = "none";
        this.isShowPanningSettings[index] = false;
      },
      toggleAudioSetting(index){
        const audio_settings = document.getElementsByClassName('audio-settings');
        const val = this.isShowAudioSettings[index];
        audio_settings[index].style.display = val ? "none" : "flex";
        this.isShowAudioSettings[index] = !val;
        if(this.isShowAudioSettings[index]){ this.hidePanningSetting(index);}
      },
      togglePanningSetting(index){
        const panning_settings = document.getElementsByClassName('panning-settings');
        const val = this.isShowPanningSettings[index];
        panning_settings[index].style.display = val ? "none" : "flex";
        this.isShowPanningSettings[index] = !val;
        if(this.isShowPanningSettings[index]){ this.hideAudiogSetting(index);}
      },
      changePanningModel(index,value){
        this.updateMediaAudiosObjectItem({index:index, key:'panningModel', value:value});
      },
      // 親コンポーネントから実行される
      validEditMode(){ this.isEditMode = true; },
      playAllAudio(){ 
        if(this.mediaAudioNum>0){
          this.$refs.mediaAudioPlayer.forEach( player=>{player.play()} ) 
        }
      },
      finishAllAudio(){
        if(this.mediaAudioNum>0){
          this.$refs.mediaAudioPlayer.forEach( player=>{player.finish()} ) 
        }
      },
      dispSterePhonicArrangeField(){
        // this.isShowSterePhonicArrangeField = !this.isShowSterePhonicArrangeField;
        const event = new CustomEvent('changeDispStereoPhonicArrangeField');
        document.body.dispatchEvent(event);
      },
      setMediaAudioDuration(index, duration){
        this.updateMediaAudiosObjectItem({index:index, key:'duration', value:duration});
      },
      searchLongestDuration(){
        let longestDuration = 0;
        this.getMediaAudios.forEach(mediaAudio=>{
          if(longestDuration <= mediaAudio['duration']){
            longestDuration = mediaAudio['duration'];
          }
        })
        return longestDuration;
      },
      updateLongestDuration(duration){
        this.longestAudioDuration = duration;
        this.updateMediaSettingObjectItem({key:'finish_time', value:duration});
      },
      // ※オーディオが追加された「後」に必要な処理をまとめた関数
      taskAfterAudioAdded(index){
        const duration = this.getMediaAudios[index]['duration'];
        if(duration >= this.longestAudioDuration){
          this.updateLongestDuration(duration);
        }
      },
      // ※オーディオ削除含め、削除時に必要な処理をまとめた関数。(↑のtask～addedと違い、delete処理も含まれる)
      taskWhenAudioDelete(index){
        const duration = this.getMediaAudios[index]['duration']; // ！オーディオ削除前に再生時間を取得しておく
        this.deleteAudio(index);
        if(duration >= this.longestAudioDuration){
          const newLongestDuration = this.searchLongestDuration();
          this.updateLongestDuration(newLongestDuration);
        }
      },
      deleteAudio(index){
        this.$refs.mediaAudioPlayer[index].pause();
        this.deleteMediaAudiosObjectItem(index);
      },
      updateLoopSetting(index){
        const newLoopSetting = !(this.getMediaAudios[index]['isLoop']); // =現在のループ設定の逆
        this.updateMediaAudiosObjectItem({index:index, key:'isLoop', value:newLoopSetting});
      },
      switchPanning(index){
        const newPanningSetting = !(this.getMediaAudios[index]['panningFlag']); // =現在のpanning設定の逆
        this.updateMediaAudiosObjectItem({index:index, key:'panningFlag', value:newPanningSetting});
      },
      updateAudioVol(index,event){
        const audioVolume = event.target.value;
        this.$refs.mediaAudioPlayer[index].updateVolume(audioVolume);
        this.updateMediaAudiosObjectItem({index:index, key:'volume', value:audioVolume});
      }

    },
    watch : { 
      mediaAudioNum : function(audioNum){
        if(audioNum > this.maxAudioNum){this.deleteAudio(0)}
      },
    },
    created(){
      document.body.addEventListener('changeDispAudioState', (e)=>{
        this.isShowAudio = e.detail;
      })
    },

  }

</script>



<style scoped>

  .disp-front {
    z-index: 11;
  }

  /* 全オーディオの再生停止コントローラー */
  .all-audio-controll-wrapper {
    padding-bottom: 5px;
    padding: 2px 0;
    width: 100%;
    /* height: 60px; */
    background-color: black;
    border-bottom-left-radius: 5px;

    display: flex;
    justify-content: center;
  }

  .size-Adjust-box {
    opacity: 0.85;
    height: 33px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .size-Adjust-box:hover{
    opacity: 1;
  }

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

  #play-all-icon {
    color: green;
  }
  #play-all-icon:hover {
    cursor: pointer;
  }
  #finish-all-icon {
    color: lightgrey;
  }
  #finish-all-icon:hover {
    cursor: pointer;
  }
  #disp-panning-setting-icon {
    color:rgba(173,255,47,0.8);
  }
  #disp-panning-setting-icon {
    cursor: pointer;
  }
  

  /* audio */
  #media-audio-wrapper {
    position: absolute;
    background-color: rgba(0,0,0,0.8);

    width: 240px;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
    display: flex;
    flex-direction: column;
  }

  .audio-num-wrapper{
    z-index: 1;
    position: absolute;
    top: 5px;
    left: 2px;
    width: 20px;
    height: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .media-audio-num{
    font-size: 16px;
    margin: -5px 0 0 5px;
    color: gold;
    opacity: 0.7;
  }

  #media-audio-frame {
    height: 100%;
    display: flex;
    border-top-left-radius: 5px;
    flex-direction: column;
    justify-content: space-around;
  }
  .is-show {
    background-color: rgba(0,0,0,0.8);
    z-index: 15;
  }

  .media-audio-controller-zone{
    padding-left: 15px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    overflow-y: scroll;
  }

  #audios{
    height: 100%;
    width: 100%;
    margin: 0;
    padding: 10px;

    display: flex;
    justify-content: space-around;
    align-items: flex-start;
  }

  .audio-area {
    width: 95%;
    position: relative;
    display: flex;
    align-items: center;
    margin: 15px 0 15px 10px;
    min-width: 100px;
  }

  .audio-wrapper {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    position: relative;
    opacity: 0.7;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  .non-audio-frame {
  }

  .dummy-audio-icon{
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: 1.5px dotted lightgrey;
    opacity: 0.7;
  }

  .setting-icon {
    color: grey;
    margin: 7px;
    font-size: 17px;
  }

  .media-audio-vol-icon {
    margin-right: 3px;
    color:  rgba(255,255,255,0.8);
  }

  /* hover設定(wrapper) */
  .audio-area:hover {
    opacity: 1;
  }

  .media-audio-name-wrapper {
    width: 90%;
    margin-left: 5px;
    color : white;
    white-space: nowrap;
    text-overflow: clip;
    overflow: hidden;
  }

  .media-audio-name {
    font-size: 0.7rem;
  }

  .setting-disp-wrapper {
    z-index: 2;
    position: absolute;
    right: 0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
  }

  .setting-wrapper {
    position: absolute;
    right: 30px;
    z-index: 3;
  }

  .setting-disp-trigger{
    color: rgb(100,100,100);
    margin-left: 5px;
  }

  .audio-settings {
    display: none;
  }
  .panning-settings {
    display: none;
    justify-content: space-around;
  }

  .panning-label {
    color: grey;
    font-size: 12px;
  }

  .setting-cover {
    align-items: center;
    width: 180px;
    padding: 2px 0 2px 2px;
    border-radius: 5px;
    background-color: rgba(60,70,100,0.9);
  }

  .audio-setting {
    /* margin: 0 5px; */
  }

  .del-and-loop-wrapper{
    display: flex;
    align-items: center;
  }


  .audio-vol-wrapper {
    display: flex;
    align-items: center;
  }
  .audio-vol-wrapper:hover +.media-audio-name-wrapper {
    display: none;
  }


  .vol-bar-wrapper {
    display: flex;
    align-items: center;
  }

  /* hover設定(各アイコン) */
  .media-audio-delete-icon:hover {
    color:  rgba(255,10,10,1);
  }

  .loop-icon {
    position: absolute;
    top: -4px;
    left: -4px;
  }

  .loop-setting-icon:hover {
    color:  rgba(10,10,255,1);
  }

  .audio-vol-range {
    -webkit-appearance: none;
    appearance: none;
    cursor: pointer;
    height: 2px;
    width: 100%;
  }


  .change-disp-audio {
    color: lightgrey;
    margin: 0 10px 10px 0;
    padding: 10px 19px 10px 15px;
    border-radius: 50%;
    background-color: rgba(0,0,0, 0.5);
  }
  .change-disp-audio:hover {
    background-color: rgba(0,110,110, 0.5);
    cursor: pointer;
  }


  /* 再生関連 */
  .isLoop {
    color:  rgba(0,0,255,1);
    display: inline-block;
    z-index: 2;
  }

  .panning-on {
    color: greenyellow;
    z-index : 2;
  }

  .audio-index{
    position: absolute;
    color: rgba(255,255,255,0.8);
  }

  .white { color: white;}
  .blue { color: blue;}

/* スマホ以外 */
@media screen and (min-width:481px) {
  #media-audio-wrapper{
    top: 70px;
    bottom: 80px;
    right: 0;
  }

  #audios{
    flex-flow: column;
  }

  .audio-area {
    justify-content: flex-start;
  }

  .audio-vol-wrapper {
    right: 0;
    margin: 0 5px;
  }

}

/* スマホ */
@media screen and (max-width:480px) {
  #media-audio-wrapper{
    bottom: 0;
    width: 100%
  }

  #audios{
    height: 110px;
    justify-content: flex-start;
    overflow-x:scroll;
    padding: 10px 25px 5px 25px;
  }

  .setting-wrapper {
    right: 10px;
    top: -20px;
    flex-direction: column;
    align-items: flex-end;
  }

  .setting-disp-wrapper{
    right: -5px;
    top: -10px;
  }

  .setting-cover {
    flex-direction: column;
    justify-content: space-between;
    width: 75px;
    height: 95px;
    padding: 3px;
    margin-right: 10px;
  }

  .del-and-loop-wrapper{
    width: 100%;
    padding: 0 0 10px 0;
    justify-content: space-between;
  }
  

  .media-audio-name-wrapper {
    width: 70px;
    text-align: center;
  }

  .audio-area {
    flex-direction: column;
    margin: 15px 0 0 10px;
  }

  .all-audio-controll-wrapper {
    overflow-x: scroll;
  }

  .change-disp-audio {
    padding: 10px 12px 10px 8px;
  }

  .media-audio-num {
    font-size: 13px;    
    color: lightgray;
  }

  .loop-icon {
    left: 22px;
  }

}

</style>