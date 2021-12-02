<template>
  <!-- Media図形-->
  <div id="media-figure-setting-wrapper" @mousedown="mouseDown($event)" @touchstart="mouseDown($event)">
    <div class="item-frame">
      <!-- クローズアイコン -->
      <div class="close-icon-wrapper" @mousedown.stop>
        <i class="fas fa-times fa-3x close-icon" @click="closeModal()"></i>
      </div>
      <!-- 図形追加アイコン -->
      <div class="add-icon-wrapper" @mousedown.stop @click="addMediaFigure()">
        <i class="fas fa-plus fa-lg add-icon"></i>
        <span class="add-text">追加</span>
      </div>

      <!-- 図形プレビュー -->
      <div class="figure-preview-wrapper">
        <canvas id="pre-canvas" @mousedown.stop @click="addMediaFigure()"></canvas>
      </div>
      <!-- 図形設定 -->
      <div class="media-figure-settings">
        <!-- 数値系の設定 -->
        <div class="setting-type-num">
          <div class="disp-space-between x_position-wrapper">
            <span>配置座標(x):</span>
            <input type="number" class="input-num" :value="getFigureData['x_position']" @input="updateFigureData({key:'x_position', value:$event.target.value})">
          </div>

          <div class="disp-space-between y_position-wrapper">
            <span>配置座標(y):</span>
            <input type="number" class="input-num" :value="getFigureData['y_position']" @input="updateFigureData({key:'y_position', value:$event.target.value})">
          </div>

          <div class="disp-space-between degree-wrapper">
            <span>回転:</span>
            <input type="number" class="input-num" :value="getFigureData['degree']" @input="updateFigureData({key:'degree', value:$event.target.value})">
          </div>

          <div class="disp-space-between width-input-wrapper">
            <span>横幅[px]:</span>
            <input type="number" class="input-num" :value="getFigureData['width']" @input="updateFigureData({key:'width', value:$event.target.value})">
          </div>
          <div class="disp-space-between height-input-wrapper">
            <span>縦幅[px]:</span>
            <input type="number" class="input-num" :value="getFigureData['height']" @input="updateFigureData({key:'height', value:$event.target.value})">
          </div>
        </div>

        <!-- カラー系の設定 -->
        <div class="setting-type-color">
          <div class="disp-space-between fill-input-wrapper">
            <div class="fill-flag">
              <span>塗りつぶし</span>
              <input type="checkbox" @mousedown.stop :value="getFigureData['isDrawFill']" @input="updateFigureData({key:'isDrawFill',value:$event.target.checked})">
            </div>
            <div class="fill-color">
              <span>色:</span>
              <input type="color" @mousedown.stop :value="getFigureData['fillColor']" @input="updateFigureData({key:'fillColor', value:$event.target.value})">
            </div>
          </div>

          <div class="disp-space-between stroke-input-wrapper">
            <div class="stroke-flag">
              <span>枠線</span>
              <input type="checkbox" @mousedown.stop :value="getFigureData['isDrawStroke']" @input="updateFigureData({key:'isDrawStroke',value:$event.target.checked})">
            </div>
            <div class="stroke-color">
              <span>色:</span>
              <input type="color" @mousedown.stop :value="getFigureData['strokeColor']" @input="updateFigureData({key:'strokeColor', value:$event.target.value})">
            </div>
          </div>
        </div>

        <div class="opacity-input-wrapper">
          <span>透過度:</span>
          <input type="range" :value="getFigureData['globalAlpha']" @mousedown.stop @input="updateFigureData({key:'globalAlpha',value:$event.target.value})" name="opacity" id="" min="0" max="1" step="0.05">
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
        "canvas_length" : 80,
        "pre_canvas" : "",
        "pre_ctx" : "",
      }
    },
    computed : {
      ...mapGetters('mediaFigureFactory', ['getFigureData']),
      degree(){ return this.getFigureData['degree'] },
      is_draw_fill(){ return this.getFigureData['isDrawFill']},
      is_draw_stroke(){ return this.getFigureData['isDrawStroke']},
      fill_color(){ return this.getFigureData['fillColor']},
      stroke_color(){ return this.getFigureData['strokeColor']},
      global_alpha(){ return this.getFigureData['globalAlpha']},
      longerEdge(){ return this.canvas_length * 3/5 },
      width(){
        const actual_width = Number(this.getFigureData['width']);
        const actual_height = Number(this.getFigureData['height']);
        if(actual_width > actual_height){
          return this.longerEdge;
        } else {
          const ratio = actual_width / actual_height; // 縦横の比率
          return this.longerEdge * ratio;
        }
      },
      height(){
        const actual_width = Number(this.getFigureData['width']);
        const actual_height = Number(this.getFigureData['height']);
        if(actual_height > actual_width){
          return this.longerEdge;
        } else {
          const ratio = actual_height / actual_width; // 縦横の比率
          return this.longerEdge * ratio;
        }
      },
      start_x(){ return (this.canvas_length - this.width) / 2;},
      start_y(){ return (this.canvas_length - this.height) / 2;},

    },
    watch : {
      degree(){ this.draw(); },
      is_draw_fill(){ this.draw(); },
      is_draw_stroke(){ this.draw(); },
      fill_color(){ this.draw(); },
      stroke_color(){ this.draw() },
      width(){ this.draw() },
      height(){ this.draw() },
      global_alpha(){ 
        this.setGlobalAlpha();
        this.draw() 
      },
    },
    methods : {
      ...mapMutations('mediaFigureFactory', ['updateFigureData']),
      ...mapMutations('mediaFigures', ['addMediaFiguresObjectItem']),
      closeModal(){
        this.$emit('close-modal');
      },
      addMediaFigure(){
        console.log('callled addMediaFigure');
        // ↓オブジェクトをそのまま渡すと参照渡しになってしまうので、切りだして新しいオブジェクトを作る。
        const mediaFigure = Object.assign({}, this.getFigureData);
        this.addMediaFiguresObjectItem(mediaFigure);
      },
      // 設定モーダル操作用
      // モーダルの初期表示位置をウィンドウ中央に持ってくる
      setModalCenter(){
        const modal = document.getElementById('media-figure-setting-wrapper');
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
        console.log(e);
        this.move_target = document.getElementById('media-figure-setting-wrapper');
        // this.move_target = event.target;
        this.x_in_element = event.clientX - this.move_target.offsetLeft;
        this.y_in_element = event.clientY - this.move_target.offsetTop;
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

      // 図形プレビュー用
      setContext(){
        this.pre_canvas = document.getElementById('pre-canvas');
        this.pre_ctx = this.pre_canvas.getContext('2d');
      },
      setCanvasSize(){
        this.pre_canvas.width = this.canvas_length;
        this.pre_canvas.height = this.canvas_length;
      },
      draw(){
        let timer = "";
        let tmpThis = this;
        if(timer){
          clearTimeout(timer);
        }
        timer = setTimeout(function(){
          tmpThis.clear();
          if(tmpThis.getFigureData['isDrawFill']){ tmpThis.fill(); };
          if(tmpThis.getFigureData['isDrawStroke']){ tmpThis.stroke(); };
        },200);
      },
      clear(){
        this.pre_ctx.clearRect(0,0,this.pre_canvas.width, this.pre_canvas.height);
      },
      setDegree(){ 
        // 描画予定の図形の中心にcontextの回転軸を持ってきて回転する。
        let move_x = this.start_x + this.width/2;
        let move_y = this.start_y + this.height/2;
        this.pre_ctx.translate(move_x, move_y);
        this.pre_ctx.rotate(this.getFigureData['degree']*Math.PI / 180);
        this.pre_ctx.translate(-move_x, -move_y); // 回転軸を元の位置に戻す。
      },
      createPathRect(){
        const point1_left_upper  = {x: this.start_x, y: this.start_y};
        const point2_left_under  = {x: this.start_x ,y: this.start_y + this.height};
        const point3_right_under = {x: this.start_x + this.width, y: this.start_y + this.height};
        const point4_right_upper = {x: this.start_x + this.width, y: this.start_y};

        this.pre_ctx.beginPath();
        this.pre_ctx.moveTo(point1_left_upper['x'],  point1_left_upper['y']);
        this.pre_ctx.lineTo(point2_left_under['x'], point2_left_under['y']);
        this.pre_ctx.lineTo(point3_right_under['x'], point3_right_under['y']);
        this.pre_ctx.lineTo(point4_right_upper['x'], point4_right_upper['y']);
        this.pre_ctx.closePath();
      },
      setGlobalAlpha(){ this.pre_ctx.globalAlpha = this.getFigureData['globalAlpha']},
      setFillColor(){this.pre_ctx.fillStyle = this.getFigureData['fillColor'];},
      setStrokeColor(){this.pre_ctx.strokeStyle = this.getFigureData['strokeColor'];},
      prepareDraw(){
        this.pre_ctx.save();
        this.setGlobalAlpha();
        this.setStrokeColor();
        this.setFillColor();
        this.setDegree();
        this.createPathRect();
      },
      fill(){
        this.prepareDraw();
        this.pre_ctx.fill();
        this.pre_ctx.restore();
      },
      stroke(){
        this.prepareDraw();
        this.pre_ctx.stroke();
        this.pre_ctx.restore();
      },
    },
    created(){
    },
    mounted(){
      this.setModalCenter();
      this.setContext();
      this.setCanvasSize();
      this.draw();
    },
  }

