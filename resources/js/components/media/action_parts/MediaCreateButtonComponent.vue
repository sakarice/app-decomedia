<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger create-icon-wrapper" @click="createMedia">
        <i class="fas fa-check fa-lg create-icon"></i>
      </div>
      <span class="action-trigger-subtitle">作成</span>
    </div>

</template>

<script>
  import { mapGetters, mapMutations} from 'vuex';

  export default {
    data : () => { return {} },
    computed : {
      ...mapGetters('mediaImg', ['getMediaImg']),
      ...mapGetters('mediaFigures', ['getMediaFigures']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      ...mapGetters('mediaMovie', ['getMediaMovie']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
    },
    methods : {
      ...mapMutations('media', ['setIsCrudDoing']),
      createMedia() {
        // this.getFinishTime();
        const url = '/media/store';
        let media_datas = {
          'img' : this.getMediaImg,
          'figures' : this.getMediaFigures,
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
            alert('メディア保存に失敗しました');
            this.setIsCrudDoing(false);
          })
      },
    },
    
  }


</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

.create-icon-wrapper:hover {
  color: darkorange;
}

.action-trigger-subtitle {
  margin-left: 7px;
}

</style>