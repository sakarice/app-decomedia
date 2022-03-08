<template>
  <div class="setting w100 flex j-s-between a-end">
    <h3 class="sub-title mb0">{{label}}</h3>
    <div class="flex a-center">
      <i class="fas fa-minus fa-lg btns minus-btn mr10" @click.stop="decrease()"></i>
      <i class="fas fa-plus fa-lg btns plus-btn ml10" @click.stop="increase()"></i>
    </div>
    <input type="number" class="w60px" :value="inputValue" @input="updateValue(Number($event.target.value))">
  </div>
</template>

<script>

export default {
  props : [
    'label',
    'inputValue',
  ],
  data : () => {
    return {
    }
  },
  methods : {
    decrease(){
      this.$emit('decrease');
    },
    increase(){
      this.$emit('increase');
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

  .setting {
    margin-bottom : 30px;
    padding-bottom: 3px;
    border-bottom: 0.5px solid rgba(200,200,200,0.2);
  }

  .sub-title {
    font-size: 13px;
    color: lightgrey;
  }
  .sub-sub-title {
    display: inline-block;
    font-size: 13px;
    color: darkgrey;
  }

  .btns {
    border-radius: 50%;
    padding: 5px 4px;
  }
  .btns:hover { cursor: pointer;}
  .plus-btn {
    color: palevioletred;
    border: 1.5px solid palevioletred;
  }
  .minus-btn {
    color: deepskyblue;
    border: 1.5px solid deepskyblue;
  }


</style>