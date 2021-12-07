<template>
  <!-- Media図形-->
  <div :id="canvas_wrapper_with_index" class="canvas_item-wrapper" @dblclick="showEditor">
    <canvas :id="canvas_with_index" class="canvas_area" :class="{is_active:isActive}"
    @mousedown="moveStart($event)" @touchstart="moveStart($event)" @dblclick="showEditor">
    </canvas>

    <figure-resize :index="index" :class="{hidden:!isActive}" :style="{width:canvas_width, height:canvas_height}"
     v-on:resize="resize"
     v-on:move="moveStart($event)">
    </figure-resize>
    <figure-rotate v-show="isActive" :index="index" v-on:rotate-finish="rotateFinish"></figure-rotate>
    <!-- <i v-show="isActive" class="fas fa-sync fa-lg rotate-icon" @mousedown="rotateStart($event)" @touchstart="rotateStart($event)"></i> -->
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
import figureRotate from './FigureRotateComponent.vue';
import figureResize from './FigureResizeComponent.vue';

  export default {
    components : {
      figureRotate,
      figureResize,
      // figureUpdate,
    },
    props:[
      'index',
    ],
    data : ()=>{
      return {
        "isActive" : false,
        "isReDraw" : false,
        "isResizing" :false,
        "mouse_x" : 0,
        "mouse_y" : 0,
        "canvas" : "",
        "ctx" : "",
        "move_target" : "", // ドラッグ操作で移動させる対象
        "x_in_element" : 0, // 移動対象要素に対するドラッグポイントの相対座標(x)
        "y_in_element" : 0, // 移動対象要素に対するドラッグポイントの相対座標(y)
        "rotate_target" : "", // ドラッグ操作で回転させる対象
        "rotate_center_x" : 0,
        "rotate_center_y" : 0,
        "window_width" : 400,
        "window_height" : 400,
        "x_position" : 0,
        "y_position" : 0, 
        "degree" : 0, 
        "figure_width" : 0, 
        "figure_height" : 0, 
        "is_draw_fill" : false, 
        "is_draw_stroke" : false,
        "fill_color" : "",
        "stroke_color" : "", 
        "global_alpha" : 0, 
      }
    },
    computed : {
      ...mapGetters('mediaFigures', ['getMediaFigure']),
      new_index:function(){ },
      canvas_width:function(){ return this.window_width+"px" },
      canvas_height:function(){ return this.window_height+"px" },
      canvas_with_index:function(){ return 'canvas'+this.index; },
      canvas_wrapper_with_index:function(){ return 'canvas_wrapper'+this.index; },
    },
    watch : {
      isReDraw:function(){ this.init(); }
    },
    methods : {
      ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      ...mapMutations('mediaFigures', ['updateMediaFiguresObjectItem']),
      ...mapMutations('mediaFigures', ['deleteMediaFiguresObjectItem']),
      showEditor(){ this.$emit('show-editor', this.index)},
      getOneFigure(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaFigure;
      },
      // 位置操作用
      moveStart(e){
        let event;
        if(e.type==="mousedown"){
          event = e;
        } else {
          event = e.changedTouches[0];
        }
        this.move_target = document.getElementById(this.canvas_wrapper_with_index);
        this.x_in_element = event.clientX - this.move_target.offsetLeft;
        this.y_in_element = event.clientY - this.move_target.offsetTop;
        // ムーブイベントにコールバック
        document.body.addEventListener("mousemove", this.moving, false);
        this.move_target.addEventListener("mouseup", this.moveEnd, false);
        document.body.addEventListener("touchmove", this.moving, false);
        this.move_target.addEventListener("touchend", this.moveEnd, false);
      },
      moving(e){
        e.preventDefault();
        this.move_target.style.left = (e.clientX - this.x_in_element) + "px";
        this.move_target.style.top = (e.clientY - this.y_in_element) + "px";
        this.x_position = (e.clientX - this.x_in_element);
        this.y_position = (e.clientY - this.y_in_element);

        // console.log("moveEnd:"+(e.clientX - this.x_in_element));
        this.updateMediaFiguresObjectItem({index:this.index,key:"x_position",value:e.clientX - this.x_in_element});
        this.updateMediaFiguresObjectItem({index:this.index,key:"y_position",value:e.clientY - this.y_in_element});

        // マウス、タッチ解除時のイベントを設定
        document.body.addEventListener("mouseleave", this.moveEnd, false);
        document.body.addEventListener("touchleave", this.moveEnd, false);
      },
      moveEnd(e){
        document.body.removeEventListener("mousemove", this.moving, false);
        this.move_target.removeEventListener("mouseup", this.moveEnd, false);
        document.body.removeEventListener("touchmove", this.moving, false);
        this.move_target.removeEventListener("touchend", this.moveEnd, false);
      },
      // 初期描画位置
      setPosition(){
        const move_target = document.getElementById(this.canvas_wrapper_with_index);
        move_target.style.left = this.x_position + 'px';
        move_target.style.top = this.y_position + 'px';
        move_target.style.transform = 'rotate('+ this.degree +'deg)';
      },
      rotateFinish(new_degree){ this.degree = new_degree },
      resize(){
        const figure = this.getOneFigure(this.index);
        this.figure_width = figure['width'];
        this.figure_height = figure['height'];
        this.setCanvasSize();
        this.x_position = figure['x_position'];
        this.y_position = figure['y_position'];
        this.setPosition();
        this.createPathRect();
        this.draw();
      },

      // 図形描画関連
      init(){
        this.setFigureData();
        this.setPosition();
        this.setContext();
        this.setCanvasSize();
        this.setGlobalAlpha();
        this.setStrokeColor();
        this.setFillColor();
        // this.setDegree();
        this.createPathRect();
        this.draw();
        this.isReDraw = false;
      },
      setFigureData(){
        const figureData = this.getOneFigure(this.index);
        if(!(typeof figureData === "undefined")){
          this.x_position = Number(figureData['x_position']);
          this.y_position = Number(figureData['y_position']);
          this.degree = Number(figureData['degree']);
          this.figure_width = Number(figureData['width']);
          this.figure_height = Number(figureData['height']);
          this.is_draw_fill = figureData['isDrawFill'];
          this.is_draw_stroke = figureData['isDrawStroke'];
          this.fill_color = figureData['fillColor'];
          this.stroke_color = figureData['strokeColor'];
          this.global_alpha = figureData['globalAlpha'];
        }
      },
      setContext(){
        this.canvas = document.getElementById(this.canvas_with_index);
        this.ctx = this.canvas.getContext('2d');
      },
      setCanvasSize(){
        this.window_width = this.figure_width;
        this.window_height = this.figure_height;
        this.canvas.width = this.window_width;
        this.canvas.height = this.window_height;
      },
      setDegree(){ 
        // 描画予定の図形の中心にcontextの回転軸を持ってきて回転する。
        let move_x = this.figure_width/2;
        let move_y = this.figure_height/2;
        this.ctx.translate(move_x, move_y);
        this.ctx.rotate(this.degree * Math.PI / 180);
        this.ctx.translate(-move_x, -move_y); // 回転軸を元の位置に戻す。
      },
      draw(){
        this.clear();
        if(this.is_draw_fill){ this.fill(); };
        if(this.is_draw_stroke){ this.stroke(); };
      },
      clear(){
        this.ctx.clearRect(0,0,this.window_width,this.window_height);
      },
      createPathRect(){
        const width = this.figure_width;
        const height = this.figure_height;

        // 左上から半時計回りに、四角形の4頂点のポイントを指定
        const point1_left_upper  = {x: 0,         y: 0};
        const point2_left_under  = {x: 0 ,        y: 0 + height};
        const point3_right_under = {x: 0 + width, y: 0 + height};
        const point4_right_upper = {x: 0 + width, y: 0};

        this.ctx.beginPath();
        this.ctx.moveTo(point1_left_upper['x'],  point1_left_upper['y']);
        this.ctx.lineTo(point2_left_under['x'], point2_left_under['y']);
        this.ctx.lineTo(point3_right_under['x'], point3_right_under['y']);
        this.ctx.lineTo(point4_right_upper['x'], point4_right_upper['y']);
        this.ctx.closePath();
      },
      setGlobalAlpha(){ this.ctx.globalAlpha = this.global_alpha;},
      setStrokeColor(){this.ctx.strokeStyle = this.stroke_color;},
      setFillColor(){this.ctx.fillStyle = this.fill_color;},
      fill(){
        this.ctx.save();
        this.ctx.fill();
        // this.ctx.restore();
      },
      stroke(){
        this.ctx.save();
        this.ctx.stroke();
      },
      delete(){
        if(this.isActive){
          this.isActive = false;
          this.deleteMediaFiguresObjectItem(this.index);
        }
      }
    },
    created(){},
    mounted(){
      this.init();

      // イベント登録
      document.addEventListener('click', (e)=> {
        if(!e.target.closest("#"+this.canvas_wrapper_with_index)){
          this.isActive = false;
        } else {
          this.isActive = true;
        }
      });
    },
  }

