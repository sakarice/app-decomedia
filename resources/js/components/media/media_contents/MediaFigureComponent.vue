<template>
  <!-- Media図形-->
  <div id="media-figure-wrapper">
    <canvas id="canvas" class="canvas_test" v-bind:style="{width:window_width, height:window_height}"></canvas>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    data : ()=>{
      return {
        "ctx" : "",
        "window_width" : "",
        "window_height" : "",
      }
    },
    computed : {
      ...mapGetters('mediaFigure', ['getMediaFigure']),
      // canvas_width : ()=>{ return this.window_width+"px" },
      // canvas_height : ()=>{ return this.window_height+"px" },
    },
    methods : {
      setContext(){
        console.log('start setContext');
        const canvas = document.getElementById('canvas');
        this.ctx = canvas.getContext('2d');
      },
      draw(){
        if(this.getMediaFigure['isFill']){ this.fill(); }
        if(this.getMediaFigure['isRect']){ this.rect(); }
      },
      fill(){
        console.log('start fill');
        this.ctx.fillStyle = this.getMediaFigure['fillColor'];
        this.ctx.fillRect(10,10,this.getMediaFigure['width'],this.getMediaFigure['height']);
      },
      rect(){
        console.log('start rect');
        this.ctx.fillStyle = this.getMediaFigure['rectColor'];
        this.ctx.strokeRect(10,10,this.getMediaFigure['width'],this.getMediaFigure['height']);
      }
    },
    created(){
      this.window_width = window.innerWidth+"px";
      this.window_height = window.innerHeight+"px";

    },
    mounted(){
      this.setContext();
      this.draw();

      let timer = "";
      let tmpThis = this;
      window.onresize = function(){
        if(timer){
          clearTimeout(timer);  
        }
        timer = setTimeout(function(){
          tmpThis.window_width = window.innerWidth+"px";
          tmpThis.window_height = window.innerHeight+"px";
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