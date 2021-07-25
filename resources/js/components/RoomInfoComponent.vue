<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="area-wrapper">
        <div class="room-info-wrapper">
          <p>roominfo</p>
        </div>
      </div>
    </div>
  </transition>
</template>



<script>
export default{
  props: [
    'transitionName',
  ],
  data : () => {
    return {

    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },

  }
  

}

</script>



<style>

@import "../../css/roomEditModals.css";

.room-info-wrapper {
  margin: 20px 0;
  width: 50%;

  display: flex;
  flex-direction: column;
  align-items: flex-start;
}



</style>