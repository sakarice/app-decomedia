<template>
  <transition :name="transitionName">
    <div id="select-modal">
      <div class="close-icon-wrapper">
        <i v-on:click="closeModal()" id="close-modal-icon" class="fas fa-chevron-circle-right fa-3x"></i>
      </div>
      <div id="area-wrapper">
        <div class="room-info-wrapper">
          <div id="room-name-wraper" class="room-info">
            <p class="info-title">Room名</p>
            <label for="">
              <p id="room-name">{{name}}</p>
            </label>
          </div>

          <div id="room-description-wrapper" class="room-info">
            <p class="info-title">説明</p>
            <textarea :value="description" type="text" id="room-description" rows="4" cols="30" readonly></textarea>
          </div>
        </div>


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
      // roomName : "",
      // roomDescription : "",
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
    // getRoomNameFromParent(){
    //   this.roomName = this.$parent.roomSetting['name'];
    // },
    // getRoomDescriptionFromParent(){
    //   this.roomDescription = this.$parent.roomSetting['description'];
    // },
  },
  // mounted : function() {
  //   this.getRoomNameFromParent();
  //   this.getRoomDescriptionFromParent();
  // }
  

}

</script>



<style>

@import "../../css/roomEditModals.css";

.room-info-wrapper {
  margin: 20px 0;
  width: 70%;

  display: flex;
  flex-direction: column;
  align-items: flex-start;
}

.room-info {
  margin-bottom : 20px;
}

.info-title {
  margin-bottom: 5px;
  font-weight: bold;
}

#room-name {
  margin: 0;
}



</style>