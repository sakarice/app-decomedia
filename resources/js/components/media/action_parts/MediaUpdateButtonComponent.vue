<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger media-update-btn-wrapper" @click="updateMedia">
        <span class="media-update-btn">更新</span>
      </div>
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

.media-update-btn-wrapper {
  color: white;
  background-color: black;
  padding: 1px 10px;
  border-radius: 3px;
}

.media-update-btn-wrapper:hover {
  background-color: lawngreen;
}

.media-update-btn {
  font-size: 15px;
}

/* スマホ */
@media screen and (max-width: 480px) {
  .media-update-btn-wrapper {
    transform: scale(1);
  }

}



</style>