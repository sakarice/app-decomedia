<template>
  <div id="object-setting-open-wrapper" class="flex a-center j-center"
  @click.stop @touchstart.stop>
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

#object-setting-open-wrapper {
  margin: 0 10px;
}

</style>