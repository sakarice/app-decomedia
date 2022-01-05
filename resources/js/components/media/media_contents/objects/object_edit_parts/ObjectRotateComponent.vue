<template>
  <div class="rotate-icon-wrapper" :style="style" v-show="isObjSelected">
    <i class="fas fa-sync fa-lg rotate-icon"
    @mousedown="rotateStart($event)" @touchstart="rotateStart($event)"></i>
  </div>
  
</template>

<script>
  import {mapGetters} from 'vuex';
  export default {
    props:[],
    data : ()=>{
      return {
        "rotate_target" : "", // ドラッグ操作で回転させる対象
        "rotate_center_x_in_page" : 0,
        "rotate_center_y_in_page" : 0,
        "degree" : 0, 
        "rotate_icon_left" : 0,
        "rotate_icon_top" : 0,
      }
    },
    computed : {
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      style:function(){
        const style = {
          "left" : this.rotate_icon_left + "px",
          "top" : this.rotate_icon_top + "px",
        }
        return style;
      },
      style_center:function(){
        const style = {
          "left" : this.rotate_center_x_in_page + "px",
          "top" : this.rotate_center_y_in_page + "px",
        }
        return style;
      },
      isObjSelected:function(){
        return this.getSelectedObjects.length > 0 ? true:false
      },
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
      objectSelected(event){
        const target_id = event.detail.element_id;
        this.target_init(target_id);
        this.degree = event.detail.degree;
        const {observer, config} = this.getMutationObserver();
        observer.observe(this.rotate_target, config);
        this.setRotateCenter();
        this.setPosition();
      },
      target_init(target_id){
        this.rotate_target = document.getElementById(target_id);
      },
      rotateStart(e){
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
          distance_x_from_target_center = event.clientX - this.rotate_center_x_in_page;
          distance_y_from_target_center = event.clientY - this.rotate_center_y_in_page;
        } else {
          event = e.changedTouches[0];
          distance_x_from_target_center = event.pageX - this.rotate_center_x_in_page;
          distance_y_from_target_center = event.pageY - this.rotate_center_y_in_page;
        }
        const new_rad = Math.atan2(distance_x_from_target_center, distance_y_from_target_center);
        const new_deg = Math.floor((new_rad * (180/Math.PI) * (-1)) % 360); // rotateは通常時計周り。そのままだとマウスの回転と逆になってしまうため×-1
        this.rotate_target.style.transform = 'rotate('+ new_deg +'deg)';
        this.degree = new_deg;
        // マウス、タッチ解除時のイベントを設定
        document.body.addEventListener("mouseleave", this.rotateEnd, false);
        document.body.addEventListener("touchleave", this.rotateEnd, false);
        const rotateObjectEvent = new CustomEvent('rotateObject', {detail:{degree:new_deg}});
        this.rotate_target.dispatchEvent(rotateObjectEvent);
      },
      rotateEnd(e){
        document.body.removeEventListener("mousemove", this.rotating, false);
        this.rotate_target.removeEventListener("mouseup", this.rotateEnd, false);
        document.body.removeEventListener("touchmove", this.rotating, false);
        this.rotate_target.removeEventListener("touchend", this.rotateEnd, false);

        const rotateFinishEvent = new CustomEvent('rotateFinish', {detail:{degree:this.degree}});
        this.rotate_target.dispatchEvent(rotateFinishEvent);
      },
      setRotateCenter(){
        const target_width = this.rotate_target.clientWidth;
        const target_height = this.rotate_target.clientHeight;
        const rect = this.rotate_target.getBoundingClientRect();
        const target_left_in_page = rect.left + window.pageXOffset;
        const target_top_in_page = rect.top + window.pageYOffset;
        // ポインターがページ内の絶対座標のため、回転の中心もページ内の絶対座標とする
        this.rotate_center_x_in_page = target_left_in_page + target_width/2;
        this.rotate_center_y_in_page = target_top_in_page + target_height/2;
      },
      setPosition(){
        // アイコンを表示する座標は、コンテンツ描画エリア内の相対座標(回転対象オブジェクトと同じ)
        const target_left_in_contents_field = Number(this.rotate_target.style.left.replace("px",""));
        const target_top_in_contents_field = Number(this.rotate_target.style.top.replace("px",""));
        const target_width = this.rotate_target.clientWidth;
        const target_height = this.rotate_target.clientHeight;
        const rotate_center_x = target_left_in_contents_field + target_width/2;
        const rotate_center_y = target_top_in_contents_field + target_height/2;
        const r = target_height/2 + 50; // 回転対象オブジェクト中心から回転アイコンまでの距離
        this.rotate_icon_left = rotate_center_x - r * Math.sin(this.degree * (Math.PI/180)) - 10;
        this.rotate_icon_top = rotate_center_y + r * Math.cos(this.degree* (Math.PI/180));
      },
      getMutationObserver(){
        const observer = new MutationObserver((mutations)=>{
          this.setRotateCenter();
          this.setPosition();
        })
        const config = {
          attribute : true,
          attributeFilter : ['style'],
        }
        return {"observer":observer, "config":config};
      },
    },
    created(){
      document.body.addEventListener('objectSelected',this.objectSelected, false);
    },
    mounted(){}

  }

</script>

<style scoped>

.rotate-icon-wrapper{
  position:absolute;
}

.rotate-icon {
  display: inline-block ;
  /* position: absolute; */
}

.rotate-icon:hover{
  cursor: all-scroll;
}


</style>