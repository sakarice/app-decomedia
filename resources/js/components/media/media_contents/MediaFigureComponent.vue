<template>
  <!-- Media図形-->
  <!-- <div id="media-figure-wrapper"> -->
  <canvas :id="canvas_with_index" class="canvas_area"></canvas>
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
      getOneFigure(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaFigure;
      },
      setPosition(){
        const target = document.getElementById(this.canvas_with_index);
        console.log(target);
        console.log('rotate('+ this.degree +'deg)');
        target.style.left = this.x_position + 'px';
        target.style.top = this.y_position + 'px';
        target.style.transform = 'rotate('+ this.degree +'deg)';
        // target.style.webkitTransform = 'rotate('+ this.degree +'deg)';
      },
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
  cursor: pointer;
}


</style>