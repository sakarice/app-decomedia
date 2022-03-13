<template>
  <!-- Media図形-->
  <div id="media-figure-factory-wrapper" @click.stop @touchstart.stop>
    <div class="item-frame flex column a-center">

      <!-- クローズアイコン -->
      <div class="close-icon-wrapper" :class="{'hidden':isMobile}" @mousedown.stop>
        <i class="fas fa-times fa-2x close-icon" @click="closeModal()"></i>
      </div>

      <p class="font-11 mt5 mb5">図形</p>

      <!-- 図形プレビュー -->
      <div class="figure-preview-wrapper">
        <!-- 図形種類変更(一つ前) -->
        <div class="change-figure-type back-figure-type"
        @mousedown="backFigureType()" @touchend="backFigureType()">
          <i class="fas fa-angle-double-left fa-2x"></i>
        </div>

        <!-- 図形プレビュー -->
        <div class="canvas-wrapper pos-r flex column j-center a-center mr15 ml15" @mousedown.stop @click="addMediaFigure()">
          <!-- プレビュー用キャンバス -->
          <canvas id="pre-canvas"></canvas>
          <!-- 追加アイコン -->
          <div class="plus-icon-wrapper mt10 column j-center a-center w100 h100 for-pc-tablet">
            <i class="fas fa-plus fa-3x plus-icon"></i>
            <span class="add-text font-11">追加</span>
          </div>
          <!-- (モバイル用)補足文 -->
          <p class="m0 mt5 font-11 grey for-mobile">タッチで追加</p>
        </div>

        <!-- 図形種類変更(一つ後) -->
        <div class="change-figure-type next-figure-type"
        @mousedown="goNextFigureType()" @touchend="goNextFigureType()">
          <i class="fas fa-angle-double-right fa-2x"></i>
        </div>
      </div>

      <!-- 詳細設定の表示・非表示切り替え -->
      <div class="change-disp-detail flex a-center j-center" @click="isShowDetail=!isShowDetail">
        <span :class="{'reverse-y':isShowDetail}">▼</span>
        <div class="horizontal-bar"></div>
        <span>設定</span>
        <div class="horizontal-bar"></div>
        <span :class="{'reverse-y':isShowDetail}">▼</span>
      </div>

      <!-- 図形設定 -->
      <div class="media-figure-settings" v-show="isShowDetail">

        <!-- 図形の種類 -->
        <div class="setting-row flex j-s-between a-center type-input-wrapper">
          <span class="label">種類</span>
          <select id="create-figure-type" name="種類" class="w80px" @input="updateFigureData({key:'type', value:$event.target.value})">
            <option v-for="figureType in figureTypeList" :key="figureType['code']" :value="figureType['code']">{{figureType['name']}}</option>
          </select>
        </div>

        <!-- カラー系の設定 -->
        <div class="setting-type-color mt10">
          <div class="setting-row flex j-s-between a-center fill-input-wrapper">
            <label class="fill-flag m0 hover-pointer">
              <input type="checkbox" @mousedown.stop :checked="getFigureData['isDrawFill']" @input="updateFigureData({key:'isDrawFill',value:$event.target.checked})">
              <span class="label">塗りつぶし</span>
            </label>
            <div class="fill-color">
              <span class="label grey">色</span>
              <input type="color" @mousedown.stop :value="getFigureData['fillColor']" @input="updateFigureData({key:'fillColor', value:$event.target.value})">
            </div>
          </div>

          <div class="setting-row flex j-s-between a-end stroke-input-wrapper">
            <label class="stroke-flag m0 hover-pointer">
              <input type="checkbox" @mousedown.stop :checked="getFigureData['isDrawStroke']" @input="updateFigureData({key:'isDrawStroke',value:$event.target.checked})">
              <span class="label">枠線</span>
            </label>
            <div class="stroke-color">
              <span class="label grey">色</span>
              <input type="color" @mousedown.stop :value="getFigureData['strokeColor']" @input="updateFigureData({key:'strokeColor', value:$event.target.value})">
            </div>
          </div>
        </div>

        <div class="setting-row opacity-input-wrapper mt25 flex j-s-between a-end">
          <span class="label w-auto">透過度</span>
          <input type="range" class="w100px" :value="getFigureData['globalAlpha']" @mousedown.stop @input="updateFigureData({key:'globalAlpha',value:$event.target.value})" name="opacity" id="" min="0" max="1" step="0.05">
          <input type="number" class="input-num w50px font-12" :value="getFigureData['globalAlpha']" @input="updateFigureData({key:'globalAlpha', value:$event.target.value})" name="opacity" min="0" max="1" step="0.05">
        </div>

        <!-- 数値系の設定 -->
        <div class="setting-type-num">
          <num-setting-template label="位置(横)" :inputValue="getFigureData['left']"
          @push-minus-btn="minusOneValue('left')" @push-plus-btn="plusOneValue('left')" @input-value="updateFigureData('left',$event)">
          </num-setting-template>

          <num-setting-template label="位置(縦)" :inputValue="getFigureData['top']"
          @push-minus-btn="minusOneValue('top')" @push-plus-btn="plusOneValue('top')" @input-value="updateFigureData('top',$event)">
          </num-setting-template>

          <num-setting-template label="回転" :inputValue="getFigureData['degree']"
          @push-minus-btn="minusOneValue('degree')" @push-plus-btn="plusOneValue('degree')" @input-value="updateFigureData('degree',$event)">
          </num-setting-template>

          <num-setting-template label="横幅" :inputValue="getFigureData['width']"
          @push-minus-btn="minusOneValue('width')" @push-plus-btn="plusOneValue('width')" @input-value="updateFigureData('width',$event)">
          </num-setting-template>

          <num-setting-template label="縦幅" :inputValue="getFigureData['height']"
          @push-minus-btn="minusOneValue('height')" @push-plus-btn="plusOneValue('height')" @input-value="updateFigureData('height',$event)">
          </num-setting-template>
        </div>


      </div>
    </div>

    <close-modal-bar class="for-mobile"></close-modal-bar>

  </div>
