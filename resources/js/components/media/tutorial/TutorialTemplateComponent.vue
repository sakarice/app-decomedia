
<template>
  <!-- <transition name="flowup" v-show="isShowTutorial"> -->
    <div id="tutorial-field" v-show="isShowTutorial">
      <div id="tutorial-contents-wrapper">
        <div class="close-icon-wrapper" @click="hideTutorial">
          <i class="fas fa-times fa-lg red"></i>
        </div>
      </div>
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
      }
    },
    methods : {
      hideTutorial(){
        this.isShowTutorial = false;
        const hideOverLay = new CustomEvent('hideOverLay');
        document.body.dispatchEvent(hideOverLay);
      },
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
}

.close-icon-wrapper {
  padding: 10px 15px;
  background-color: rgba(0,0,0,0.7);
  border-radius: 50%;
}

.close-icon-wrapper:hover {
  cursor: pointer;
  background-color: rgba(40,40,40,1);
}

.red {
  color: red;
}


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