</script>

<style scoped>
#media-figure-setting-wrapper{
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 30;
  width: 350px;
  height: 425px;
  padding: 20px;
  background-color: slategrey;
  border-radius: 3px;
  box-shadow: 1px 1px 10px rgba(220,220,220,1);
}
#media-figure-setting-wrapper:hover{
  cursor: all-scroll;
}

.item-frame {
  background-color: rgba(240,240,250,1);
}
.item-frame:hover{
  cursor: all-scroll;
}
.media-figure-settings {
  padding: 15px 45px;
}

.figure-preview-wrapper{
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 5px 0px;
  margin-bottom: 5px;
}

#pre-canvas {
  background-color: white;
  border-radius: 50%;
  padding: 4px;
}
#pre-canvas:hover{
  cursor: pointer;
  outline: 1px solid orange;
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

.add-icon-wrapper {
  display: inline-block;
  position: absolute;
  top: 100px;
  left: 190px;
  z-index: 3;
  padding: 0px 4px;
  color: darkorange;
  /* background-color: rgba(255,255,255,0.1);
  box-shadow: 1px 1px 3px lightslategrey;
  border-radius: 4px; */
}
.add-icon-wrapper:hover {
  cursor: pointer;
  /* outline: 1px solid orange; */
}
.add-text {
  font-size: 11px;
  margin-left: 2px;
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