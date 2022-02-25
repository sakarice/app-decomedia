<template>

  <!-- アイコン配置による定位設定エリア -->
  <div id="arrange-field-area" class="w100 pos-r flex a-center j-center">

    <!-- ゴミ箱アイコン -->
    <i id="trash-icon" class="fas fa-trash pos-a hover-p" :class="{'red':isRegistDelEvent}" @click="getTrashIconRect"></i>

    <div class="arrange-field pos-r border-r-50per flex a-center j-center"
    :style="arrangeAreaSize">
      <div class="arrange-object-wrapper pos-r w100 h100 border-r-50per">
        <!-- 中心点 -->
        <img src="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/%E3%83%98%E3%83%83%E3%83%89%E3%83%95%E3%82%A9%E3%83%B3%E3%82%A2%E3%82%A4%E3%82%B3%E3%83%B35.svg" alt=""
        class="center-point pos-a w30px h30px border-r-50per">

        <!-- オーディオのアイコン -->
        <audio-object v-for="(audio, index) in getMediaAudios" :key="index"
        :index="index" :center_x="center_x" :center_y="center_y" :radius="radius"
        @moveObject="judgeObjDelete" :class="{'red':(isRegistDelEvent&&(index==deleteAudioIndex))}">
        </audio-object>
      </div>
    </div>
  </div>

</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  import audioObject from './audio/AudioObjectComponent.vue';
  import {setBaseObjInfo, judgeCollide} from '../../../functions/collisionDetectHelper'

  export default {
    components: {
      audioObject,
    },
    props : [
    ],

    data : () => {
      return {
        center_x : 0,
        center_y : 0,
        isRegistDelEvent : false,
        deleteAudioIndex : -1,
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
      ...mapMutations('mediaAudios', ['deleteMediaAudiosObjectItem']),

      getCenterPosition(){
        this.center_x = window.innerWidth/2;
        this.center_y = window.innerHeight/2 + 70;
      },
      setTrashIconPosition(){
        const rect = this.getTrashIconRect();
        const radius = rect.width / 2;
        const center_x = Math.floor(rect.left) + radius;
        const center_y = Math.floor(rect.top) + radius;
        setBaseObjInfo(center_x, center_y, radius);
        const target = document.getElementById('arrange-field-area');
        target.removeEventListener('click', this.setTrashIconPosition, false);
      },
      getTrashIconRect(){
        const trashIcon = document.getElementById('trash-icon');
        const rect = trashIcon.getBoundingClientRect();
        return rect;
      },
      judgeObjDelete(index, center_x, center_y, radius){
        const obj_center = {"x":center_x, "y":center_y};
        const isCollide = judgeCollide(obj_center, radius);
        if(isCollide && !this.isRegistDelEvent){
          this.deleteAudioIndex = index;
          this.registDelAudioEvent();
        } else if(!isCollide){
          this.removeDelAudioEvent();
        }
      },
      registDelAudioEvent(){
        document.body.addEventListener('mouseup', this.deleteAudio, false);
        this.isRegistDelEvent = true;
      },
      removeDelAudioEvent(){
        document.body.removeEventListener('mouseup', this.deleteAudio, false);
        this.isRegistDelEvent = false;
        this.deleteAudioIndex = -1;
      },
      deleteAudio(){
        this.deleteMediaAudiosObjectItem(this.deleteAudioIndex);
        this.removeDelAudioEvent();
        const event = new CustomEvent('deleteMediaAudio');
        document.body.dispatchEvent(event);
      },


    },
    watch : { 
    },
    created(){
    },
    mounted(){
      this.getCenterPosition();
      const target = document.getElementById('arrange-field-area');
      target.addEventListener('mouseover', this.setTrashIconPosition, false);
    }

  }

</script>

<style scoped>
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";

#arrange-field-area {
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

#trash-icon {
  top: 30px;
  right: 30px;
  width: 40px;
  height: 40px;
  padding: 7px;
  font-size: 25px;
  text-align: center;
  color: slategrey;
  background-color: rgba(255,255,255,0.1);
  border-radius: 50%
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

.red { outline : 1px solid red;}


</style>