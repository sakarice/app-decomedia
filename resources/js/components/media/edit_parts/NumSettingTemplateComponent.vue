<template>
  <div class="setting w100 flex j-s-between a-end">
    <h3 class="sub-title mb0">{{subTitle}}</h3>
    <div class="flex a-center">
      <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="minusOneValue()"></i>
      <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="plusOneValue()"></i>
    </div>
    <input type="number" class="w60px" :value="numValue" @input="updateValue($event.target.value)">
  </div>
</template>

<script>

export default {
  props : [
    'subTitle',
    'numValue'
  ],
  data : () => {
    return {
    }
  },
  methods : {
    minusOneValue(){
      this.$emit('minusOneValue');
    },
    plusOneValue(){
      this.$emit('plusOneValue');
    },
    updateValue(value){
      this.$emit('updateValue', value);
    }
  },

}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";


</style>