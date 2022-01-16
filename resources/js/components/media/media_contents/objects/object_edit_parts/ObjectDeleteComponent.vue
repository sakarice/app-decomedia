<template>
  <div id="delete-wrapper" class="p20 flex a-center j-center"
  @click="deleteObject()" @click.stop @touchstart.stop>
    <i class="fas fa-trash fa-lg del-icon"></i>
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
      ...mapMutations('mediaTexts', ['deleteMediaTextsObjectItem']),
      ...mapMutations('selectedObjects', ['unSelectedAll']),
      deleteObject(){
        const delObjs = JSON.parse(JSON.stringify(this.getSelectedObjects));
        delObjs.forEach(obj=>{
          this.delMutations[obj.type](obj.index);
        })
        this.unSelectedAll(); // 削除対象を格納したstoreの配列を空に
        const event = new CustomEvent('objectDeleted', {detail:{objs:delObjs}});
        document.body.dispatchEvent(event);
      }
    },
    created(){
      this.delMutations[0] = this.deleteMediaFiguresObjectItem; // 0
      this.delMutations[1] = this.deleteMediaImgsObjectItem;    // 1
      this.delMutations[2] = this.deleteMediaTextsObjectItem;    // 2

      document.addEventListener('keydown', (e)=>{
        if(e.code=="Delete"){
          this.deleteObject();
        }
      });
    },
  }

</script>

<style scoped>

@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";

#delete-wrapper:hover {
  background-color: rgba(255,255,255,0.2);
}


</style>