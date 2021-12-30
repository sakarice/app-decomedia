<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div id="area-wrapper">
        <div id="media-text-setting-area" class="flex column a-start">
          <h2 id="media-text-setting-title">メディアテキスト設定</h2>
          <!-- メディア名設定 -->
          <div id="media-text-add-wraper" class="setting">
            <i class="fas fa-plus fa-2x add-text-icon" @click="addText()"></i>
            <span>追加</span>
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
      window_width : "",
      window_height : "",
    }
  },
  computed :  {
    ...mapGetters('media', ['getMediaId']),
    ...mapGetters('mediaTextFactory', ['getTextData']),
    // ...mapGetters('mediaTexts', ['getMediaText']),
    // ...mapGetters('mediaTexts', ['getMediaTexts']),
    ...mapGetters('mediaSetting', ['getMediaSetting']),
  },  
  methods : {
    ...mapMutations('mediaTextFactory', ['updateTextData']),
    ...mapMutations('mediaTexts', ['addMediaTextsObjectItem']),
    // ...mapMutations('mediaTexts', ['updateIsInitializedTexts']),
    // ...mapMutations('mediaTexts', ['deleteMediaTextsObjectItem']),
    closeModal() {
      this.$emit('close-modal');
    },
    addText(){
      const mediaText = Object.assign({},this.getTextData);
      this.addMediaTextsObjectItem(mediaText);
      this.updateTextData({key:"left",value:Number(this.getTextData["left"]+20)})
      this.updateTextData({key:"top",value:Number(this.getTextData["top"]+20)})
    },

  },
  mounted : function() {
    this.window_width = window.innerWidth;
    this.window_height = window.innerHeight;
  },
}
</script>


<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/flexSetting.css";

  #media-text-setting-area {
    margin: 20px 0;
    width: 95%;
    overflow-y: scroll;
  }

  #media-text-setting-title{
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

  .add-text-icon {
    padding: 10px;
  }
  .add-text-icon:hover{
    color: lightsalmon;
  }


  .setting-title {
    margin-bottom: 5px;
    /* font-weight: bold; */
    font-size: 15px;
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



@media screen and (max-width:480px) {
  
  #area-wrapper {
    padding: 20px;
  }
  
  #media-text-setting-area {
    margin : 0;
  }
}

</style>