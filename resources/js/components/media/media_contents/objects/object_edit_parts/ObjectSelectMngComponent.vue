<template>
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
    },
    methods : {
      ...mapMutations('selectedObjects', ['unSelectedAll']),
      ...mapMutations('selectedObjects', ['addSelectedObjectItem']),
      onObjedctSelected(event){
        this.unSelectedAll();
        const type = event.detail.type;
        const index = event.detail.index;
        const objTypeAndIndex = {type:type, index:index};
        this.addSelectedObjectItem(objTypeAndIndex);
      }
    },
    created(){
      document.body.addEventListener('fieldClicked', this.unSelectedAll, false);
      document.body.addEventListener('objectSelected', this.onObjedctSelected, false);      
    },
  }

</script>

<style scoped>
</style>