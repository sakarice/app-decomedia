<template>
  <!-- Media図形-->
  <div id="media-figure-setting-wrapper">
      <!-- モーダル移動用の領域。モバイルでは非表示にすること -->
      <!-- <div class="area-for-move for-pc-tablet"
      @mousedown="moveStart($event)" @touchstart="moveStart($event)">
      </div> -->
    <div class="item-frame">

      <!-- クローズアイコン -->
      <div class="close-icon-wrapper" :class="{'hidden':isMobile}" @mousedown.stop>
        <i class="fas fa-times fa-3x close-icon" @click="closeModal()"></i>
      </div>
      <!-- 図形追加アイコン -->
      <div class="add-icon-wrapper" @mousedown.stop @click="addMediaFigure()">
        <i class="fas fa-plus fa-2x add-icon"></i>
        <span class="add-text">追加</span>
      </div>

      <!-- 図形プレビュー -->
      <div class="figure-preview-wrapper">
        <div class="change-figure-type back-figure-type"
        @mousedown="backFigureType()" @touchend="backFigureType()">
          <i class="fas fa-angle-double-left fa-2x"></i>
        </div>
        <canvas id="pre-canvas" @mousedown.stop @click="addMediaFigure()"></canvas>
        <div class="change-figure-type next-figure-type"
        @mousedown="goNextFigureType()" @touchend="goNextFigureType()">
          <i class="fas fa-angle-double-right fa-2x"></i>
        </div>
      </div>

      <!-- 詳細設定の表示・非表示切り替え -->
      <div class="change-disp-detail flex a-center j-center" @click="isShowDetail=!isShowDetail">
        <span :class="{'reverse-y':isShowDetail}">▼</span>
        <div class="horizontal-bar"></div>
        <span>詳細</span>
        <div class="horizontal-bar"></div>
        <span :class="{'reverse-y':isShowDetail}">▼</span>
      </div>

      <!-- 図形設定 -->
      <div class="media-figure-settings" v-show="isShowDetail">

        <!-- 図形の種類 -->
        <div class="disp-space-between type-input-wrapper">
          <span>種類:</span>
          <select id="create-figure-type" name="種類" class="input-num" @input="updateFigureData({key:'type', value:$event.target.value})">
            <option v-for="figureType in figureTypeList" :key="figureType['code']" :value="figureType['code']">{{figureType['name']}}</option>
          </select>
        </div>

        <!-- 数値系の設定 -->
        <div class="setting-type-num">
          <div class="disp-space-between x-position-wrapper">
            <span>配置座標(x):</span>
            <input type="number" class="input-num" :value="getFigureData['left']" @input="updateFigureData({key:'left', value:$event.target.value})">
          </div>

          <div class="disp-space-between y-position-wrapper">
            <span>配置座標(y):</span>
            <input type="number" class="input-num" :value="getFigureData['top']" @input="updateFigureData({key:'top', value:$event.target.value})">
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
              <input type="checkbox" @mousedown.stop :checked="getFigureData['isDrawFill']" @input="updateFigureData({key:'isDrawFill',value:$event.target.checked})">
            </div>
            <div class="fill-color">
              <span>色:</span>
              <input type="color" @mousedown.stop :value="getFigureData['fillColor']" @input="updateFigureData({key:'fillColor', value:$event.target.value})">
            </div>
          </div>

          <div class="disp-space-between stroke-input-wrapper">
            <div class="stroke-flag">
              <span>枠線</span>
              <input type="checkbox" @mousedown.stop :checked="getFigureData['isDrawStroke']" @input="updateFigureData({key:'isDrawStroke',value:$event.target.checked})">
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

    <close-modal-bar class="for-mobile"></close-modal-bar>

  </div>
</template>

