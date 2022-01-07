<template>
  <!-- Media図形-->
  <div :id="canvas_wrapper_with_index" class="obj canvas_item-wrapper"
   :style="canvasWrapperStyle()" :class="{is_active : isActive}"
  @dblclick="showEditor" @click.stop @touchstart.stop>
    <canvas :id="canvas_with_index" class="canvas_area" :class="{is_active:isActive}"
    @mousedown="moveStart($event)" @touchstart="moveStart($event)" @dblclick="showEditor">
    </canvas>
  </div>
</template>

<script>
import {moveStart} from '../../../../../functions/moveHelper'
import { mapGetters, mapMutations } from 'vuex';

  export default {
    components : {},
    props:[
      'index',
    ],
    data : ()=>{
      return {
        "isReDraw" : false,
        "isResizing" :false,
        "canvas" : "",
        "ctx" : "",
        "canvas_wrapper" : "",

        "figureDatas" : {
          "left" : 0,
          "top" : 0,
          "width" : 0,
          "height" : 0,
          "degree" : 0,
          "layer" : 0,
          "globalAlpha" : 0,
          "isDrawFill" : false,
          "isDrawStroke" : false,
          "fillColor" : "",
          "strokeColor" : "",
        },

      }
    },
    computed : {
      ...mapGetters('mediaFigures', ['getMediaFigure']),
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      canvas_with_index:function(){ return 'canvas'+this.index; },
      canvas_wrapper_with_index:function(){ return 'canvas_wrapper'+this.index; },
      isEditMode : function(){
        const rn = this.$route.name;
        return (rn=="create" || rn=="edit") ? true : false
      },
      isActive:function(){
        return this.getSelectedObjects.some((obj)=>obj.type==0 && obj.index==this.index)
      },

    },
    watch : {
      isReDraw : function(){ this.init(); },
      figureDatas : {
        handler : function(val){
          if(this.isActive){
            const event = new CustomEvent('objectStatusChanged',{detail:{type:0,index:this.index}});
            document.body.dispatchEvent(event);
          }
        },
        deep : true
      },
    },
    methods : {
      ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      ...mapMutations('mediaFigures', ['updateMediaFiguresObjectItem']),
      ...mapMutations('mediaFigures', ['deleteMediaFiguresObjectItem']),
      ...mapMutations('selectedObjects', ['addSelectedObjectItem']),
      ...mapMutations('selectedObjects', ['deleteSelectedObjectItem']),
      showEditor(){
        const showSetting = new CustomEvent('showFigureSetting', {detail:{index:this.index}});
        document.body.dispatchEvent(showSetting);
      },
      getOneFigure(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaFigure;
      },
      canvasWrapperStyle(){
        const styleObject = {
          "left" : this.figureDatas["left"] + "px",
          "top" : this.figureDatas["top"] + "px",
          "width" : this.figureDatas["width"] + "px", // キャンバスより若干大きめに
          "height" : this.figureDatas["height"] + "px", // キャンバスより若干大きめに
          // "transform" : 'rotate('+ this.figureDatas['degree'] +'deg)',
        }
        return styleObject;
      },
      canvasSizeStyle(){
        const styleObject = {
          "width" : this.figureDatas["width"] + "px",
          "height" : this.figureDatas["height"] + "px",
        }
        return styleObject;
      },
      selected(){
        const objectSelected = new CustomEvent('objectSelected',
        {detail:{
          type:0
          ,index:this.index
          ,degree:this.figureDatas['degree']
          ,element_id:this.canvas_wrapper_with_index}});
        document.body.dispatchEvent(objectSelected);
      },
      // 位置操作用
      moveStart(e){
        const move_target_dom = document.getElementById(this.canvas_wrapper_with_index);
        moveStart(e, move_target_dom);
        move_target_dom.addEventListener('moveFinish', this.moveEnd, false);
      },
      moveEnd(e){
        e.target.removeEventListener('moveFinish', this.moveEnd, false);
        const new_left = e.detail.left;
        const new_top = e.detail.top;
        this.updateMediaFiguresObjectItem({index:this.index,key:"left",value:new_left});
        this.updateMediaFiguresObjectItem({index:this.index,key:"top",value:new_top});
        this.figureDatas['left'] = new_left;
        this.figureDatas['top'] = new_top;
      },
      updateDegree(event){
        const new_degree = event.detail.degree;
        this.updateMediaFiguresObjectItem({index:this.index,key:"degree",value:new_degree});
        this.figureDatas['degree'] = new_degree;
      },
      updateSizeAndPosition(event){
        const diff_x = event.detail.diff_x;
        const diff_y = event.detail.diff_y;
        const x = event.detail.resize_side['x'];
        const y = event.detail.resize_side['y'];
        const start_value = event.detail.resize_start_infos;
        let new_values = {};
        new_values['width'] = start_value['width'] + diff_x;
        new_values['height'] = start_value['height'] + diff_y;
        const current_left = start_value['left'];
        const current_top = start_value['top'];
        new_values['left'] = x == -1 ? current_left - diff_x : current_left;
        new_values['top'] = y == -1 ? current_top - diff_y : current_top;
        Object.keys(new_values).forEach((key)=>{
          if(new_values[key]){
            const new_val = Math.floor(new_values[key]);
            this.updateMediaFiguresObjectItem({index:this.index,key:key,value:new_val});
            this.figureDatas[key] = new_val;
          }
        })
        this.reDraw();
      },
      reDraw(){
        this.setCanvasSize();
        this.setLayer();
        this.setGlobalAlpha();
        this.setStrokeColor();
        this.setFillColor();
        this.createPath();
        this.draw();
      },
      // 図形描画関連
      init(){
        this.setFigureData();
        this.setCanvasSize();
        this.setLayer();
        this.setGlobalAlpha();
        this.setStrokeColor();
        this.setFillColor();
        this.createPath();
        this.draw();
        this.isReDraw = false;
      },
      checkTypeNum(key){
        const num_type_keys = ["width","height","degree","left","top","globalAlpha"];
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
      setFigureData(){
        const storeData = this.getOneFigure(this.index);
        if(!(typeof storeData === "undefined")){
          for(let key of Object.keys(storeData)){
            this.figureDatas[key] = this.fixDataType(key, storeData[key]);
          }
        }
      },
      setContext(){
        this.canvas = document.getElementById(this.canvas_with_index);
        this.ctx = this.canvas.getContext('2d');
      },
      setCanvasSize(){
        this.canvas.width = this.figureDatas['width'];
        this.canvas.height = this.figureDatas['height'];
      },
      draw(){
        this.clear();
        if(this.figureDatas['isDrawFill']){ this.fill(); };
        if(this.figureDatas['isDrawStroke']){ this.stroke(); };
      },
      clear(){
        this.ctx.clearRect(0,0,this.figureDatas['width'],this.figureDatas['height']);
      },
      createPath(){
        if(this.figureDatas['type']==1){ // 四角形
          this.createPathRect();
        } else if(this.figureDatas['type']==2){ // 楕円
          this.createPathEllipse();
        }
      },
      createPathRect(){ // 四角形。図形タイプ1
        const width = this.figureDatas['width'];
        const height = this.figureDatas['height'];

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
      createPathEllipse(){ // 楕円。図形タイプ2
        this.ctx.beginPath();
        const radius_x = this.figureDatas['width']/2 - 2; // x軸半径
        const radius_y = this.figureDatas['height']/2 - 2; // y軸半径
        const center_x = radius_x + 1; // 中心x座標
        const center_y = radius_y + 1; // 中心y座標
        this.ctx.ellipse(center_x, center_y, radius_x, radius_y, 0, 0, Math.PI*2);
      },
      setLayer(){ document.getElementById(this.canvas_wrapper_with_index).style.zIndex = this.figureDatas['layer'] },
      setGlobalAlpha(){ this.ctx.globalAlpha = this.figureDatas['globalAlpha'];},
      setStrokeColor(){this.ctx.strokeStyle = this.figureDatas['strokeColor'];},
      setFillColor(){this.ctx.fillStyle = this.figureDatas['fillColor'];},
      fill(){
        this.ctx.save();
        this.ctx.fill();
      },
      stroke(){
        this.ctx.save();
        this.ctx.stroke();
      },
    },
    created(){},
    mounted(){
      this.canvas_wrapper = document.getElementById(this.canvas_wrapper_with_index);
      this.setContext();
      this.init();

      // イベント登録
      this.canvas_wrapper.addEventListener('resize',this.updateSizeAndPosition,false);
      this.canvas_wrapper.addEventListener('rotateObject',this.updateDegree,false);
      this.canvas_wrapper.addEventListener('figureDataUpdated',this.init,false);
      this.canvas_wrapper.addEventListener('click',this.selected,false);
      this.canvas_wrapper.addEventListener('touchstart',this.selected,false);
    },
  }

</script>

<style scoped>
@import "/resources/css/mediaObjectCommon.css";
@import "/resources/css/flexSetting.css";


.canvas_item-wrapper {
  position: absolute;
  display: inline-flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transform-origin: center center;
}


.canvas_area {
  display: block;
}

.canvas_area:hover{
  cursor: all-scroll;
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
  right: -90px;
}
.mouse-x {
  bottom: -20px;
}
.mosue-y {
  bottom: -40px;
}


</style>