</script>

<style scoped>
.canvas_item-wrapper {
  position: absolute;
  display: inline-flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transform-origin: center center;
}
.canvas_item-wrapper:hover .rotate-icon{
  display: inline-block ;
}
.rotate-icon:hover{
  cursor: all-scroll;
}


.canvas_area {
  display: block;
}

.canvas_area:hover{
  cursor: all-scroll;
  }

.rotate-icon {
  position: absolute;
  bottom: -90px;  
  padding: 30px;
}

.is_active {
  outline : 1.5px solid blue;
}
.hidden {
  /* display: none; */
  opacity: 0;
}

.x-size-adjust-bar-wrapper,
.y-size-adjust-bar-wrapper{
  position: absolute;
  top: 0;
  left: 0;
}

/* x軸方向のサイズ調整 */
.x-size-adjust-bar-wrapper{
  display: flex;
  justify-content: space-between;
  align-items: center; 
}
.x-size-adjust-bar-wrapper:hover{
  cursor: all-scroll;
}

.right-size-adjust-bar,
.left-size-adjust-bar {
  background-color: red;
  width: 5px;
  height: 20px;
  border-radius: 5px;
}
.right-size-adjust-bar{
  margin-right: -3px;
}
.right-size-adjust-bar:hover{
  cursor: auto;  
}

.left-size-adjust-bar{
  margin-left: -3px;
}
.left-size-adjust-bar:hover{
  cursor: auto;
}

/* y軸方向のサイズ調整 */
.y-size-adjust-bar-wrapper{
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
}
.y-size-adjust-bar-wrapper:hover{
  cursor: all-scroll;
}
.top-size-adjust-bar,
.bottom-size-adjust-bar {
  background-color: blue;
  width: 20px;
  height: 5px;
  border-radius: 5px;
}
.top-size-adjust-bar {
  margin-top: -3px;
}
.top-size-adjust-bar:hover {
  cursor: auto;
}

.bottom-size-adjust-bar {
  margin-bottom: -3px;
}
.bottom-size-adjust-bar:hover {
  cursor: auto;
}

.mouse-position {
  position : absolute;
  /* bottom: -20px; */
  right: -90px;
}
.mouse-x {
  bottom: -20px;
}
.mosue-y {
  bottom: -40px;
}


</style>