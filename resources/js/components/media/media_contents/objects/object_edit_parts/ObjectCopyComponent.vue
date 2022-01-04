<template>
  <div id="copy-wrapper" class="flex a-center j-center"
  @click="copyAndPaste()" @click.stop @touchstart.stop>
    <i class="fas fa-copy fa-lg copy-icon"></i>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';

  const randMinNum = 10;
  const randMaxNum = 20;

  export default {
    data : ()=>{
      return {
        copyFigures : [],
        copyImgs : [],
        copyTexts : [],
      }
    },
    computed : {
      ...mapGetters('mediaFigures', ['getMediaFigure']),
      ...mapGetters('mediaImgs', ['getMediaImg']),
      ...mapGetters('mediaTexts', ['getMediaText']),
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
    },
    methods : {
      setTargetFigureIndex(index){ this.$store.commit('mediaFigures/setTargetObjectIndex', index)},
      ...mapMutations('mediaFigures', ['addMediaFiguresObjectItem']),
      setTargetImgIndex(index){ this.$store.commit('mediaImgs/setTargetObjectIndex', index)},
      ...mapMutations('mediaImgs', ['addMediaImgsObjectItem']),
      setTargetTextIndex(index){ this.$store.commit('mediaTexts/setTargetObjectIndex', index)},
      ...mapMutations('mediaTexts', ['addMediaTextsObjectItem']),

      ...mapMutations('selectedObjects', ['unSelectedAll']),
      copyObject(){
        this.resetCopyTargetInfo();
        const copyObjInfos = JSON.parse(JSON.stringify(this.getSelectedObjects));
        copyObjInfos.forEach(objInfo=>{
          switch(objInfo.type) {
            case 0 : // 図形
              this.setTargetFigureIndex(objInfo.index);
              const figureData = JSON.parse(JSON.stringify(this.getMediaFigure));
              figureData['top'] += this.getRandNum(randMinNum, randMaxNum);
              figureData['left'] += this.getRandNum(randMinNum, randMaxNum);
              this.copyFigures.push(figureData);
              break;
            case 1 : // 画像
              this.setTargetImgIndex(objInfo.index);
              const imgData = JSON.parse(JSON.stringify(this.getMediaImg));
              imgData['top'] += this.getRandNum(randMinNum, randMaxNum);
              imgData['left'] += this.getRandNum(randMinNum, randMaxNum);
              this.copyImgs.push(imgData);
              break;
            case 2 : // テキスト
              this.setTargetTextIndex(objInfo.index);
              const textData = JSON.parse(JSON.stringify(this.getMediaText));
              textData['top'] += this.getRandNum(randMinNum, randMaxNum);
              textData['left'] += this.getRandNum(randMinNum, randMaxNum);
              this.copyTexts.push(textData);
              break;
          }
        })
      },
      pasteObject(){
        this.copyFigures.forEach(figure=>{
          this.addMediaFiguresObjectItem(figure);
        })
        this.copyImgs.forEach(figure=>{
          this.addMediaImgsObjectItem(figure);
        })
        this.copyTexts.forEach(text=>{
          this.addMediaTextsObjectItem(text);
        })
      this.resetCopyTargetInfo();
      },
      copyAndPaste(){
        this.copyObject();
        this.pasteObject();
      },
      resetCopyTargetInfo(){
        this.copyFigures.length = 0;
        this.copyImgs.length = 0;
        this.copyTexts.length = 0;
      },
      getRandNum(min,max){
        return min + Math.floor(Math.random() * (max - min));
      }

    },
    created(){
      // ctrl+C, ctrl+Vのショートカットキー用にキーボードイベント作成
      document.addEventListener('keydown', (e)=>{
        if(e.ctrlKey){
          switch (e.code) {
            case "KeyC" :
              this.copyObject();
              break;
            case "KeyV" :
              this.pasteObject();
              break;
          }
        }
      });
    },
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";

#copy-wrapper {
  padding: 10px 12px;
}
#copy-wrapper:hover {
  background-color: rgba(255,255,255,0.2);
}


</style>