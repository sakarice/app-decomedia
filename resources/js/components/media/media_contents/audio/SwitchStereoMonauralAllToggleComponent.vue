<template>
  <div id="toggle-area" class="flex j-center a-center" @click="switchStereoMonauralAll">
    <p class="stereo-toggle-label">立体音響</p>
    <div class="toggle-outer" :class="{'j-end': isStereo}">
      <div class="toggle-inner" :class="{'bg-lime': isStereo}"></div>
    </div>
  </div>
</template>


<script>
  import { mapGetters, mapMutations} from 'vuex';
  export default {
    data : () => {
      return {
        isStereo : true,
      }
    },
    computed : {
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      audioNum : function(){ return this.getMediaAudios.length; },
    },
    methods : {
      ...mapMutations('mediaAudios', ['updateMediaAudiosObjectItem']),
      switchStereoMonauralAll(){
        for(let i=0; i<this.audioNum; i++){
          this.updateMediaAudiosObjectItem({index:i, key:'panningFlag', value:!this.isStereo});
        }
        this.isStereo = !this.isStereo;
      },
    },
  }

</script>

<style scoped>

#toggle-area:hover {
  cursor: pointer;
}

#toggle-area > .stereo-toggle-label {
  color: white;
  margin: 0 7px 0 0;
}

.toggle-outer {
  width: 37px;
  height: 20px;
  padding: 0 1px;
  border-radius: 15px;
  outline: 1px solid white;
  display: flex;
  align-items: center;
}

.toggle-inner {
  width: 16px;
  height: 16px;
  /* background-color: lime; */
  background-color: darkgrey;
  border-radius: 50%;
}

.j-end {
  justify-content: end;
}
.bg-lime { background-color: lime;}

  
</style>