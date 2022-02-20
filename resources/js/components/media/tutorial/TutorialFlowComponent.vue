
<template>
  <!-- <transition name="flowup" v-show="isShowTutorial"> -->
    <div id="tutorial-field" v-show="isShowTutorial">
      <div id="tutorial-contents-wrapper">

        <!-- 閉じるアイコン -->
        <div class="close-icon-wrapper" @click="hideTutorial">
          <i class="fas fa-times fa-2x red"></i>
        </div>

        <!-- 戻る -->
        <div class="back-wrapper" @click="back">
          <i class="fas fa-chevron-left fa-2x white"></i>
        </div>

      <ul id="tutorial-pages">
        <li class="tutorial-page" v-for="(tutorial_info, index) in tutorial_infos" :key="index">
          <tutorial-template
          v-show="page==index+1"
          :page="index+1"
          :tutorialInfo="tutorial_infos[index]">
          </tutorial-template>
        </li>
      </ul>

        <!-- 進む -->
        <div class="next-wrapper" @click="next">
          <i class="fas fa-chevron-right fa-2x white"></i>
        </div>
      </div>
      <overlay></overlay>
    </div>
  <!-- </transition> -->
</template>

<script>
import overlay from '../../common/OverlayComponent.vue';
import tutorialTemplate from './TutorialTemplateComponent.vue';
import {mapGetters} from 'vuex';


  export default {
    components : {
      overlay,
      tutorialTemplate,
    },
    props : [],
    data : () => {
      return {
        isShowTutorial : false,
        page : 1,
        showOverLayEvent : new CustomEvent('showOverLay'),
        hideOverLayEvent : new CustomEvent('hideOverLay'),
        hideTutorialSelfEvent : new CustomEvent('hideTutorialSelf'),
      }
    },
    computed :{
      tutorial_1:function(){ return this.$store.getters['tutorialInfo_1/getTutorialInfo']},
      tutorial_2:function(){ return this.$store.getters['tutorialInfo_2/getTutorialInfo']},
      tutorial_3:function(){ return this.$store.getters['tutorialInfo_3/getTutorialInfo']},

      // 各ページ毎のチュートリアルの情報を1つの配列にまとめる
      tutorial_infos:function(){
        let infosArray = [];
        infosArray.push(
          this.tutorial_1,
          this.tutorial_2,
          this.tutorial_3,
        );
        return infosArray;
      },
      tutorial_info_num:function(){ return this.tutorial_infos.length},
    },
    methods : {
      showTutorial(){
        this.isShowTutorial = true;
        document.body.dispatchEvent(this.showOverLayEvent);
      },
      hideTutorial(){
        this.isShowTutorial = false;
        document.body.dispatchEvent(this.hideOverLayEvent);
        document.body.dispatchEvent(this.hideTutorialSelfEvent);
      },
      back(){
        this.page = this.page>1 ? this.page-1 : this.page;
      },
      next(){
        this.page = this.page<this.tutorial_info_num ? this.page+1 : this.page;
      }
    },
    created(){
    },
    mounted(){
      document.body.addEventListener('showTutorial', (e)=>{
        this.showTutorial();
      });
      document.body.addEventListener('hideTutorial', (e)=>{
        this.hideTutorial();
      });

      document.addEventListener('keydown', (e)=>{
        if(e.keyCode==27){
          this.hideTutorial();
        }
      })
    }

  }

</script>



<style scoped>

ul, li {
  margin:0;
  padding:0;
  list-style: none;
}


#tutorial-field {
  z-index: 20;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  /* background-color: rgba(0,0,0,0.5); */

  /* flex設定 */
  display: flex;
  align-items: center;
  justify-content: center;
}

#tutorial-contents-wrapper {
  z-index: 11;
  position: relative;
  margin-top: 80px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.close-icon-wrapper {
  position: absolute;
  top: -50px;
  left: 0;
  display: inline-block;
  padding: 10px 16px 8px 16px;
  /* background-color: rgba(0,0,0,0.7); */
  border-radius: 50%;
}

.close-icon-wrapper:hover {
  cursor: pointer;
  background-color: rgba(40,40,40,1);
}

.back-wrapper, .next-wrapper {
  margin: 7px;
  padding: 100px 30px;
  border-radius: 3px;
}

.back-wrapper:hover{
  background-color: rgba(100,100,100,0.1);
}
.next-wrapper:hover{
  background-color: rgba(100,100,100,0.1);
}

#tutorial-pages {
  position:relative;  
  display: flex;
  width: 300px;
  height: 400px;
}

.tutorial-page {
  position: absolute;
  width: 100%;
  height: 100%;
}


.red {color: red;}
.white {color:white;}


/* 
@-webkit-keyframes gradation {
  0% { color: #ff1493; }
  50% { color: #7fff00; }
  100% { color: #ff1493; }
}
@keyframes gradation {
  0% { color: #ff1493; }
  50% { color: #7fff00; }
  100% { color: #ff1493; }
} */



</style>