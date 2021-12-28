<template>
  <!-- Media図形-->
  <div :id="text_wrapper_with_index" class="obj text-item-wrapper"
  @click.stop @touchstart.stop>
    <p contenteditable spellcheck="false"
    class="text-area"
    @mousedown="moveStart" @touchstart="moveStart">
      aaa
    </p>

    <object-resize v-show="isEditMode" :index="index" :id="resize_wrapper_with_index" :class="{hidden:!isActive}"
     @move="moveStart($event)">
    </object-resize>
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
        isReDraw : false,
        isResizing :false,
        text_wrapper : "",
        textDatas : {
        }
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

    },
    watch : {
      isActive(val){
        if(val==true){
          const resize_wrapper = document.getElementById(this.resize_wrapper_with_index);
          resize_wrapper.style.width = this.text_wrapper.style.width;
          resize_wrapper.style.height = this.text_wrapper.style.height;
        }
      }
    },
    methods : {
      ...mapMutations('selectedObjects', ['addSelectedObjectItem']),
      ...mapMutations('selectedObjects', ['deleteSelectedObjectItem']),
      ...mapMutations('mediaTexts', ['setTargetObjectIndex']),
      ...mapMutations('mediaTexts', ['updateMediaTextsObjectItem']),
      getOneText(index){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(index);
        return this.getMediaText;
      },
      textWrapperStyle(){
        const styleObject = {
          // "left" : this.text_wrapper.style.left,
          // "top" : this.text_wrapper.style.top,
          // "width" : this.text_wrapper.style.width,
          // "height" : this.text_wrapper.style.height,
          // "width" : "200px",
          // "height" : "200px",
        }
        return styleObject;
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
        const storeData = this.getOneText(this.index);
        if(!(typeof storeData === "undefined")){
          for(let key of Object.keys(storeData)){
            this.textDatas[key] = this.fixDataType(key, storeData[key]);
          }
        }
      },
      updateSizeAndPosition(event){
        const data = event.detail;
        const keys = ["width","height","left","top"];
        keys.forEach(key=>{
          if(data[key]){
            // this.updateMediaTextsObjectItem({index:this.index,key:key,value:data[key]});
            this.text_wrapper.style[key] = data[key] + "px";
          }
        });
      },
      // 位置操作用
      moveStart(e){
        const move_target_dom = this.text_wrapper;
        moveStart(e, move_target_dom);
        move_target_dom.addEventListener('moveFinish', this.moveEnd, false);
      },
      moveEnd(e){
        e.target.removeEventListener('moveFinish', this.moveEnd, false);
      },
      updateDegree(event){
        const new_degree = event.detail.degree;
        // this.updateMediaTextsObjectItem({index:this.index,key:"degree",value:new_degree});
      },
      selected(){
        const objectSelected = new CustomEvent('objectSelected',
        {detail:{type:3,index:this.index,element_id:this.text_wrapper_with_index}});
        document.body.dispatchEvent(objectSelected);
      },
      updateDegree(new_degree){ this.textDatas['degree'] = new_degree },
    },
    created(){},
    mounted(){
      this.text_wrapper = document.getElementById(this.text_wrapper_with_index);
      // イベント登録
      // this.text_wrapper.addEventListener('textDataUpdated',this.init,false);
      this.text_wrapper.addEventListener('resizingWidth',this.updateSizeAndPosition,false);
      this.text_wrapper.addEventListener('resizingHeight',this.updateSizeAndPosition,false);
      this.text_wrapper.addEventListener('rotateFinish',this.updateDegree,false);
      this.text_wrapper.addEventListener('click',this.selected,false);
      this.text_wrapper.addEventListener('touchstart',this.selected,false);

    },
  }

</script>

<style scoped>
.text-item-wrapper {
  position: absolute;
  display: inline-flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  transform-origin: center center;
}

.text-area {
  font-size: 30px;
  border: none;
  margin: 0;
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