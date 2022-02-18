<template>
  <div id="disp-tutorial-icon-wrapper" class="" v-on:click.stop="toggleTutorial">
    <i class="fas fa-question-circle fa-lg disp-tutorial-icon lightgrey"></i>
    <span class="sub-text">チュートリアル</span>
  </div>
</template>

<script>
  export default {
    data : ()=>{
      return {
        isShowTutorial : false,
      }
    },
    methods : {
      toggleTutorial(){
        if(this.isShowTutorial==false){
          this.showTutorial();
          this.isShowTutorial = true;
        } else if(this.isShowTutorial==true){
          this.hideTutorial();
          this.isShowTutorial = false;
        }
      },
      showTutorial(){
        const event = new CustomEvent('showTutorial');
        document.body.dispatchEvent(event);
      },
      hideTutorial(){
        const event = new CustomEvent('hideTutorial');
        document.body.dispatchEvent(event);
      },
    },
    created(){
      document.body.addEventListener('hideTutorialSelf',(e)=>{this.isShowTutorial=false});
    }
  }

</script>

<style scoped>

#disp-tutorial-icon-wrapper {
  margin: 0 10px;
  padding: 10px 5px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

#disp-tutorial-icon-wrapper:hover {
  cursor: pointer;
  background-color: rgba(255,255,255,0.2);
}

.sub-text {
  color:grey;
  font-size:10px;
  height:10px;
  margin-top:2px;
}

.lightgrey{
  color: lightgrey;
}

</style>