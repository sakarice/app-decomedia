<template>
  <div id="judge-device-type"></div>
</template>

<script>

  export default {
    props : [],
    data : () => {
      return {
        // 0:PC, 1:TABLET, 2:MOBILE
        deviceType : 0,
      }
    },
    methods : {
      judgeDeviceType(){
        const window_width = window.innerWidth;
        if(window_width < 481){
          this.deviceType = 2; // =モバイル
        } else if(window_width < 780){
          this.deviceType = 1; // =タブレット
        } else {
          this.deviceType = 0; // =PC
        }
      },      
    },
    created(){
      this.judgeDeviceType();
      window.addEventListener('resize',this.judgeDeviceType, false);
    },

  }

</script>



<style scoped>
</style>