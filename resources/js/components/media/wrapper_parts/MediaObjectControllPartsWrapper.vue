<template>
  <div id="media-object-controll-parts-wrapper" class="flex a-center j-center"
  v-show="isObjectSelected">
    <slot></slot>
  </div>
</template>

<script>

import { mapGetters } from 'vuex';

  export default {
    components : {
    },
    data : ()=>{
      return {
      }
    },
    computed : {
      ...mapGetters('selectedObjects', ['getSelectedObjects']),
      isObjectSelected(){
        return (this.getSelectedObjects.length > 0 ? true : false);
      }
    },
    methods : {
      // onObjectSelected(){ this.isObjectSelected = true; },
      // offObjectSelected(){ this.isObjectSelected = false; },
    },
    created(){
      // document.body.addEventListener('onObjectSelected', this.onObjectSelected, false);
      // document.body.addEventListener('offObjectSelected', this.offObjectSelected, false);
    }
  }

</script>

<style scoped>
@import "/resources/css/flexSetting.css";

#media-object-controll-parts-wrapper {
  position: absolute;
  bottom: 0;
  z-index: 15;

  background-color: black;
  color: white;
  height: 50px;
  border-radius: 5px;
  padding: 10px;
}

@media screen and (max-width:480px) {

  #media-object-controll-parts-wrapper {
    width: 95%;
  }
  
}



</style>