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
  import { showOverLay, hideOverLay } from '../../../functions/overlayDispHelper';

  export default {
    data : () => { return {} },
    computed : {
      ...mapGetters('mediaImgs', ['getMediaImgs']),
      ...mapGetters('mediaTexts', ['getMediaTexts']),
      ...mapGetters('mediaFigures', ['getMediaFigures']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      ...mapGetters('mediaMovie', ['getMediaMovie']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      ...mapGetters('mediaContentsField',['getMediaContentsField']),
    },
    methods : {
      ...mapMutations('media', ['setIsCrudDoing']),
      updateMedia() {
        const url = '/media/update';
        let media_datas = {
          'imgs' : this.getMediaImgs,
          'figures' : this.getMediaFigures,
          'texts' : this.getMediaTexts,
          'audios' : this.getMediaAudios,
          'movie' : this.getMediaMovie,
          'contents_field': this.getMediaContentsField,
          'setting' : this.getMediaSetting,
        }
        this.showWaitingModal();
        axios.post(url, media_datas)
        .then(response =>{
          alert(response.data.message);
          this.hideWaitingModal();
        })
        .catch(error => {            
          alert('メディア更新に失敗しました');
          this.hideWaitingModal();
        })
      },
      showWaitingModal(){
        showOverLay();
        this.setIsCrudDoing(true);
      },
      hideWaitingModal(){
        this.setIsCrudDoing(false);
        hideOverLay();
      },
    },
    created(){},

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