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
        const event = new CustomEvent('changeStereoMonaural', {detail:{isStereo:this.isStereo}});
        document.body.dispatchEvent(event);
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
  font-size: 12px;
  margin: 3px 12px 0 0;
}

.toggle-outer {
  width: 37px;
  height: 13px;
  border-radius: 15px;
  background-color: darkslategray;
  outline: 1px solid grey;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 0 21px 0 0;

  transition: all 0.2s;
}

.toggle-inner {
  z-index: 1;
  width: 16px;
  height: 16px;
  /* background-color: lime; */
  background-color: darkgrey;
  border-radius: 50%;
}

.j-end {
  padding: 0 0 0 21px;
}
.bg-lime { background-color: lime;}

  
</style>