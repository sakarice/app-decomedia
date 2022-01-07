<template>
  <i class="fas fa-sync fa-lg rotate-icon" @mousedown="rotateStart($event)" @touchstart="rotateStart($event)"></i>
</template>

<script>
  export default {
    props:[
      // 'index',
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
    },
    watch : {},
    methods : {
      getStyleSheetValue(element,property){ // ↑でcssの値を取得するための関数
        if (!element || !property) {
          return null;
        }
        var style = window.getComputedStyle(element);
        var value = style.getPropertyValue(property);
        return value;
      },
      // 回転用
      rotateInit(event){
        const target_id = event.detail.element_id;
        this.rotate_target = document.getElementById(target_id);
      },
      rotateStart(e){

        this.target_left = this.rotate_target.getBoundingClientRect().left + window.pageXOffset;
        this.target_top = this.rotate_target.getBoundingClientRect().top + window.pageYOffset;
        const x = this.rotate_target.clientWidth;
        const y = this.rotate_target.clientHeight;
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
        let event;
        let distance_x_from_target_center;
        let distance_y_from_target_center;
        if(e.type==="mousemove"){
          event = e;
          distance_x_from_target_center = event.clientX - this.rotate_center_x;
          distance_y_from_target_center = event.clientY - this.rotate_center_y;
        } else {
          event = e.changedTouches[0];
          distance_x_from_target_center = event.pageX - this.rotate_center_x;
          distance_y_from_target_center = event.pageY - this.rotate_center_y;
        }
        const new_rad = Math.atan2(distance_x_from_target_center, distance_y_from_target_center);
        const new_deg = Math.floor((new_rad * (180/Math.PI) * (-1)) % 360); // rotateは通常時計周り。そのままだとマウスの回転と逆になってしまうため×-1
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

        const rotateFinishEvent = new CustomEvent('rotateFinish', {detail:{degree:this.degree}});
        this.rotate_target.dispatchEvent(rotateFinishEvent);
      },
    },
    created(){
      document.body.addEventListener('objectSelected',this.rotateInit, false);
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