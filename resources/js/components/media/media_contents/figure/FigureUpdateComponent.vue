<template>
  <!-- Media図形-->
  <div id="media-figure-update-wrapper"
   @mousedown="mouseDown($event)" @touchstart="mouseDown($event)">
    <div class="item-frame">
      <!-- クローズアイコン -->
      <div class="close-icon-wrapper" @mousedown.stop>
        <i class="fas fa-times fa-3x close-icon" @click="closeEditor"></i>
      </div>

      <!-- 図形設定 -->
      <div class="media-figure-settings">
        <!-- 数値系の設定 -->
        <div class="setting-type-num">
          <div class="disp-space-between x_position-wrapper">
            <span>配置座標(x):</span>
            <input type="number" class="input-num" :value="figureDatas['x_position']" @input="updateFigureData('x_position', $event.target.value)">
          </div>

          <div class="disp-space-between y_position-wrapper">
            <span>配置座標(y):</span>
            <input type="number" class="input-num" :value="figureDatas['y_position']" @input="updateFigureData('y_position', $event.target.value)">
          </div>

          <div class="disp-space-between degree-wrapper">
            <span>回転:</span>
            <input type="number" class="input-num" :value="figureDatas['degree']" @input="updateFigureData('degree', $event.target.value)">
          </div>

          <div class="disp-space-between width-input-wrapper">
            <span>横幅[px]:</span>
            <input type="number" class="input-num" :value="figureDatas['width']" @input="updateFigureData('width', $event.target.value)">
          </div>
          <div class="disp-space-between height-input-wrapper">
            <span>縦幅[px]:</span>
            <input type="number" class="input-num" :value="figureDatas['height']" @input="updateFigureData('height', $event.target.value)">
          </div>
        </div>

        <!-- カラー系の設定 -->
        <div class="setting-type-color">
          <div class="disp-space-between fill-input-wrapper">
            <div class="fill-flag">
              <span>塗りつぶし</span>
              <input type="checkbox" @mousedown.stop :value="figureDatas['isDrawFill']" @input="updateFigureData('isDrawFill',$event.target.checked)">
            </div>
            <div class="fill-color">
              <span>色:</span>
              <input type="color" @mousedown.stop :value="figureDatas['fillColor']" @input="updateFigureData('fillColor', $event.target.value)">
            </div>
          </div>

          <div class="disp-space-between stroke-input-wrapper">
            <div class="stroke-flag">
              <span>枠線</span>
              <input type="checkbox" @mousedown.stop :value="figureDatas['isDrawStroke']" @input="updateFigureData('isDrawStroke',$event.target.checked)">
            </div>
            <div class="stroke-color">
              <span>色:</span>
              <input type="color" @mousedown.stop :value="figureDatas['strokeColor']" @input="updateFigureData('strokeColor', $event.target.value)">
            </div>
          </div>
        </div>

        <div class="opacity-input-wrapper">
          <span>透過度:</span>
          <input type="range" :value="figureDatas['globalAlpha']" @mousedown.stop @input="updateFigureData('globalAlpha',$event.target.value)" name="opacity" id="" min="0" max="1" step="0.05">
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    components : {},
    props:[
      "index",
    ],
    data : ()=>{
      return {
        "move_target" : "",
        "x_in_element" : 0, // クリックカーソルの要素内における相対位置(x座標)
        "y_in_element" : 0, // 〃↑のy座標

        "figureDatas" : {
          "x_position" : 0,
          "y_position" : 0,
          "width" : 0,
          "height" : 0,
          "degree" : 0,
          "globalAlpha" : 0,
          "isDrawFill" : false,
          "isDrawStroke" : false,
          "fillColor" : "",
          "strokeColor" : "",
        },
        // "figureDataKeys" : [
        //   "x_position",
        //   "y_position",
        //   "width",
        //   "height",
        //   "degree",
        //   "isDrawFill",
        //   "isDrawStroke",
        //   "fillColor",
        //   "strokeColor",
        //   "globalAlpha",
        // ],
      }
    },
    computed : {
      ...mapGetters('mediaFigures', ['getMediaFigure']),
    },
    watch : {},
    methods : {
      // ...mapMutations('mediaFigureFactory', ['updateFigureData']),
      ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      ...mapMutations('mediaFigures', ['updateMediaFiguresObjectItem']),
      ...mapMutations('mediaFigures', ['deleteMediaFiguresObjectItem']),
      closeEditor(){ this.$emit('close-editor', this.index); },

      getOneFigure(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaFigure;
      },
      init(index){
        const storeFigureData = this.getOneFigure(index);
          // this.figureDataKeys.forEach(figureDataKey=>{
          //   this.figureDatas[figureDataKey] = this.fixDataType(figureDataKey,storeFigureData[figureDataKey]);
        // })
        console.log("storeFigureData:"+storeFigureData);
        for(let key of Object.keys(storeFigureData)){
          this.figureDatas[key] = this.fixDataType(key, storeFigureData[key]);
        }
      },
      updateFigureData(key, value){
        this.updateMediaFiguresObjectItem({index:this.index, key:key, value:this.fixDataType(key, value)});
        this.figureDatas[key] = this.getOneFigure(this.index)[key];
        this.$emit('re-render', this.index);
      },
      checkTypeNum(key){
        const num_type_keys = ["width","height","degree","x_position","y_position","globalAlpha"];
        return num_type_keys.includes(key);
      },
      fixDataType(key, value){
        let reTypedValue;
        if(this.checkTypeNum(key)){
          reTypedValue = Number(value);
        } else {
          reTypedValue = value;
        }
        return reTypedValue;
      },
      // 設定モーダル操作用
      // モーダルの初期表示位置をウィンドウ中央に持ってくる
      setModalCenter(){
        const modal = document.getElementById('media-figure-update-wrapper');
        const modal_width = Number(this.getStyleSheetValue(modal,"width").replace("px",""));
        const modal_height = Number(this.getStyleSheetValue(modal,"height").replace("px",""));
        modal.style.left = (window.innerWidth/2 - modal_width/2) + "px";
        modal.style.top = (window.innerHeight/2 - modal_height/2) + "px";
      },
      getStyleSheetValue(element,property){ // ↑でcssの値を取得するための関数
        if (!element || !property) {
          return null;
        }
        var style = window.getComputedStyle(element);
        var value = style.getPropertyValue(property);
        return value;
      },
      mouseDown(e){
        let event;
        if(e.type==="mousedown"){
          event = e;
        } else {
          event = e.changedTouches[0];
        }
        this.move_target = document.getElementById('media-figure-update-wrapper');
        // this.move_target = event.target;
        this.x_in_element = event.clientX - this.move_target.offsetLeft;
        this.y_in_element = event.clientY - this.move_target.offsetTop;
        // ムーブイベントにコールバック
        document.body.addEventListener("mousemove", this.mouseMove, false);
        this.move_target.addEventListener("mouseup", this.mouseUp, false);
        document.body.addEventListener("touchmove", this.mouseMove, false);
        this.move_target.addEventListener("touchend", this.mouseUp, false);
      },
      mouseMove(e){
        e.preventDefault();
        this.move_target.style.left = (e.clientX - this.x_in_element) + "px";
        this.move_target.style.top = (e.clientY - this.y_in_element) + "px";

        // マウス、タッチ解除時のイベントを設定
        document.body.addEventListener("mouseleave", this.mouseUp, false);
        document.body.addEventListener("touchleave", this.mouseUp, false);
      },
      mouseUp(e){
        document.body.removeEventListener("mousemove", this.mouseMove, false);
        this.move_target.removeEventListener("mouseup", this.mouseUp, false);
        document.body.removeEventListener("touchmove", this.mouseMove, false);
        this.move_target.removeEventListener("touchend", this.mouseUp, false);
      },
    },
    created(){
      // this.init();
    },
    mounted(){
      this.setModalCenter();
    },
  }

</script>

<style scoped>
#media-figure-update-wrapper{
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 30;
  width: 300px;
  height: 300px;
  padding: 5px;
  background-color: rgba(35,40,50,0.85);
  color: white;
  border-radius: 6px;
  box-shadow: 1px 1px 10px rgba(220,220,220,1);
}
#media-figure-update-wrapper:hover{
  cursor: all-scroll;
}

.item-frame {
  /* background-color: rgba(240,240,250,1); */
}
.item-frame:hover{
  cursor: all-scroll;
}
.media-figure-settings {
  padding: 15px 45px;
}


.close-icon-wrapper {
  display: inline-block;
  position: absolute;
  top: 0px;
  right: 0px;
  z-index: 3;
  padding: 5px;
}
.close-icon:hover {
  cursor: pointer;
}

.setting-type-num,
.setting-type-color {
  margin-bottom: 15px;
}

.disp-space-between {
  display: flex;
  justify-content: space-between;
}

.input-num {
  width: 100px;
}



</style>