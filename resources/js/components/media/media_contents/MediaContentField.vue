<template>
  <div id="media-contents-field-wrapper">
    <div id="media-contents-field"
    :style="{'background-color' : getMediaSetting['mediaBackgroundColor']}">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import {mapGetters} from 'vuex';

  export default{
    computed : {
      ...mapGetters('mediaSetting', ['getMediaSetting']),
    },
  }
</script>

<style scoped>

  #media-contents-field-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    margin-top: 70px;
    margin-bottom: 10px;
  }

  #media-contents-field {
    overflow: hidden;
    position: relative;
    width: 90%;
    height: 90%;
    background-color: rgb(255,255,255);
    box-shadow: 1px 1px 3px lightgrey;
  }

  @media screen and (max-width:480px) {
    #media-contents-field-wrapper {
      margin-top: 45px;
      margin-bottom: 55px;
    }

    #media-contents-field {
      width: 95%;
    }    
  }

</style>