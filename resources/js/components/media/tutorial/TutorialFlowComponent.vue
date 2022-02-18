
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

        <!-- 説明用モーダル -->
        <div id="tutorial-modal">
          <!-- 上部のヘッダ -->
          <div class="tutorial--modal--header">
            <div class="page-wrapper">
              <span class="page-index">{{page}}</span>
            </div>
            <p class="description-title">title</p>
          </div>

          <!-- 補足用画像 -->
          <div class="tutorial--img--wrapper">
            <img class="tutorial--img" src="https://cdn.pixabay.com/photo/2018/09/17/09/47/pixel-3683374_960_720.png" :alt="page">
          </div>

          <!-- 説明 -->
          <div class="tutorial--description--wrapper">
            <p class="description">this is tutorial {{page}}</p>
          </div>
        </div>

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

  export default {
    components : {
      overlay,
    },
    props : [
      'message',
    ],
    data : () => {
      return {
        isShowTutorial : false,
        page : 1,
      }
    },
    methods : {
      hideTutorial(){
        this.isShowTutorial = false;
        const hideOverLay = new CustomEvent('hideOverLay');
        document.body.dispatchEvent(hideOverLay);
      },
      back(){
        this.page = this.page>1 ? this.page-1 : this.page;
        console.log(this.page);
      },
      next(){
        this.page = this.page<7 ? this.page+1 : this.page;
        console.log(this.page);
      }
    },
    created(){},
    mounted(){
      document.body.addEventListener('showTutorial', (e)=>{
        this.isShowTutorial = true;
        const showOverLay = new CustomEvent('showOverLay');
        document.body.dispatchEvent(showOverLay);
      });
    }

  }

</script>



<style scoped>

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
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.close-icon-wrapper {
  position: absolute;
  top: -50px;
  left: 0;
  display: inline-block;
  padding: 10px 15px;
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

#tutorial-modal {
  width: 320px;
  height: 380px;
  /* background-color: rgba(50,50,80,0.9); */
  background-color: white;
  display: flex;
  flex-direction: column;
  align-items: center;
}

#tutorial-modal .tutorial--modal--header {
  display: flex;
  align-items: center;
  width: 100%;
}

.page-wrapper {
  background: linear-gradient(-225deg,
  black, black 50%, white 50%, white);
  width: 50px;
  height: 50px;
}

.page-index {
  color: skyblue;
  font-size: 20px;
  margin-left: 3px;
}

.description-title {
  margin: 0;
  font-size: 18px;
}

#tutorial-modal .tutorial--img--wrapper {
  width: 90%;
  height: 40%;
  padding: 10px;
}

.tutorial--img {
  width: 100%;
  height: 100%;
}



.red {color: red;}
.white {color:white;}


@-webkit-keyframes gradation {
  0% { color: #ff1493; }
  50% { color: #7fff00; }
  100% { color: #ff1493; }
}
@keyframes gradation {
  0% { color: #ff1493; }
  50% { color: #7fff00; }
  100% { color: #ff1493; }
}



/* モーダル表示アニメーション */
.flowup-enter-active, .flowup-leave-active {
  opacity: 1;
  transform: translate(0px, 0px);
  transition: all 150ms;
}

.flowup-enter, .flowup-leave-to {
  opacity: 0;
  transform: translateY(20px);
}


</style>