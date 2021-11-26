
<template>
  <transition name="flowup">
    <div id="loading-disp-background">
      <div id="loading-disp-wrapper">
        <!-- <i class="far fa-lightbulb fa-3x loading-icon"></i> -->
        <span class="material-icons-outlined">emoji_objects</span>
        <div class="loading-message">
          <span v-if="message">{{message}}</span>
          <span v-else>処理中です...</span>
        </div>
      </div>
    </div>

  </transition>
</template>

<script>

  export default {
    props : [
      'message',
    ],
    data : () => {
      return {}
    },
    methods : {},
  }

</script>



<style scoped>

#loading-disp-background {
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

#loading-disp-wrapper {
  /* flex設定 */
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
}

.loading-icon {
  color : aquamarine;
  animation : gradation 3s infinite;
  -webkit-animation : gradation 3s infinite;
}

.loading-message {
  margin-top: 5px;
  color : white;
  font-size: 25px;
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