</template>

<script>
  import {moveStart} from '../../../functions/moveHelper'
  import { mapGetters, mapMutations } from 'vuex';
  import numSettingTemplate from './edit_items/NumSettingTemplateComponent.vue';
  import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'

  export default {
    components : {
      numSettingTemplate,
      closeModalBar,
    },
    data : ()=>{
      return {

        figureTypeList : [
          {code : 1, name : "四角形"},
          {code : 2, name : "丸"},
        ],
        figureType : 1,
        canvas_length : 80,
        pre_canvas : "",
        pre_ctx : "",

        isShowDetail : false,
        // "isMobile" : false,
      }
    },
    computed : {
      ...mapGetters('mediaFigureFactory', ['getFigureData']),
      ...mapGetters('deviceType', ['getDeviceType']),
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
      isMobile(){ return (this.getDeviceType==2) ? true : false; },
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
      isMobile(){
        this.responsiveAction();
      }
    },
    methods : {
      ...mapMutations('mediaFigureFactory', ['updateFigureData']),
      ...mapMutations('mediaFigures', ['addMediaFiguresObjectItem']),
      closeModal(){
        this.$emit('close-modal');
      },
      responsiveAction(){
        if(this.isMobile){ // モバイルの時
          this.deleteMoveEvent();
          this.setModalAtMobilePosition();
        } else {
          this.registMoveEvent();
        }
      },
      setModalAtMobilePosition(){
        const modal = document.getElementById('media-figure-factory-wrapper');
        modal.style.left = "";
        modal.style.top = ""; // topの指定を消す
      },
      registMoveEvent(){
        const target = document.getElementById('media-figure-factory-wrapper');
        target.addEventListener('mousedown', this.moveStart, false);
        target.addEventListener('touchstart', this.moveStart, false);
      },
      deleteMoveEvent(){
        const target = document.getElementById('media-figure-factory-wrapper');
        target.removeEventListener('mousedown', this.moveStart, false);
        target.removeEventListener('touchstart', this.moveStart, false);
      },
      goNextFigureType(){
        const type_new = this.figureType % 2 + 1;
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
      plusOneValue(data_key){
        const new_val = Number(this.getFigureData[data_key]) + 1;
        this.updateFigureData({key:data_key, value:new_val});
      },
      minusOneValue(data_key){
        const new_val = Number(this.getFigureData[data_key]) - 1;
        this.updateFigureData({key:data_key, value:new_val});
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
        const modal = document.getElementById('media-figure-factory-wrapper');
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
        const move_target_dom = document.getElementById("media-figure-factory-wrapper");
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
    },
    mounted(){
      this.setModalCenter();
      this.setContext();
      this.setCanvasSize();
      this.draw();
      this.responsiveAction();
    },
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";


#media-figure-factory-wrapper{
  position: absolute;
  z-index: 30;
  color: white;
}
#media-figure-factory-wrapper:hover{
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
.change-figure-type:hover {
  cursor:pointer;
}

.back-figure-type{
  color: deepskyblue;
}
.next-figure-type{
  color: palevioletred;
}

.item-frame {
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
  padding: 15px 25px;
  max-height: 200px;
  min-width: 300px;
  overflow-y: scroll;
}

.figure-preview-wrapper{
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 5px 0px;
}

#pre-canvas {
  background-color: white;
  /* border-radius: 50%; */
  padding: 4px;
  box-shadow: 1px 1px 2px 1px grey;
}

.canvas-wrapper:hover {
  cursor: pointer;
}
.canvas-wrapper:hover #pre-canvas{
  opacity: 0.3;
}

