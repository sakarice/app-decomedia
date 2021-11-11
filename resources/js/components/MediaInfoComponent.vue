<template>
  <transition :name="transitionName">
    <div id="select-modal">

      <div id="area-wrapper">
        <div class="media-info-wrapper">
          <div id="media-name-wraper" class="media-info">
            <p class="info-title">Media名</p>
            <label for="">
              <p id="media-name">{{name}}</p>
            </label>
          </div>

          <div id="media-description-wrapper" class="media-info">
            <p class="info-title">説明</p>
            <textarea :value="description" type="text" id="media-description" readonly></textarea>
          </div>
        </div>
      </div>

      <i v-on:click="closeModal()" id="change-disp-modal" class="fas fa-times-circle fa-2x for-mobile"></i>
      <div class="close-icon-wrapper for-pc-tablet">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-left fa-3x"></i>
      </div>

    </div>
  </transition>
</template>



<script>
export default{
  props: [
    'transitionName',
    'name',
    'description',
  ],
  data : () => {
    return {
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
  },

}

</script>



<style scoped>

@import "../../css/mediaEditModals.css";

.media-info-wrapper {
  margin: 20px 0;
  width: 70%;

  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.media-info {
  margin-bottom : 20px;
}

.info-title {
  margin-bottom: 5px;
  font-weight: bold;
}

#media-name {
  margin: 0;
}

#media-description {
  width: 100%;
  height: 150px;
}


</style>