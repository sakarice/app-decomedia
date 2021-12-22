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
        const delObjs = JSON.parse(JSON.stringify(this.getSelectedObjects));
        // const delObjs = Object.assign({},this.getSelectedObjects);
        delObjs.forEach(obj=>{
          this.delMutations[obj.type](obj.index);
        })
        // Object.keys(delObjs).forEach(key=>{
        //   this.delMutations[delObjs[key].type](delObjs[key].index);
        // });
        this.unSelectedAll(); // 削除対象を格納したstoreの配列を空に
        const event = new CustomEvent('objectDeleted', {detail:{objs:delObjs}});
        document.body.dispatchEvent(event);
      }
    },
    created(){
      this.delMutations[0] = this.deleteMediaFiguresObjectItem; // 0
      this.delMutations[1] = this.deleteMediaImgsObjectItem;    // 1

      document.addEventListener('keydown', (e)=>{
        if(e.code=="Delete"){
          this.deleteObject();
        }
      });
    },
    mounted(){},
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";

#delete-wrapper {
  margin: 0 10px;
}


</style>