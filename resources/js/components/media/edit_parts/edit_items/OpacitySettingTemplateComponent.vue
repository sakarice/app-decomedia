<template>
  <div id="opacity-wrapper" class="setting w100 flex j-s-between a-end">
    <h3 class="sub-title mb0"><slot name="label">透過度</slot></h3>
    <input type="range" v-model="opacity" @mousedown.stop name="opacity" min="0" max="1" step="0.05">
    <input type="number" v-model="opacity" name="opacity" min="0" max="1" step="0.05">
  </div>
</template>

<script>

export default {
  props : [
    'initValue',
  ],
  data : () => {
    return {
      opacity : 1,
    }
  },
  methods : {
  },
  watch : {
    opacity : function(value){
      this.$emit('value-updated', value);
      console.log("value:"+value);
    },
  },
  created(){
    this.opacity = this.initValue;
  }

}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";

  .setting {
    margin-bottom : 30px;
    padding-bottom: 3px;
    border-bottom: 0.5px solid rgba(200,200,200,0.2);
  }

  .sub-title {
    font-size: 13px;
    color: lightgrey;
  }

</style>