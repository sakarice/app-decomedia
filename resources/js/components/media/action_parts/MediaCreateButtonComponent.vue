<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger media-store-btn-wrapper" @click="createMedia">
        <span class="media-store-btn">保存</span>
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
      ...mapGetters('mediaFigures', ['getMediaFigures']),
      ...mapGetters('mediaTexts', ['getMediaTexts']),
      ...mapGetters('mediaAudios', ['getMediaAudios']),
      ...mapGetters('mediaMovie', ['getMediaMovie']),
      ...mapGetters('mediaSetting', ['getMediaSetting']),
      ...mapGetters('mediaContentsField',['getMediaContentsField']),
      
    },
    methods : {
      ...mapMutations('media', ['setIsCrudDoing']),
      createMedia() {
        const url = '/media/store';
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
            alert('メディア保存に失敗しました');
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

.media-store-btn-wrapper {
  color: black;
  outline: 1px solid deepskyblue;
  padding: 1px 10px;
  border-radius: 3px;
}

.media-store-btn-wrapper:hover {
  background-color: deepskyblue;
}

.media-store-btn {
  font-size: 15px;
}

/* スマホ */
@media screen and (max-width: 480px) {
  .for-pc-tablet {
    display: none;
  }

  .media-store-btn-wrapper {
    transform: scale(1);
  }

}

</style>