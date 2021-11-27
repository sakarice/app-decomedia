<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger update-icon-wrapper" @click="updateMedia">
        <i class="fas fa-check fa-lg update-icon"></i>
      </div>
      <span class="action-trigger-subtitle">更新</span>
    </div>

</template>

<script>
  import { mapGetters, mapMutations} from 'vuex';

  export default {
    data : () => { return {} },
    computed : {
      ...mapGetters('mediaImg', ['getMediaImg']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      ...mapGetters('mediaMovie', ['getMediaMovie']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
    },
    methods : {
      ...mapMutations('media', ['setIsCrudDoing']),
      updateMedia() {
        // this.getFinishTime();
        const url = '/media/update';
        let media_datas = {
          'img' : this.getMediaImg,
          'audios' : this.getMediaAudios,
          'movie' : this.getMediaMovie,
          'setting' : this.getMediaSetting,
        }
        this.setIsCrudDoing(true);
        axios.post(url, media_datas)
        .then(response =>{
          alert(response.data.message);
          this.setIsCrudDoing(false);
        })
        .catch(error => {            
          alert('failed!');
          this.setIsCrudDoing(false);
        })
      },
    },

  }

</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

.update-icon-wrapper:hover {
  color: darkorange;
}

.update-trigger-subtitle {
  margin-left: 7px;
}

</style>