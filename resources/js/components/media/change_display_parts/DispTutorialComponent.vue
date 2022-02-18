<template>
  <div id="disp-tutorial-icon-wrapper" class="icon-wrapper" v-on:click.stop="showTutorial">
    <i class="fas fa-question-circle fa-lg disp-tutorial-icon lightgrey"></i>
  </div>
</template>

<script>
  export default {
    methods : {
      showTutorial(){
        const event = new CustomEvent('showTutorial');
        console.log("showTutorial");
        document.body.dispatchEvent(event);
      }
    }
  }

</script>

<style scoped>

#disp-tutorial-icon-wrapper {
  margin: 0 5px;
}

.lightgrey{
  color: lightgrey;
}

</style>