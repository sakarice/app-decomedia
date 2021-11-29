<template>
  <!-- Media図形-->
  <div id="media-figure-wrapper">
    <canvas id="canvas" class="canvas_test" v-bind:style="{width:canvas_width, height:canvas_height}"></canvas>
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
        "ctx" : "",
        "window_width" : 0,
        "window_height" : 0,
      }
    },
    computed : {
      ...mapGetters('mediaFigure', ['getMediaFigure']),
      canvas_width:function(){ return this.window_width+"px" },
      canvas_height:function(){ return this.window_height+"px" },
      x_position(){ return this.getMediaFigure['x_position'] },
      y_position(){ return this.getMediaFigure['y_position'] },
      degree(){ return this.getMediaFigure['degree'] },
      figure_width(){ return this.getMediaFigure['width'] },
      figure_height(){ return this.getMediaFigure['height'] },
      is_draw_fill(){ return this.getMediaFigure['isDrawFill']},
      is_draw_stroke(){ return this.getMediaFigure['isDrawStroke']},
      fill_color(){ return this.getMediaFigure['fillColor']},
      stroke_color(){ return this.getMediaFigure['strokeColor']},
      global_alpha(){ return this.getMediaFigure['globalAlpha']},
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
        const canvas = document.getElementById('canvas');
        this.ctx = canvas.getContext('2d');
      },
      draw(){
        let timer = "";
        let tmpThis = this;
        if(timer){
          clearTimeout(timer);
        }
        timer = setTimeout(function(){
          tmpThis.clear();
          if(tmpThis.getMediaFigure['isDrawFill']){ tmpThis.fill(); };
          if(tmpThis.getMediaFigure['isDrawStroke']){ tmpThis.stroke(); };
        },200);
      },
      clear(){
        this.ctx.clearRect(0,0,this.window_width,this.window_height);
      },
      setDegree(){ 
        this.ctx.translate(this.window_width/2, this.window_height/2);
        this.ctx.rotate(this.getMediaFigure['degree']*Math.PI / 180);
        this.ctx.translate(-this.window_width/2, -this.window_height/2);
      },
      setGlobalAlpha(){ this.ctx.globalAlpha = this.getMediaFigure['globalAlpha']},
      setFillColor(){this.ctx.fillStyle = this.getMediaFigure['fillColor'];},
      setStrokeColor(){this.ctx.strokeStyle = this.getMediaFigure['strokeColor'];},
      fill(){
        this.setDegree();
        this.setFillColor();
        this.ctx.fillRect(
          this.getMediaFigure['x_position']
          ,this.getMediaFigure['y_position']
          ,this.getMediaFigure['width']
          ,this.getMediaFigure['height']
        );
      },
      stroke(){
        this.setDegree();
        this.setStrokeColor();
        this.ctx.strokeRect(
          this.getMediaFigure['x_position']
          ,this.getMediaFigure['y_position']
          ,this.getMediaFigure['width']
          ,this.getMediaFigure['height']
        );
      }
    },
    created(){
      this.window_width = window.innerWidth;
      this.window_height = window.innerHeight;
    },
    mounted(){
      this.setContext();
      this.setGlobalAlpha();
      this.setFillColor();
      this.setStrokeColor();
      this.draw();

      let timer = "";
      let tmpThis = this;
      window.onresize = function(){
        if(timer){
          clearTimeout(timer);  
        }
        timer = setTimeout(function(){
          tmpThis.window_width = window.innerWidth;
          tmpThis.window_height = window.innerHeight;
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
.canvas_test {
  /* width: 500px;
  height: 500px; */
}


</style>