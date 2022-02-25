<template>
  <div id="master-vol-wrapper" class="all-audio-controller">
    <i class="audio-vol-icon fas fa-volume-off fa-2x"></i>
    <div id="vol-bar-wrapper">
      <input type="range" class="audio-vol-range"
       :value="getMasterVolume" @input="updateVolume($event.target.value)" min="0" max="1" step="0.01">
    </div>
  </div>
</template>


<script>
  import { mapGetters, mapMutations} from 'vuex';
  export default {
    computed : {
      ...mapGetters('mediaAudios', ['getMasterVolume']),
    },
    methods : {
      ...mapMutations('mediaAudios', ['updateMasterVolume']),
      updateVolume(volume){this.updateMasterVolume(Number(volume)); },
    },
  }

</script>

<style scoped>

  .all-audio-controller {
    color: ghostwhite;
    min-width: 70px;
    margin: 0 5px;
    padding: 7px;
    font-size: 11px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .all-audio-controller:hover {
    background-color: rgb(50,50,50);
    cursor: pointer;
  }

  .audio-vol-icon {
    margin-right: 5px;
  }

  #vol-bar-wrapper {
    display: flex;
  }

  
</style>