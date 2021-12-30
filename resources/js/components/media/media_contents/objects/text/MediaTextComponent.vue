<template>
  <!-- Media図形-->
  <div :id="text_wrapper_with_index" class="obj text-wrapper"
  :style="textWrapperStyle"
  @click.stop @touchstart.stop>
    <p contenteditable spellcheck="false"
    :id="text_with_index"
    class="text-area"
    :style="textStyle"
    @mousedown="moveStart" @touchstart="moveStart">
    {{mediaText['text']}}
    </p>

    <!-- <object-resize v-show="isEditMode" :index="index" :id="resize_wrapper_with_index" :class="{hidden:!isActive}"
     @move="moveStart($event)">
    </object-resize> -->
    
    <object-rotate v-show="isEditMode && isActive" :index="index" v-on:rotate-finish="updateDegree"></object-rotate>

  </div>

  
</template>

<script>
import {moveStart} from '../../../../../functions/moveHelper'
import { mapGetters, mapMutations } from 'vuex';
import objectRotate from '../object_edit_parts/ObjectRotateComponent.vue';
import objectResize from '../object_edit_parts/ObjectResizeComponent.vue';

  export default {
    components : {
      objectRotate,
      objectResize,
    },
    props:[
      'index',
    ],
    data : ()=>{
      return {
        mediaText : "",
        isReDraw : false,
        isResizing :false,
        original_width : 100,
        original_height : 100,
        width : 100,
        height : 100,
        text_wrapper : "",
      }
    },
    computed : {
      ...mapGetters('mediaTexts', ['getMediaText']),
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      text_with_index:function(){ return 'text'+this.index; },
      text_wrapper_with_index:function(){ return 'text_wrapper'+this.index; },
      resize_wrapper_with_index:function(){ return 'resize_wrapper'+this.index; },
      isEditMode : function(){
        const rn = this.$route.name;
        return (rn=="create" || rn=="edit") ? true : false
      },
      isActive:function(){
        return this.getSelectedObjects.some((obj)=>obj.type==3 && obj.index==this.index)
      },
      // scale_x:function(){ return this.width / this.original_width},
      // scale_y:function(){ return this.height / this.original_height},
      textWrapperStyle:function(){
        const textWrapperStyle = {
          "left" : this.mediaText['left'] + "px",
          "top" : this.mediaText['top'] + "px",
          "width" : (this.original_width * this.mediaText['scale_x']) + "px",
          "height" : (this.original_height * this.mediaText['scale_y']) + "px",
        }
        return textWrapperStyle;
      },
      textStyle:function(){
        const textStyle = {
          // "width" : this.original_width + "px",
          // "height" : this.original_height + "px",
          "transform" : "scaleX(" + this.mediaText['scale_x'] + ")" + " scaleY(" + this.mediaText['scale_y'] + ")",
        }
        return textStyle;
      },
      domScaleWrapperStyle:function(){
        const style = {
          "width" : this.mediaText['width'],
          "height" :this.mediaText['height'],
        }
        return style;
      },

    },
    watch : {},
    methods : {
      ...mapMutations('selectedObjects', ['addSelectedObjectItem']),
      ...mapMutations('selectedObjects', ['deleteSelectedObjectItem']),
      ...mapMutations('mediaTexts', ['setTargetObjectIndex']),
      ...mapMutations('mediaTexts', ['updateMediaTextsObjectItem']),
      getOneText(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaText;
      },
      initTextData(){
        this.mediaText = Object.assign({}, this.getOneText(this.index));
      },
      checkTypeNum(key){
        const num_type_keys = ["width","height","left","top","degree","opacity"];
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
      setTextData(){
        const storeData = Object.assign({},this.getOneText(this.index));
        if(!(typeof storeData === "undefined")){
          for(let key of Object.keys(storeData)){
            this.mediaText[key] = this.fixDataType(key, storeData[key]);
          }
        }
      },
      updateSizeAndPosition(event){
        const data = event.detail;
        const keys = ["width","height","left","top"];
        keys.forEach(key=>{
        });
      },
      scaleX(event){
        console.log('scaleX');
        const e = event.detail;
        console.log(e);
        this.width += e.diff;
        this.text.style.width = this.original_width + "px";
        const new_scale_x = this.width / this.original_width;
        let new_left;
        switch (e.direction) {
          case "right":
            new_left = this.mediaText['left'];
            break;
          case "left":
            new_left = this.mediaText['left'] - e.diff;
            break;
        }
        console.log("new_left:");
        console.log(new_left);
        // this.text_wrapper.style.left = new_left + "px";
        this.updateMediaTextsObjectItem({index:this.index,key:"left", value:new_left})
        this.updateMediaTextsObjectItem({index:this.index,key:"scale_x", value:new_scale_x})
        this.initTextData();
      },
      scaleY(event){
        console.log('scaleY');
        const e = event.detail;
        console.log(e);
        this.height += e.diff;
        this.text.style.height = this.original_height + "px";
        const new_scale_y = this.height / this.original_height;
        let new_top;
        switch (e.direction) {
          case "bottom":
            new_top = this.mediaText['top'];
            break;
          case "top":
            new_top = this.mediaText['top'] - e.diff;
            break;
        }
        // this.text_wrapper.style.top = new_top + "px";
        this.updateMediaTextsObjectItem({index:this.index,key:"top", value:new_top})
        this.updateMediaTextsObjectItem({index:this.index,key:"scale_y", value:new_scale_y})
        this.initTextData();
        console.log(this.text_wrapper.style.top);
      },
      // 位置操作用
      moveStart(e){
        const move_target_dom = this.text_wrapper;
        moveStart(e, move_target_dom);
        move_target_dom.addEventListener('moveFinish', this.moveEnd, false);
      },
      moveEnd(e){
        const new_left = e.detail.left;
        const new_top = e.detail.top;
        this.updateMediaTextsObjectItem({index:this.index,key:"left", value:new_left})
        this.updateMediaTextsObjectItem({index:this.index,key:"top", value:new_top})
        this.initTextData();
        e.target.removeEventListener('moveFinish', this.moveEnd, false);
      },
      updateDegree(event){
        const new_degree = event.detail.degree;
        this.updateMediaTextsObjectItem({index:this.index,key:"degree",value:new_degree});
      },
      selected(){
        const objectSelected = new CustomEvent('objectSelected',
        {detail:{
          type:3
          ,index:this.index
          ,width:this.original_width
          ,height:this.original_height
          ,scale_x:this.mediaText['scale_x']
          ,scale_y:this.mediaText['scale_y']
          // ,element_id:this.text_with_index}});
          ,element_id:this.text_wrapper_with_index}});
        document.body.dispatchEvent(objectSelected);
        // console.log(this.mediaText['scale_x']);
        // console.log(objectSelected.detail.scale_x);
      },
    },
    created(){},
    mounted(){
      this.text_wrapper = document.getElementById(this.text_wrapper_with_index);
      this.text = document.getElementById(this.text_with_index);
      // DOMの描画終了を待つ
      this.$nextTick(function(){
        const width = Number(this.text.clientWidth);
        const height = Number(this.text.clientHeight);
        this.original_width = this.width = width;
        this.original_height = this.height = height;
        this.text.style.width = this.original_width;
        this.text.style.height = this.original_height;
      });
      this.initTextData();
      // イベント登録
      this.text_wrapper.addEventListener('moveStart',this.moveStart,false);
      this.text_wrapper.addEventListener('resizingWidth',this.updateSizeAndPosition,false);
      this.text_wrapper.addEventListener('resizingHeight',this.updateSizeAndPosition,false);
      this.text_wrapper.addEventListener('scaleX',this.scaleX,false);
      this.text_wrapper.addEventListener('scaleY',this.scaleY,false);
      this.text_wrapper.addEventListener('rotateFinish',this.updateDegree,false);
      this.text_wrapper.addEventListener('click',this.selected,false);
      this.text_wrapper.addEventListener('touchstart',this.selected,false);

    },
  }

</script>

<style scoped>
.text-wrapper {
  position: absolute;
  display: inline-flex;
  flex-direction: column;
  /* justify-content: center;
  align-items: center; */
  transform-origin: center center;
}

.text-area {
  font-size: 30px;
  border: none;
  margin: 0;
  transform-origin: 0 0;
}
.text-area:hover {
  cursor: pointer;
  outline: 1px solid blue;
}
.text-area:focus {
  outline: 1px solid blue;
}

.is_active {
  outline : 1.5px solid blue;
}
.hidden {
  opacity: 0;
}


::-webkit-resizer {
    display: none;
}

</style>