<template>
  <p id="player-setting-title" class="w100">{{labelName}}</p>
</template>

<script>

export default {
  props : [
    'labelName',
  ],
  data : () => {
    return {
    }
  },
  methods : {},

}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";

  #player-setting-title {
    margin: 10px 0;
    padding: 5px 0;
    text-align: center;
  }

</style>