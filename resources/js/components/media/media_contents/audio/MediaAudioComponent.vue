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
          <i class="loop-icon fas fa-undo-alt" @click.stop="updateLoopSetting(index)"
          v-show="isEditMode" :class="{'isLoop' : mediaAudio['isLoop']}"></i>

          <!-- オーディオアイコン -->
          <div class="audio-wrapper"
          @click.stop @touchstart.stop>
            <div class="audio-player-wrapper">
              <img class="media-audio-thumbnail" :src="mediaAudio['thumbnail_url']">
            </div>

            <!-- <monaural-audio
            :mediaAudioIndex="index"
            :ref="'monauralAudio'">
            </monaural-audio>

            <stereo-audio
            :mediaAudioIndex="index"
            :ref="'stereoAudio'">
            </stereo-audio> -->

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
                <i class="media-audio-delete-icon setting-icon fas fa-trash" @click="deleteAudio(index)" v-show="isEditMode"></i>
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

    <div v-show="isEditMode" class="all-audio-controll-wrapper">
      <div class="all-audio-controller change-disp-setting-wrapper" @click="hideAudio">
        <!-- 閉じるボタン -->
        <div class="hide-field-icon-wrapper flex a-center p5">
          <i class="fas fa-times fa-2x hide-field-icon p10"></i>
        </div>
      </div>
    </div>


  </div>


  <!-- </transition> -->
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import MediaAudioPlayer from './MediaAudioPlayerComponent.vue';
  import StereoAudio from './StereoAudioComponent.vue';
  import MonauralAudio from './MonauralAudioComponent.vue';

  export default {
    components: {
      MediaAudioPlayer,
      StereoAudio,
      MonauralAudio,
    },
    props : [
      'maxAudioNum',
    ],

    data : () => {
      return {
        isShowAudio : false,
        // isEditMode : false,
        longestAudioDuration : 0,
        isShowAudioSettings : [false,false,false,false,false,],
        isShowPanningSettings : [false,false,false,false,false,],
        isShowSterePhonicArrangeField : false,
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('media', ['getMode']),
      ...mapGetters('mediaAudios', ['getIsInitializedAudios']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      mediaAudioNum : function(){
        return this.getMediaAudios.length;
      },
      isEditMode:function(){
        return (this.getMode==1 || this.getMode==2) ? true : false;
      },
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
      deleteAudio(index){
        this.updateMediaAudiosObjectItem({index:index, key:'isPlay', value:false});
        this.deleteMediaAudiosObjectItem(index);
        const event = new CustomEvent('deleteMediaAudio');
        document.body.dispatchEvent(event);        
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
        this.updateMediaAudiosObjectItem({index:index, key:'volume', value:audioVolume});
      }

    },
    watch : { 
      mediaAudioNum : function(audioNum){
        if(audioNum > this.maxAudioNum){this.deleteAudio(0)}
      },
    },
    created(){
      document.body.addEventListener('changeDispSetting', ()=>{
        this.isShowAudio = !this.isShowAudio;
      })
    },

  }

</script>



<style scoped>

  .disp-front {
    z-index: 12;
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

  .audio-player-wrapper {
    display: flex;
    align-items: center;
    border: 1.5px dotted lightgrey;
    border-radius: 50%;
  }
  .audio-player-wrapper:hover {
    opacity: 1;
  }

  .media-audio-thumbnail {
    width: 40px;
    height: 40px;
    border-radius: 50%;
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
    top: 10px;
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