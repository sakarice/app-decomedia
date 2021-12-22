<template>
  <div id="object-setting-open-wrapper" class="flex a-center j-center">
    <i class="fas fa-edit fa-2x" @click="showObjectSetting()"></i>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    data : ()=>{
      return {
      }
    },
    computed : {
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      firstSelectedObject(){
        return this.getSelectedObjects[0];
      }
    },
    methods : {
      ...mapMutations('mediaFigures', ['deleteMediaFiguresObjectItem']),
      ...mapMutations('mediaImgs', ['deleteMediaImgsObjectItem']),
      ...mapMutations('selectedObjects', ['unSelectedAll']),
      showObjectSetting(){
        const obj = this.firstSelectedObject;
        let event;
        switch (obj.type) {
          case 0: 
            event = new CustomEvent('showFigureSetting', {detail:{index:obj.index}});
            break;
          case 1:
            event = new CustomEvent('showImgSetting', {detail:{index:obj.index}});
            break;
        }
        document.body.dispatchEvent(event);
      }
    },
    created(){
    },
    mounted(){},
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";


</style>