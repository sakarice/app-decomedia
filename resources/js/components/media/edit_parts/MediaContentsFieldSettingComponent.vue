<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">

        <div id="media-contents-field-setting-area" class="flex column a-start">
          <h2 id="media-contents-field-setting-title">メディア設定</h2>

          <!-- メディア背景色設定 -->
          <div id="media-bg-color-wraper" class="setting">
            <h3 class="setting-title">Media背景</h3>
            <label for="">
              <input :value="getMediaContentsField['color']" @input="updateMediaContentsFieldObjectItem({key:'color', value:$event.target.value})" type="color" id="media-bg-color">
              カラー選択
            </label>
          </div>

        </div>

      </div>

      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex';
import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../change_display_parts/CloseModalIconComponent.vue'

export default {
  components : {
    closeModalBar,
    closeModalIcon,
  },
  props : [
    'transitionName',
  ],
  data : () => {
    return {
    }
  },
  computed :  {
    ...mapGetters('mediaContentsField',['getMediaContentsField']),
  },
  methods : {
    ...mapMutations('mediaContentsField', ['updateMediaContentsFieldObjectItem']),
    closeModal() {
      this.$emit('close-modal');
    },
  },
  mounted : function() {
  },
}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";

  #media-contents-field-setting-area {
    margin: 20px 0;
    width: 95%;
    overflow-y: scroll;
  }

  #media-contents-field-setting-title{
    font-weight: bold;
    font-size: 14px;
    margin: 10px 0 30px 0;
    background-color: lightslategrey;
    border-radius: 5px;
    padding: 3px 10px;
  }

  .setting {
    margin-bottom : 20px;
  }

  .public-state-icon {
    margin-left: 10px;
    opacity: 0.5;
  }
  .public-state-icon:hover {
    opacity: 1;
  }
  .open-icon {
    color: lawngreen;
  }
  .lock-icon {
    color: yellow;
  }
  

  .message-label {
    font-size: 10px;
  }

  .setting-title {
    margin-bottom: 5px;
    /* font-weight: bold; */
    font-size: 15px;
  }

  .img-config-input {
    margin-bottom : 5px;
  }

    /* トグル */
  .toggle-outer{
    width: 38px;
    height: 17px;
    padding: 2px;
    border-radius: 20px;
    background-color: grey;
    transition-duration: 0.4s;
  }

  .toggle-inner {
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background-color: white;
  }

  .is-public-outer {
    background-color: lawngreen;
  }
  .is-public-inner {
    margin-left: 19px;
  }



@media screen and (max-width:480px) {
  
  #area-wrapper {
    padding: 20px;
  }
  
  #media-contents-field-setting-area {
    margin : 0;
  }
}

</style>