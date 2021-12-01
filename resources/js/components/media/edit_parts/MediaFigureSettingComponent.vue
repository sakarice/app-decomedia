<template>
  <!-- Media図形-->
  <div id="media-figure-setting-wrapper">
    <!-- クローズアイコン -->
    <div class="close-icon-wrapper">
      <i class="fas fa-times fa-2x close-icon" @click="closeModal()"></i>
    </div>
    <!-- 図形設定 -->
    <div class="media-figure-settings">
      <div class="x_position-wrapper">
        <span>x軸位置:</span>
        <input type="number" :value="getMediaFigure['x_position']" @input="updateMediaFigureObjectItem({key:'x_position', value:$event.target.value})">
      </div>

      <div class="y_position-wrapper">
        <span>y軸位置:</span>
        <input type="number" :value="getMediaFigure['y_position']" @input="updateMediaFigureObjectItem({key:'y_position', value:$event.target.value})">
      </div>

      <div class="degree-wrapper">
        <span>回転:</span>
        <input type="number" :value="getMediaFigure['degree']" @input="updateMediaFigureObjectItem({key:'degree', value:$event.target.value})">
      </div>

      <div class="width-input-wrapper">
        <span>横幅:</span>
        <input type="number" :value="getMediaFigure['width']" @input="updateMediaFigureObjectItem({key:'width', value:$event.target.value})">
      </div>
      <div class="height-input-wrapper">
        <span>縦幅:</span>
        <input type="number" :value="getMediaFigure['height']" @input="updateMediaFigureObjectItem({key:'height', value:$event.target.value})">
      </div>

      <div class="fill-input-wrapper">
        <span>塗りつぶし</span>
        <input type="checkbox" :value="getMediaFigure['isDrawFill']" @input="updateMediaFigureObjectItem({key:'isDrawFill',value:$event.target.checked})">
        <span>色:</span>
        <input type="color" :value="getMediaFigure['fillColor']" @input="updateMediaFigureObjectItem({key:'fillColor', value:$event.target.value})">
      </div>

      <div class="stroke-input-wrapper">
        <span>枠線</span>
        <input type="checkbox" :value="getMediaFigure['isDrawStroke']" @input="updateMediaFigureObjectItem({key:'isDrawStroke',value:$event.target.checked})">
        <span>色:</span>
        <input type="color" :value="getMediaFigure['strokeColor']" @input="updateMediaFigureObjectItem({key:'strokeColor', value:$event.target.value})">
      </div>

      <div class="opacity-input-wrapper">
        <span>透過度:</span>
        <input type="range" :value="getMediaFigure['globalAlpha']" @input="updateMediaFigureObjectItem({key:'globalAlpha',value:$event.target.value})" name="opacity" id="" min="0" max="1" step="0.05">
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    components : {},
    data : ()=>{
      return {}
    },
    computed : {
      ...mapGetters('mediaFigure', ['getMediaFigure']),
    },
    methods : {
      ...mapMutations('mediaFigure', ['updateMediaFigureObjectItem']),
      closeModal(){
        this.$emit('close-modal');
      }
    },
    created(){
    },
    mounted(){
    },
  }

</script>

<style scoped>
#media-figure-setting-wrapper{
  position: absolute;
  bottom: 20%;
  left: 50%;
  z-index: 30;
  background-color: rgba(245,245,245,1);
  box-shadow: 1px 1px 10px rgba(220,220,220,1);
}
.media-figure-settings {
  padding: 30px;
}

.close-icon-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 10px;
  padding: 10px;
}
.close-icon:hover {
  cursor: pointer;
}



</style>