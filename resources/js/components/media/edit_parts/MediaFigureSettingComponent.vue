<template>
  <!-- Media図形-->
  <div id="media-figure-setting-wrapper" @mousedown="mouseDown($event)" @touchstart="mouseDown($event)">
    <div class="item-frame" @mousedown.stop @mouseup.stop>
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
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    components : {},
    data : ()=>{
      return {
        "move_target" : "",
        "x_in_element" : 0, // クリックカーソルの要素内における相対位置(x座標)
        "y_in_element" : 0, // 〃↑のy座標
      }
    },
    computed : {
      ...mapGetters('mediaFigure', ['getMediaFigure']),
    },
    methods : {
      ...mapMutations('mediaFigure', ['updateMediaFigureObjectItem']),
      closeModal(){
        this.$emit('close-modal');
      },
      mouseDown(e){
        let event;
        if(e.type==="mousedown"){
          event = e;
        } else {
          event = e.changedTouches[0];
        }
        this.move_target = document.getElementById('media-figure-setting-wrapper');
        this.x_in_element = event.clientX - event.target.offsetLeft;
        this.y_in_element = event.clientY - event.target.offsetTop;
        // ムーブイベントにコールバック
        document.body.addEventListener("mousemove", this.mouseMove, false);
        document.body.addEventListener("touchmove", this.mouseMove, false);
      },
      mouseMove(e){
        e.preventDefault();
        this.move_target.style.left = (e.clientX - this.x_in_element) + "px";
        this.move_target.style.top = (e.clientY - this.y_in_element) + "px";

        // マウス、タッチ解除時のイベントを設定
        this.move_target.addEventListener("mouseup", this.mouseUp, false);
        document.body.addEventListener("mouseleave", this.mouseUp, false);
        this.move_target.addEventListener("touchend", this.mouseUp, false);
        document.body.addEventListener("touchleave", this.mouseUp, false);
      },
      mouseUp(){
        document.body.removeEventListener("mousemove", this.mouseMove, false);
        this.move_target.removeEventListener("mouseup", this.mouseUp, false);
        document.body.removeEventListener("touchmove", this.mouseMove, false);
        this.move_target.removeEventListener("touchend", this.mouseUp, false);
      },
    },
    created(){
    },
    mounted(){},
  }

</script>

<style scoped>
#media-figure-setting-wrapper{
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 30;
  padding: 20px;
  background-color: rgba(245,245,245,1);
  box-shadow: 1px 1px 10px rgba(220,220,220,1);
}
#media-figure-setting-wrapper:hover{
  cursor: all-scroll;
}

.item-frame {
  background-color: rgba(250,250,255,1);
}
.item-frame:hover{
  cursor:auto;
}
.media-figure-settings {
  padding: 25px;
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