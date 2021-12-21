<template>
  <div id="delete-wrapper" class="flex a-center j-center">
    <i class="fas fa-trash fa-2x del-icon" @click="deleteObject()"></i>
  </div>
</template>

<script>
  import { mapGetters, mapMutations } from 'vuex';

  export default {
    data : ()=>{
      return {
        delMutations : [],
      }
    },
    computed : {
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
    },
    methods : {
      ...mapMutations('mediaFigures', ['deleteMediaFiguresObjectItem']),
      ...mapMutations('mediaImgs', ['deleteMediaImgsObjectItem']),
      ...mapMutations('selectedObjects', ['unSelectedAll']),
      deleteObject(){
        const objs = this.getSelectedObjects;
        objs.forEach(obj=>{
          this.delMutations[obj.type]();
        });
        this.unSelectedAll(); // 削除対象を格納したstoreの配列を空に
      }
    },
    created(){
      this.delMutations[0] = this.deleteMediaFiguresObjectItem; // 0
      this.delMutations[1] = this.deleteMediaImgsObjectItem;    // 1
    },
    mounted(){},
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";

#delete-wrapper {
}


</style>