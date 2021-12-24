<template>
  <div id="object-setting-open-wrapper" class="flex a-center j-center"
  @click="showObjectSetting()" @click.stop @touchstart.stop>
    <i class="fas fa-edit fa-lg"></i>
  </div>
</template>

<script>
  import { mapGetters } from 'vuex';

  export default {
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
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";

#object-setting-open-wrapper {
  padding: 10px 12px;
}
#object-setting-open-wrapper:hover {
  background-color: rgba(255,255,255,0.2);
}

</style>