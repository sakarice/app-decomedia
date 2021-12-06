<template>
  <i class="fas fa-sync fa-lg rotate-icon" @mousedown="rotateStart($event)" @touchstart="rotateStart($event)"></i>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';
  export default {
    props:[
      'index',
      // 'rotate_target'
    ],
    data : ()=>{
      return {
        "rotate_target" : "", // ドラッグ操作で回転させる対象
        "rotate_center_x" : 0,
        "rotate_center_y" : 0,
        "degree" : 0, 
      }
    },
    computed : {
      ...mapGetters('mediaFigures', ['getMediaFigure']),
      canvas_wrapper_with_index:function(){ return 'canvas_wrapper'+this.index; },
    },
    watch : {},
    methods : {
      ...mapMutations('mediaFigures', ['setTargetObjectIndex']),
      ...mapMutations('mediaFigures', ['updateMediaFiguresObjectItem']),
      getOneFigure(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaFigure;
      },
      getStyleSheetValue(element,property){ // ↑でcssの値を取得するための関数
        if (!element || !property) {
          return null;
        }
        var style = window.getComputedStyle(element);
        var value = style.getPropertyValue(property);
        return value;
      },
      // 回転用
      rotateStart(e){
        let event;
        if(e.type==="mousedown"){
          event = e;
        } else {
          event = e.changedTouches[0];
        }
        this.rotate_target = document.getElementById(this.canvas_wrapper_with_index);
        this.target_left = Number(this.getStyleSheetValue(this.rotate_target, "left").replace("px",""));
        this.target_top = Number(this.getStyleSheetValue(this.rotate_target, "top").replace("px",""));
        const x = this.getOneFigure(this.index)['width'];
        const y = this.getOneFigure(this.index)['height'];
        // const r =  Math.sqrt(x*x + y*y);
        this.rotate_center_x = this.target_left + x/2;
        this.rotate_center_y = this.target_top + y/2;

        // 回転イベントにコールバック
        document.body.addEventListener("mousemove", this.rotating, false);
        document.body.addEventListener("mouseup", this.rotateEnd, false);
        document.body.addEventListener("touchmove", this.rotating, false);
        document.body.addEventListener("touchend", this.rotateEnd, false);
      },
      rotating(e){
        e.preventDefault();
        const distance_x_from_target_center = e.clientX - this.rotate_center_x;
        const distance_y_from_target_center = e.clientY - this.rotate_center_y;
        const new_rad = Math.atan2(distance_x_from_target_center, distance_y_from_target_center);
        const new_deg = new_rad * (180/Math.PI) * (-1); // rotateは通常時計周り。そのままだとマウスの回転と逆になってしまうため×-1
        this.rotate_target.style.transform = 'rotate('+ new_deg +'deg)';
        this.degree = new_deg;
        // マウス、タッチ解除時のイベントを設定
        document.body.addEventListener("mouseleave", this.rotateEnd, false);
        document.body.addEventListener("touchleave", this.rotateEnd, false);
      },
      rotateEnd(e){
        document.body.removeEventListener("mousemove", this.rotating, false);
        this.rotate_target.removeEventListener("mouseup", this.rotateEnd, false);
        document.body.removeEventListener("touchmove", this.rotating, false);
        this.rotate_target.removeEventListener("touchend", this.rotateEnd, false);

        this.updateMediaFiguresObjectItem({index:this.index,key:"degree",value:this.degree});
        this.$emit('rotate-finish', this.degree);
      },

    },
  }

</script>

<style scoped>

.rotate-icon {
  display: inline-block ;
  position: absolute;
  bottom: -90px;  
  padding: 30px;
}

.rotate-icon:hover{
  cursor: all-scroll;
}

</style>