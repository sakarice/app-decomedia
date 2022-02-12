<template>
  <!-- Mediaテキスト-->
  <div :id="text_wrapper_with_index" class="obj text-wrapper"
  :class="{is_active : isActive, 'hover-blue' : getMode!=3}" :style="textWrapperStyle"
  @click.stop @mousedown.stop="moveTrigger($event)" @touchstart.stop="moveTrigger($event)">
    <p spellcheck="false" class="text-area"
    :id="text_with_index" :style="textStyle" @input="onChangeTextContent($event)">
    {{text_tmp}}
    </p>
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
        original_width : 100,
        original_height : 100,
        text_tmp : "default text",
        text_wrapper : "",
        text : "",
      }
    },
    computed : {
      ...mapGetters('media', ['getMode']),
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
      textWrapperStyle:function(){
        return {
          "transform" : "rotate(" + this.mediaText['degree'] + "deg)",
          "left" : this.mediaText['left'] + "px",
          "top" : this.mediaText['top'] + "px",
        }
      },
      textStyle:function(){
        return {
          "transform" : "scaleX(" + this.mediaText['scale_x'] + ")" + " scaleY(" + this.mediaText['scale_y'] + ")",
          "color" : this.mediaText['color'],
          "font-size" : this.mediaText['font_size'] + "px",
          "font-family" : this.mediaText['font_family'],
          "opacity" : this.mediaText['opacity'],
        }
      },
    },
    watch : {
      original_width(new_val){
        this.updateTextWidth();
        this.updateMediaTextsObjectItem({index:this.index,key:"width", value:new_val})
      },
      original_height(new_val){this.updateMediaTextsObjectItem({index:this.index,key:"height", value:new_val})},
      getMode(){ this.setEditable()},
    },
    methods : {
      ...mapMutations('selectedObjects', ['addSelectedObjectItem']),
      ...mapMutations('selectedObjects', ['deleteSelectedObjectItem']),
      ...mapMutations('mediaTexts', ['setTargetObjectIndex']),
      ...mapMutations('mediaTexts', ['updateMediaTextsObjectItem']),
      init(){
        if(this.getOneText()){
          this.setMyTextDataFromStore();
          this.setDataFromStoreData();
        }
      },
      setDomElement(){
        this.text_wrapper = document.getElementById(this.text_wrapper_with_index);
        this.text = document.getElementById(this.text_with_index);
      },
      setEditable(){
        if(this.getMode != 3){ // = createかeditモード
          this.text.contentEditable = true;
        } else { // =showモード
          this.text.contentEditable = false;
        }
      },
      getOneText(){ // ストアから自分のインデックスのオブジェクトだけ取得する
        this.setTargetObjectIndex(this.index);
        return this.getMediaText;
      },
      setMyTextDataFromStore(){
        this.mediaText = this.getOneText();
        // this.mediaText = Object.assign({}, this.getOneText(this.index));
      },
      updateStoreAndMyData(key,value){
        this.updateMediaTextsObjectItem({index:this.index,key:key,value:value});
        this.mediaText[key] =  value;
      },
      onChangeDegree(e){
        this.updateStoreAndMyData("degree", e.detail.degree);
      },
      onChangeTextContent(e){
        this.updateStoreAndMyData("text", e.target.textContent);
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
      selected(){
        const objectSelected = new CustomEvent('objectSelected',
        {detail:{
          type:2
          ,index:this.index
          ,degree:this.mediaText['degree']
          ,element_id:this.text_wrapper_with_index}});
        document.body.dispatchEvent(objectSelected);
      },
      // 位置操作用
      moveTrigger(e){
        if(this.getMode != 3){
          const move_target_dom = this.text_wrapper;
          moveStart(e, move_target_dom);
        }
      },
      moving(e){
        this.updateMediaTextsObjectItem({index:this.index,key:"left", value:e.detail.left})
        this.updateMediaTextsObjectItem({index:this.index,key:"top", value:e.detail.top})
        this.setMyTextDataFromStore();
      },
      // リサイズ用
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
        const e = event.detail;
        const diff = e.diff_x;
        const start_width = e.resize_start_infos['width'];
        const start_left = e.resize_start_infos['left'];

        this.original_width = (start_width + diff) / this.mediaText['scale_x'];

      // オブジェクトの左辺リサイズ時のみleftを更新
        const resize_side = e.resize_side['x'] > 0 ? "right" : "left";
        if(e.resize_side['x'] < 0){ // =左辺のリサイズ
          this.updateStoreAndMyData("left",start_left - diff);
        }
      },
      scale(event){
        // スケール率を計算(※↓はheightを元に計算しているが、縦横の比率固定のため、計算には縦横どちらを使ってもよい)
        const e = event.detail;
        const start_infos = event.detail.resize_start_infos;
        const new_width = start_infos["width"] + e.diff_x;
        const new_scale = new_width / this.original_width;
      // 座標の微調整(左か上辺でリサイズした場合は位置の調整が必要)
        const new_left = e.resize_side['x'] == -1 ? start_infos['left'] - e.diff_x : start_infos['left'];
        const scale_diff_y = this.original_height * new_scale - start_infos["height"];
        const new_top = e.resize_side['y'] == -1 ? start_infos['top'] - scale_diff_y : start_infos['top'];
        const updateStyleValues = {"left":new_left,"top":new_top, "scale_x":new_scale, "scale_y":new_scale}
      // storeの更新
        Object.keys(updateStyleValues).forEach((key)=>{
          this.updateStoreAndMyData(key,updateStyleValues[key]);
        })
        this.updateTextWrapperStyle();
      },
      getTextInitialSize(){
        // 初期テキストの横幅取得用要素から横幅を取得し要素を削除
        const dummy_text_dom = document.createElement('p');
        Object.keys(this.textStyle).forEach((key)=>{
          dummy_text_dom.style[key] = this.textStyle[key];
        })
        dummy_text_dom.style.display = "inline-block"; // 幅を中味のテキストに合わせるため
        dummy_text_dom.textContent = this.text_tmp;
        this.text_wrapper.after(dummy_text_dom);
        const width = dummy_text_dom.offsetWidth;
        const height = dummy_text_dom.offsetHeight;
        dummy_text_dom.remove();
        return {"width":width, "height":height};
      },
      setTextBoxInitialSize(){
        const initial_size = this.getTextInitialSize();
        this.original_width = initial_size["width"] + 1;
        this.original_height = initial_size["height"] + 1;
      },
      setDataFromStoreData(){
        this.text_tmp = this.mediaText['text'];
        this.original_width = this.mediaText['width'];
        this.original_height = this.mediaText['height'];
      },
      updateTextWidth(){this.text.style.width = this.original_width+ "px";},
      updateTextWrapperStyle(){
        this.text_wrapper.style.width = this.original_width * this.mediaText['scale_x'] + "px";
        this.text_wrapper.style.height = this.original_height * this.mediaText['scale_y'] + "px";
      },
    },
    created(){
      this.setMyTextDataFromStore();
      this.text_tmp = this.mediaText["text"];
    },
    mounted(){
      this.setDomElement();
      // DOMの描画終了を待つ
      // this.text.contentEditable = true;
      this.setEditable();

      this.$nextTick(function(){
        this.setTextBoxInitialSize();

        const textResizeObserver = new ResizeObserver(entrys=>{
          entrys.forEach((entry)=>{
            const rect = entry.contentRect;
            this.original_height = rect["height"];
            this.updateTextWrapperStyle();
          })
        });
        textResizeObserver.observe(this.text);
      });

      this.text_wrapper.addEventListener('mousedown',this.moveTrigger,false);
      this.text_wrapper.addEventListener('touchstart',this.moveTrigger,false);
      this.text_wrapper.addEventListener('touchstart',this.selected,false);
      this.text_wrapper.addEventListener('click',this.selected,false);
      this.text_wrapper.addEventListener('moving',this.moving,false);
      this.text_wrapper.addEventListener('resize',this.resizeStart,false);
      this.text_wrapper.addEventListener('rotateObject',this.onChangeDegree,false);

    },
  }

</script>

<style scoped>
@import "/resources/css/mediaObjectCommon.css";
@import "/resources/css/flexSetting.css";

.text-area:focus{
  outline:solid 2px #ff6a00;
}

.focus-trigger{
  margin-top: 10px;
  margin-left: -10px;
}
.focus-trigger:hover{
  cursor:pointer
}


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