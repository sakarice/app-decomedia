<template>
  <!-- Media図形-->
  <div :id="text_wrapper_with_index" class="obj text-wrapper"
  :class="{is_active : isActive}"
  @click.stop @mousedown.stop="moveTrigger($event)" @touchstart.stop="moveTrigger($event)">
    <p contenteditable spellcheck="false" class="text-area"
    :id="text_with_index" :style="textStyle" @input="updateText($event)">
    {{text_tmp}}
    </p>
    <!-- ↓初期描画時にテキストの横幅を取得するための一時的な要素↓ -->
    <p id="tmp-dummy-text">{{text_tmp}}</p>
    
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
        mediaText : "",
        isReDraw : false,
        isResizing :false,
        original_width : 100,
        original_height : 100,
        width : 100,
        height : 100,
        scale_x_and_y : 1,
        text_wrapper : "",
        text_tmp : "default text",
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
      textStyle:function(){
        const textStyle = {
          "max-width" : this.original_width + "px",
          "transform" : "scaleX(" + this.mediaText['scale_x'] + ")" + " scaleY(" + this.mediaText['scale_y'] + ")",
        }
        return textStyle;
      },
    },
    watch : {
      scale_x_and_y(new_val){
        this.updateMediaTextsObjectItem({index:this.index,key:"scale_x", value:new_val})
        this.updateMediaTextsObjectItem({index:this.index,key:"scale_y", value:new_val})
      },
      original_width(new_val){this.updateMediaTextsObjectItem({index:this.index,key:"original_width", value:new_val})},
      original_height(new_val){this.updateMediaTextsObjectItem({index:this.index,key:"original_height", value:new_val})},
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
      initTextData(){
        this.mediaText = Object.assign({}, this.getOneText(this.index));
      },
      updateText(e){
        const new_text = e.target.textContent;
        this.updateMediaTextsObjectItem({index:this.index,key:"text",value:new_text});
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
      resizeStart(event){
        const x = event.detail.resize_side['x'];
        const y = event.detail.resize_side['y'];
        if(x==0 || y==0){ // 縦か横どちらか(斜め方向の変化なし))
          if(x != 0){ // x軸方向の変化
            this.resizeX(event);
          } else if(y != 0){ // y軸方向の変化
          }
        } else { // 斜め方向の変化
          this.scale(event);
        }
      },
      resizeX(event){
      // 横幅
        const diff = event.detail.diff_x;
        const start_width = event.detail.resize_start_infos['width'];
        const start_left = event.detail.resize_start_infos['left'];
        this.original_width = (start_width + diff) / this.scale_x_and_y;
      // オブジェクトの左辺リサイズ時のみleftを更新
        const resize_side = event.detail.resize_side['x'] > 0 ? "right" : "left";
        if(resize_side == "left"){
          const new_left = start_left - diff;
          this.updateMediaTextsObjectItem({index:this.index,key:"left", value:new_left})
        }
      // dataを更新
        this.initTextData();
        this.updateTextWrapperStyle();
      },
      scale(event){
        // スケール率を計算(※↓はheightを元に計算しているが、縦横の比率固定のため、計算には縦横どちらを使ってもよい)
        const e = event.detail;
        const start_infos = event.detail.resize_start_infos;
        this.width = start_infos["width"] + e.diff_x;
        this.height = start_infos["height"] + e.diff_y;
        this.scale_x_and_y = this.width / this.original_width;
        let updateStyleValues = {"left":0,"top":0}
      // 座標の微調整(左か上辺でリサイズした場合は位置の調整が必要)
        const current_left = start_infos['left'];
        const current_top = start_infos['top'];
        updateStyleValues["left"] = e.resize_side['x'] == -1 ? current_left - e.diff_x : current_left;
        const scale_diff_y = this.original_height * this.scale_x_and_y - start_infos["height"];
        updateStyleValues["top"] = e.resize_side['y'] == -1 ? current_top - scale_diff_y : current_top;
      // storeの更新
        Object.keys(updateStyleValues).forEach((key)=>{
          this.updateMediaTextsObjectItem({index:this.index,key:key, value:updateStyleValues[key]})
        })
        this.initTextData();
        this.updateTextWrapperStyle();
      },
      // 位置操作用
      moveTrigger(e){
        const move_target_dom = this.text_wrapper;
        moveStart(e, move_target_dom);
      },
      moving(e){
        this.updateMediaTextsObjectItem({index:this.index,key:"left", value:e.detail.left})
        this.updateMediaTextsObjectItem({index:this.index,key:"top", value:e.detail.top})
        this.initTextData();
      },
      updateTextWrapperStyle(){
        this.text_wrapper.style.left = this.getMediaText["left"] + "px";
        this.text_wrapper.style.top = this.getMediaText["top"] + "px";
        this.text_wrapper.style.width = this.original_width * this.scale_x_and_y + "px";
        this.text_wrapper.style.height = this.original_height * this.scale_x_and_y + "px";
      },
      updateDegree(event){
        const new_degree = event.detail.degree;
        this.updateMediaTextsObjectItem({index:this.index,key:"degree",value:new_degree});
      },
      selected(){
        const objectSelected = new CustomEvent('objectSelected',
        {detail:{
          type:2
          ,index:this.index
          ,element_id:this.text_wrapper_with_index}});
        document.body.dispatchEvent(objectSelected);
      },
    },
    created(){
      this.initTextData();
      this.text_tmp = this.mediaText["text"];
    },
    mounted(){
      this.text_wrapper = document.getElementById(this.text_wrapper_with_index);
      this.text = document.getElementById(this.text_with_index);

      // 初期テキストの横幅取得用要素から横幅を取得し要素を削除
      const dummy_text = document.getElementById('tmp-dummy-text');
      const dummy_text_width = dummy_text.offsetWidth;
      const dummy_text_height = dummy_text.offsetHeight;
      dummy_text.remove();

      // DOMの描画終了を待つ
      this.$nextTick(function(){
        this.original_width = dummy_text_width + 1;
        this.original_height = dummy_text_height + 1;
        this.width = this.original_width;
        this.height = this.original_height;
        this.updateTextWrapperStyle();

        const resizeObserver = new ResizeObserver(entrys=>{
          entrys.forEach((entry)=>{
            const rect = entry.contentRect;
            // this.original_height = rect["height"];
            // this.updateTextWrapperStyle();
          })
        });
        resizeObserver.observe(this.text);
      });

      this.text_wrapper.addEventListener('mousedown',this.moveTrigger,false);
      this.text_wrapper.addEventListener('touchstart',this.moveTrigger,false);
      this.text_wrapper.addEventListener('touchstart',this.selected,false);
      this.text_wrapper.addEventListener('click',this.selected,false);
      this.text_wrapper.addEventListener('moving',this.moving,false);
      this.text_wrapper.addEventListener('resize',this.resizeStart,false);
      this.text_wrapper.addEventListener('rotateObject',this.updateDegree,false);

    },
  }

</script>

<style scoped>
@import "/resources/css/mediaObjectCommon.css";
@import "/resources/css/flexSetting.css";

.text-wrapper {
  position: absolute;
  transform-origin: center center;
}

textarea {
  overflow: hidden;
}

.text-area {
  display: inline-block;
  box-sizing: border-box;
  width: 100%;
  border: none;
  margin: 0;
  transform-origin: 0 0;
  resize: none;
  padding: 0px;
  word-wrap: break-word;
  overflow-wrap: break-word;
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