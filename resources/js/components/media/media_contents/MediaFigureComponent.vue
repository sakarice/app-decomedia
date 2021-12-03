<template>
  <!-- Media図形-->
  <!-- <div id="media-figure-wrapper"> -->
  <canvas :id="canvas_with_index" class="canvas_area" 
  @mousedown="mouseDown($event)" @touchstart="mouseDown($event)"
  @mouseup="mouseUp($event)" @touchend="mouseUp($event)">
  </canvas>
  <!-- </div> -->
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    props:[
      'index',
    ],
    data : ()=>{
      return {
        "canvas" : "",
        "ctx" : "",
        "move_target" : "", // ドラッグ操作で移動させる対象
        "x_in_element" : 0, // 移動対象要素に対するドラッグポイントの相対座標(x)
        "y_in_element" : 0, // 移動対象要素に対するドラッグポイントの相対座標(y)
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
      canvas_width:function(){ return this.window_width+"px" },
      canvas_height:function(){ return this.window_height+"px" },
      canvas_with_index:function(){ return 'canvas'+this.index; }
    },
    watch : {},
    methods : {
      ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      ...mapMutations('mediaFigures', ['updateMediaFiguresObjectItem']),
      getOneFigure(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaFigure;
      },
      // 位置操作用
      mouseDown(e){
        let event;
        if(e.type==="mousedown"){
          event = e;
        } else {
          event = e.changedTouches[0];
        }
        this.move_target = document.getElementById(this.canvas_with_index);
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
      mouseUp(e){
        this.updateMediaFiguresObjectItem({index:this.index,key:"x_position",value:e.clientX - this.x_in_element});
        this.updateMediaFiguresObjectItem({index:this.index,key:"y_position",value:e.clientY - this.y_in_element});

        document.body.removeEventListener("mousemove", this.mouseMove, false);
        this.move_target.removeEventListener("mouseup", this.mouseUp, false);
        document.body.removeEventListener("touchmove", this.mouseMove, false);
        this.move_target.removeEventListener("touchend", this.mouseUp, false);
      },
      // 初期描画位置
      setPosition(){
        const target = document.getElementById(this.canvas_with_index);
        console.log(target);
        console.log('rotate('+ this.degree +'deg)');
        target.style.left = this.x_position + 'px';
        target.style.top = this.y_position + 'px';
        target.style.transform = 'rotate('+ this.degree +'deg)';
        // target.style.webkitTransform = 'rotate('+ this.degree +'deg)';
      },

      // 図形描画関連
      setFigureData(){
        const figureData = this.getOneFigure(this.index);
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
      },
      setContext(){
        this.canvas = document.getElementById(this.canvas_with_index);
        console.log(this.canvas);
        this.ctx = this.canvas.getContext('2d');
      },
      setCanvasSize(){
        this.window_width = this.figure_width;
        // this.window_width = window.innerWidth;
        this.window_height = this.figure_height;
        // this.window_height = window.innerHeight;
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
        console.log('start draw');
        if(this.is_draw_fill){ this.fill(); };
        if(this.is_draw_stroke){ this.stroke(); };
      },
      clear(){
        this.ctx.clearRect(0,0,this.window_width,this.window_height);
      },
      createPathRect(){
        // const start_x = this.x_position;
        // const start_y = this.y_position;
        const width = this.figure_width;
        const height = this.figure_height;

        // 左上から半時計回りに、四角形の4頂点のポイントを指定
        // const point1_left_upper  = {x: start_x,         y: start_y};
        // const point2_left_under  = {x: start_x ,        y: start_y + height};
        // const point3_right_under = {x: start_x + width, y: start_y + height};
        // const point4_right_upper = {x: start_x + width, y: start_y};
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
        // this.ctx.restore();
      }
    },
    created(){
      this.setFigureData();
    },
    mounted(){
      this.setPosition();
      this.setContext();
      this.setCanvasSize();
      this.setGlobalAlpha();
      this.setStrokeColor();
      this.setFillColor();
      // this.setDegree();
      this.createPathRect();
      this.draw();

    },
  }

</script>

<style scoped>

.canvas_area {
  position: absolute;
  display: block;
  /* width: 100vw;
  height: 100vh; */
}
.canvas_area:hover{
  cursor: all-scroll;
  outline : 1.5px solid blue;
  }


</style>