<template>
  <!-- Media図形-->
  <div id="media-figure-wrapper">
    <canvas id="canvas" class="canvas_area"></canvas>
  </div>
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
        "dpr" : 1,
      }
    },
    computed : {
      ...mapGetters('mediaFigureFactory', ['getFigureData']),
      canvas_width:function(){ return this.window_width+"px" },
      canvas_height:function(){ return this.window_height+"px" },
      x_position(){ return this.getFigureData['x_position'] },
      y_position(){ return this.getFigureData['y_position'] },
      degree(){ return this.getFigureData['degree'] },
      figure_width(){ return this.getFigureData['width'] },
      figure_height(){ return this.getFigureData['height'] },
      is_draw_fill(){ return this.getFigureData['isDrawFill']},
      is_draw_stroke(){ return this.getFigureData['isDrawStroke']},
      fill_color(){ return this.getFigureData['fillColor']},
      stroke_color(){ return this.getFigureData['strokeColor']},
      global_alpha(){ return this.getFigureData['globalAlpha']},
    },
    watch : {
      figure_width(){ this.draw(); },
      figure_height(){ this.draw(); },
      x_position(){ this.draw(); },
      y_position(){ this.draw(); },
      degree(){ this.draw(); },
      is_draw_fill(){ this.draw(); },
      is_draw_stroke(){ this.draw(); },
      fill_color(){ this.draw(); },
      stroke_color(){ this.draw() },
      global_alpha(){ 
        this.setGlobalAlpha();
        this.draw() 
      },
    },
    methods : {
      setContext(){
        console.log('start setContext');
        this.canvas = document.getElementById('canvas');
        this.ctx = canvas.getContext('2d');
      },
      resizeCanvas(){
        this.window_width = window.innerWidth;
        this.window_height = window.innerHeight;
        this.canvas.width = this.window_width;
        this.canvas.height = this.window_height;
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
        this.ctx.clearRect(0,0,this.window_width,this.window_height);
      },
      setDegree(){ 
        // 描画予定の図形の中心にcontextの回転軸を持ってきて回転する。
        let move_x = Number(this.getFigureData['x_position']) + Number(this.getFigureData['width']/2);
        let move_y = Number(this.getFigureData['y_position']) + Number(this.getFigureData['height']/2);
        this.ctx.translate(move_x, move_y);
        this.ctx.rotate(this.getFigureData['degree']*Math.PI / 180);
        this.ctx.translate(-move_x, -move_y); // 回転軸を元の位置に戻す。
      },
      createPathRect(){
        const start_x = Number(this.getFigureData['x_position']);
        const start_y = Number(this.getFigureData['y_position']);
        const width = Number(this.getFigureData['width']);
        const height = Number(this.getFigureData['height']);

        // 左上から半時計回りに、四角形の4頂点のポイントを指定
        const point1_left_upper  = {x: start_x,         y: start_y};
        const point2_left_under  = {x: start_x ,        y: start_y + height};
        const point3_right_under = {x: start_x + width, y: start_y + height};
        const point4_right_upper = {x: start_x + width, y: start_y};

        this.ctx.beginPath();
        this.ctx.moveTo(point1_left_upper['x'],  point1_left_upper['y']);
        this.ctx.lineTo(point2_left_under['x'], point2_left_under['y']);
        this.ctx.lineTo(point3_right_under['x'], point3_right_under['y']);
        this.ctx.lineTo(point4_right_upper['x'], point4_right_upper['y']);
        this.ctx.closePath();
      },
      setGlobalAlpha(){ this.ctx.globalAlpha = this.getFigureData['globalAlpha']},
      setFillColor(){this.ctx.fillStyle = this.getFigureData['fillColor'];},
      setStrokeColor(){this.ctx.strokeStyle = this.getFigureData['strokeColor'];},
      prepareDraw(){
        this.ctx.save();
        this.setGlobalAlpha();
        this.setStrokeColor();
        this.setFillColor();
        this.setDegree();
        this.createPathRect();
      },
      fill(){
        this.prepareDraw();
        this.ctx.fill();
        this.ctx.restore();
      },
      stroke(){
        this.prepareDraw();
        this.ctx.stroke();
        this.ctx.restore();
      }
    },
    created(){
      this.dpr = window.devicePixelRatio || 1;
    },
    mounted(){
      this.setContext();
      this.resizeCanvas();
      this.draw();

      let timer = "";
      let tmpThis = this;
      window.onresize = function(){
        if(timer){
          clearTimeout(timer);  
        }
        timer = setTimeout(function(){
          tmpThis.resizeCanvas();
          tmpThis.draw();
          console.log('window size changed');
        },200);
      }

    },
  }

</script>

<style scoped>
#media-figure-wrapper {
  position: absolute;
  top: 0;
  left: 0;
}
.canvas_area {
  display: block;
  /* width: 100vw;
  height: 100vh; */
}


</style>