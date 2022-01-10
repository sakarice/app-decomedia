<template>
  <transition :name="transitionName">
    <div id="select-modal">

      <div id="area-wrapper" class="">
        <div class="media-info-wrapper w90 mt20 mb20 flex column a-start">
          <div id="media-name-wraper" class="media-info mb20">
            <p class="info-title mb5">Media名</p>
            <label for="">
              <p id="media-name" class="m0">{{getMediaSetting['name']}}</p>
            </label>
          </div>

          <div id="media-description-wrapper" class="media-info mb20 w100">
            <p class="info-title mb5">説明</p>
            <textarea :value="getMediaSetting['description']" type="text" id="media-description" class="w100" readonly></textarea>
          </div>
        </div>
      </div>

      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>



<script>
import { mapGetters } from 'vuex';
import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../change_display_parts/CloseModalIconComponent.vue'

export default{
  components : {
    closeModalBar,
    closeModalIcon,
  },
  props: [
    'transitionName',
  ],
  data : () => {
    return {
    }
  },
  computed : {
    ...mapGetters('mediaSetting', ['getMediaSetting']),
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
  },

}

</script>



<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.media-info-wrapper {
  overflow-y: scroll;
}

.info-title {
  font-weight: bold;
}

#media-description {
  height: 150px;
}


</style>