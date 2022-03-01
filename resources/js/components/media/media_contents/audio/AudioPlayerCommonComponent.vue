<template>
  <div></div>
</template>
<script>
  export default {
    // name : "AudioPlayerCommon",
    data : () => {
      return {
        text : "abc",
      }
    },
    methods : {
      display(){ console.log('called') },
    },
    created(){
      this.display();
    },
    // render(){
    //   return this.$scopedSlots.default({});
    // },
  }

</script>