<script>
  import {moveStart} from '../../../functions/moveHelper'
  import { mapGetters, mapMutations } from 'vuex';
  import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'

  export default {
    components : {closeModalBar},
    data : ()=>{
      return {

        "figureTypeList" : [
          {code : 1, name : "四角形"},
          {code : 2, name : "丸"},
        ],
        "figureType" : 1,

        // "move_target" : "",
        // "x_in_element" : 0, // クリックカーソルの要素内における相対位置(x座標)
        // "y_in_element" : 0, // 〃↑のy座標
        "canvas_length" : 80,
        "pre_canvas" : "",
        "pre_ctx" : "",

        "isShowDetail" : false,
        "isMobile" : false,
      }
    },
    computed : {
      ...mapGetters('mediaFigureFactory', ['getFigureData']),
      type(){ return this.getFigureData['type'] },
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
      type(){ this.draw(); },
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
      isMobile(new_val){
        if(new_val==true){
          this.deleteMoveEvent();
          this.setModalAtMobilePosition();
        } else {
          this.registMoveEvent();
        }
      }
    },
    methods : {
      ...mapMutations('mediaFigureFactory', ['updateFigureData']),
      ...mapMutations('mediaFigures', ['addMediaFiguresObjectItem']),
      closeModal(){
        this.$emit('close-modal');
      },
      judgeIsMobile(){
        this.isMobile =  (window.innerWidth < 481 ? true : false);
      },
      setModalAtMobilePosition(){
        const modal = document.getElementById('media-figure-setting-wrapper');
        modal.style.left = "";
        modal.style.top = ""; // topの指定を消す
      },
      registMoveEvent(){
        const target = document.getElementById('media-figure-setting-wrapper');
        target.addEventListener('mousedown', this.moveStart, false);
        target.addEventListener('touchstart', this.moveStart, false);
      },
      deleteMoveEvent(){
        const target = document.getElementById('media-figure-setting-wrapper');
        target.removeEventListener('mousedown', this.moveStart, false);
        target.removeEventListener('touchstart', this.moveStart, false);
      },
      goNextFigureType(e){
        // const type_old = this.getFigureData['type'];
        console.log('goNextFigureType:type='+e.type);
        const type_new = this.figureType % 2 + 1;
        // if(type_old == 2){
        //   type_new = 1;
        // } else {
        //   type_new = type_old + 1;
        // }
        this.updateFigureData({key:'type', value:type_new});
        this.figureType = type_new;
      },
      backFigureType(){
        const type_old = this.getFigureData['type'];
        let type_new;
        if(type_old == 1){
          type_new = 2;
        } else {
          type_new = type_old - 1;
        }
        this.updateFigureData({key:'type', value:type_new});
      },

      addMediaFigure(){
        // ↓オブジェクトをそのまま渡すと参照渡しになってしまうので、切りだして新しいオブジェクトを作る。
        const mediaFigure = Object.assign({}, this.getFigureData);
        this.addMediaFiguresObjectItem(mediaFigure);

        // 図形追加後に次の描画位置をずらす
        this.updateFigureData({key:'left', value:Number(this.getFigureData['left'])+40});
        this.updateFigureData({key:'top', value:Number(this.getFigureData['top'])+40});
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
      // 位置操作用
      moveStart(e){
        const move_target_dom = document.getElementById("media-figure-setting-wrapper");
        moveStart(e, move_target_dom);
        move_target_dom.addEventListener('moveFinish', this.moveEnd, false);
      },
      moveEnd(e){
        e.target.removeEventListener('moveFinish', this.moveEnd, false);
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
      createPath(){
        if(this.getFigureData['type']==1){ // 四角形
          this.createPathRect();
        } else if(this.getFigureData['type']==2){ // 楕円
          this.createPathEllipse();
        }
      },
      createPathRect(){ // 四角形。図形タイプ1
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
      createPathEllipse(){ // 楕円。図形タイプ2
        this.pre_ctx.beginPath();
        const radius_x = this.width/2 - 2; // x軸半径
        const radius_y = this.height/2 - 2; // y軸半径
        const center_x = this.start_x +  radius_x + 1; // 中心x座標
        const center_y = this.start_y +  radius_y + 1; // 中心y座標
        this.pre_ctx.ellipse(center_x, center_y, radius_x, radius_y, 0, 0, Math.PI*2);
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
        this.createPath();
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
      this.judgeIsMobile();
      window.addEventListener('resize',this.judgeIsMobile, false);
    },
    mounted(){
      this.setModalCenter();
      this.setContext();
      this.setCanvasSize();
      this.draw();
      this.registMoveEvent();
    },
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";

#media-figure-setting-wrapper{
  position: absolute;
  z-index: 30;
  color: white;
}
#media-figure-setting-wrapper:hover{
  cursor: all-scroll;
}

.area-for-move {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.change-figure-type {
  margin: 0 10px;
}
.back-figure-type{
  color: blue;
}
.next-figure-type{
  color: red;
}

.item-frame {
  /* background-color: rgba(240,240,250,1); */
}
.item-frame:hover{
  cursor: all-scroll;
}

.change-disp-detail {
  width: 100%;
  margin: 10px 0;
}

.horizontal-bar {
  background-color: rgb(120,120,120);
  width: 33%;
  height: 0.5px;
  margin: 0 5px;
}


.media-figure-settings {
  padding: 15px 45px;
  max-height: 200px;
  overflow-y: scroll;
}

.figure-preview-wrapper{
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 10px 0px;
  margin-bottom: 5px;
}

#pre-canvas {
  background-color: white;
  border-radius: 50%;
  padding: 4px;
}
#pre-canvas:hover{
  cursor: pointer;
  outline: 2px solid orange;
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
  position: absolute;
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

.reverse-y {
  transform: scaleY(-1);
}

.hidden {
  display: none;
}

@media screen and (min-width:481px) {
  #media-figure-setting-wrapper{
    left: 100px;
    top: 100px;
    width: 300px;
    background-color: rgba(35,40,50,0.85);
    padding: 5px;
    border-radius: 6px;
  }
  .add-icon-wrapper {
    display: inline-block;
    position: absolute;
    top: 80px;
    left: 160px;
  }
  
}

@media screen and (max-width:480px) {
  #media-figure-setting-wrapper{
    bottom: 50px;  
    max-height: 50vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .media-figure-settings {
    max-height: 20vh;
  }

  .item-frame {
    width:92%;
    background-color: rgba(35,40,50,0.85);
    border-top-right-radius: 5px;
    border-top-left-radius: 5px;
  }

  .add-icon-wrapper {
    display: flex;
    flex-direction: column;
    top: 5px;
    right: 20px;
  }

  .for-pc-tablet{
    display: none;
  }
  
}



</style>