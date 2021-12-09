<template>
  <!-- <transition name="right-slide"> -->
    <!-- Mediaオーディオ -->
  <div id="media-audio-wrapper" :class="{'disp-front':isShowAudio}">
    <!-- オーディオの表示・非表示切り替えアイコン -->
    <div class="change-disp-audio-wrapper">
      <span v-if="isEditMode" class="media-audio-num">{{mediaAudioNum}}</span>
        <i v-on:click="isShowAudio=!(isShowAudio)" class="fas fa-chevron-left fa-3x change-disp-audio for-pc-tablet" v-bind:class="{'is-reverse': isShowAudio}"></i>
        <i v-on:click="isShowAudio=!(isShowAudio)" class="fas fa-music fa-2x change-disp-audio for-mobile" v-show="!isShowAudio"></i>
        <i v-on:click="isShowAudio=!(isShowAudio)" class="fas fa-times fa-2x change-disp-audio for-mobile" v-show="isShowAudio"></i>
    </div>

      <!-- 選択したオーディオ一覧 -->
    <div id="media-audio-frame" v-show="isShowAudio">
      <!-- オーディオ再生・停止 -->
      <div v-show="isEditMode" class="all-audio-controll-wrapper">
        <div class="all-audio-controller all-audio-play-wrapper">
          <!-- <button id="play-all-button" @click="playAllAudio">全オーディオ再生</button> -->
          <div class="size-Adjust-box">
            <i id="play-all-icon" class="fas fa-caret-right fa-3x" @click="playAllAudio"></i>
          </div>
          <span style="color:grey">play all</span>
        </div>

        <div class="all-audio-controller all-audio-finish-wrapper">
          <div class="size-Adjust-box">
            <!-- <button id="finish-button" @click="finishPlayAudio">オーディオ再生終了</button> -->
            <i id="finish-all-icon" class="fas fa-pause fa-2x" @click="finishAllAudio"></i>
          </div>
          <span style="color:grey">stop all</span>
        </div>
      </div>

      <!-- オーディオのサムネと各種アイコン -->
      <ul id="audios">
        <li class="audio-area" :id="index" v-for="(mediaAudio, index) in getMediaAudios" :key="index">
          <div class="audio-wrapper">
            <media-audio-player-component
            @setMediaAudioDuration="setMediaAudioDuration"
            @taskAfterAudioAdded="taskAfterAudioAdded"
            :mediaAudioIndex="index"
            :ref="'mediaAudioPlayer'">'
            </media-audio-player-component>
            <i class="media-audio-delete-icon fas fa-times fa-2x" @click="taskWhenAudioDelete(index)" v-show="isEditMode"></i>
            <i class="media-audio-loop-icon fas fa-undo-alt fa-2x" @click="updateLoopSetting(index)" v-show="isEditMode" :class="{'isLoop' : mediaAudio['isLoop']}"></i>
          </div>
          <!-- オーディオ名 -->
          <div v-if="mediaAudio" class="media-audio-name-wrapper">
            <span class="media-audio-name">
              {{mediaAudio['name']}}
            </span>
          </div>
          <!-- ボリューム -->
          <div class="audio-vol-wrapper">
            <i class="media-audio-vol-icon fas fa-volume-off fa-2x"></i>
            <div class="vol-bar-wrapper">
              <input type="range" :id="index" class="audio-vol-range" v-on:input="updateAudioVol(index,$event)" min="0" max="1" step="0.01">
            </div>
          </div>

        </li>
        <li class="non-audio-frame" v-for="n in 5-(mediaAudioNum)" :key="5-n">
        </li>
      </ul>
    </div>
  </div>
  <!-- </transition> -->
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import MediaAudioPlayer from './MediaAudioPlayerComponent.vue';

  export default {
    components: {
      MediaAudioPlayer,
    },
    props : [
      'maxAudioNum',
    ],

    data : () => {
      return {
        isShowAudio : false,
        isEditMode : false,
        longestAudioDuration : 0,
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

  }

</script>



<style scoped>

  .disp-front {
    z-index: 50;
  }

  /* 全オーディオの再生停止コントローラー */
  .all-audio-controll-wrapper {
    padding-bottom: 5px;
    margin-bottom: 5px;
    border-bottom: double 2px grey;
    width: 90px;

    display: flex;
    justify-content: space-between;
  }

  .size-Adjust-box {
    opacity: 0.85;
    height: 33px;
    display: flex;
    justify-content: center;
  }
  .size-Adjust-box:hover{
    opacity: 1;
  }

  .all-audio-controller {
    color: ghostwhite;
    height: 50px;
    font-size: 11px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  #play-all-icon {
    color: green;
  }
  #play-all-icon:hover {
    cursor: pointer;
  }
  #finish-all-icon {
    color: lightgrey;
    margin-top: 5px;
  }
  #finish-all-icon:hover {
    cursor: pointer;
  }

  /* audio */
  #media-audio-wrapper {
    position: absolute;
    top: 0;
    right: 0;
    /* z-index : 9; */
    width: 180px;
    height: 100%;
    padding: 5px 0 60px 0;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
  }
  #media-audio-frame {
    background-color: rgba(0,0,0,0.8);
    position: absolute;
    top:70px;
    bottom: 10px;
    right: 0;
    padding: 5px 0 0 10px;
    border-top-left-radius: 10px;
    border-bottom-left-radius: 10px;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    /* overflow-y: scroll; */
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
    width: 180px;
    margin: 0;
    padding-left: 0;

    display: flex;
    flex-flow: column;
    justify-content: space-around;
  }

  .audio-area {
    position: relative;
    display: flex;
    align-items: center;
  }

  .audio-wrapper {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin: 10px 5px;
    position: relative;
    opacity: 0.7;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .non-audio-frame {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 1.5px dotted lightgrey;
    margin: 10px 5px;
    opacity: 0.7;
  }

  .media-audio-delete-icon {
    position: absolute;
    left: -15px;
    top: -15px;
    z-index: -1;
    color:  rgba(220,50,50,0.8);
    display: none;
  }

  .media-audio-loop-icon {
    position: absolute;
    right: -15px;
    top: -15px;
    z-index: -1;
    color:  rgba(20,20,250,0.8);
    display: none;
  }

  .media-audio-vol-icon {
    margin-right: 3px;
    z-index: -1;
    color:  rgba(255,255,255,0.8);
    display: none;
  }

  /* hover設定(wrapper) */
  .audio-area:hover {
    opacity: 1;
  }

  .audio-area:hover
  .media-audio-delete-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .media-audio-loop-icon {
    z-index: 2;
    display: inline-block;
  }

  .audio-area:hover
  .media-audio-vol-icon {
    z-index: 2;
    display: inline-block;
  }

  .media-audio-name {
    color : white;
    font-size: 0.7rem;
    margin-left: 5px;
  }

  .audio-vol-wrapper {
    position: absolute;
    top: 43px;
    left: 40px;
    /* transform: rotate(180deg); */
    display: flex;
    align-items: center;
  }

  .vol-bar-wrapper {
    display: flex;
    align-items: center;
    display: none;


  }

  .audio-vol-wrapper:hover
  .vol-bar-wrapper {
    display: inline-block;
  }


  /* hover設定(各アイコン) */
  .media-audio-delete-icon:hover {
    color:  rgba(255,10,10,1);
  }

  .media-audio-loop-icon:hover {
    color:  rgba(10,10,255,1);
  }

  .audio-vol-range {
    -webkit-appearance: none;
    appearance: none;
    cursor: pointer;
    /* background: #8acdff; */
    height: 2px;
    width: 50px;
    margin-bottom: 12px;
  }

  .change-disp-audio-wrapper {
    position: absolute;
    bottom: 0px;
    width: 100%;
    z-index: 10;
    height: auto;

    display: flex;
    justify-content: flex-end;
    align-items: center;
  }

  .is-reverse{
    transform: scale(-1, 1);
  }

  .media-audio-num {
    z-index: 1;
    background-color: rgba(50, 110, 110, 0.7);
    color: white;
    padding: 0 8px;
    border-radius: 50%;
    margin-right: -20px;
    margin-top: -60px;
    font-size: 16px;
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

@media screen and (max-width:480px) {
  .change-disp-audio {
    padding: 10px 12px 10px 8px;
  }
  .fa-times {
    padding: 10px 15px;
  }
  .media-audio-delete-icon {
    left: -25px;
    top: -25px;
  }
  .media-audio-num {
    padding: 0 6px;
    margin-right: -14px;
    margin-top: -50px;
    font-size: 13px;    
  }  
}


</style>