.canvas-wrapper:hover .plus-icon-wrapper{
  display: inline-flex;
}

.plus-icon-wrapper {
  display: none;
  position:absolute;
}
.plus-icon {
  color: orange;
}


.close-icon-wrapper {
  display: inline-block;
  position: absolute;
  top: 0px;
  right: 0px;
  z-index: 3;
  padding: 5px;
  color:black;
}
.close-icon-wrapper:hover {
  cursor: pointer;
  background-color: grey;
}

.setting-row {
  border-bottom: 0.5px solid rgba(200,200,200,0.2);
  padding-bottom: 3px;
  margin-bottom: 17px;
}

.setting-type-num,
.setting-type-color {
  margin-bottom: 15px;
}

.input-num {
  width: 60px;
  color: darkgray;
}

.btns {
  border-radius: 50%;
  padding: 5px 4px;
}
.btns:hover { cursor: pointer;}
.plus-btn {
  color: palevioletred;
  border: 1.5px solid palevioletred;
}
.minus-btn {
  color: deepskyblue;
  border: 1.5px solid deepskyblue;
}

.label {
  width: 50px;
  color: lightgrey;
  font-size: 13px;
}

.hover-pointer:hover { cursor:pointer; }

.reverse-y {
  transform: scaleY(-1);
}

.hidden {
  display: none;
}

.grey { color: grey;}
.darkgrey { color: darkgrey;}



@media screen and (min-width:481px) {
  #media-figure-factory-wrapper{
    left: 100px;
    top: 100px;
    width: 300px;
    background-color: rgba(35,40,50,0.85);
    padding: 5px;
    border-radius: 6px;
  }
  
}

@media screen and (max-width:480px) {
  #media-figure-factory-wrapper{
    z-index: 2;
    bottom: 50px;  
    max-height: 60vh;
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .media-figure-settings {
    max-height: 32vh;
  }

  .item-frame {
    width:92%;
    background-color: rgba(35,40,50,0.85);
    border-radius: 5px;
    /* border-top-right-radius: 5px;
    border-top-left-radius: 5px; */
  }

  .plus-icon-wrapper {
    margin-top: 0;
  }

  .label {
    font-size: 11px;
  }

  .for-pc-tablet{
    display: none;
  }
  
}



</style>