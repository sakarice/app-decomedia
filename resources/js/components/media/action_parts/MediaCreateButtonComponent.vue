<template>

    <div class="action-trigger-wrapper">
      <div class="action-trigger create-icon-wrapper" @click="createMedia">
        <i class="fas fa-check fa-lg create-icon"></i>
      </div>
      <span class="action-trigger-subtitle for-pc-tablet">作成</span>
    </div>

</template>

<script>
  import { mapGetters, mapMutations} from 'vuex';

  export default {
    data : () => { return {} },
    computed : {
      ...mapGetters('mediaImgs', ['getMediaImgs']),
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
          'imgs' : this.getMediaImgs,
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

.create-icon-wrapper {
  box-shadow: 1px 1px 1px dimgrey;
  padding: 5px 7px;
  background-color: rgb(100,100,255);
  opacity: 0.9;
}

.create-icon-wrapper:hover {
  opacity: 1;
}

/* スマホ */
@media screen and (max-width: 480px) {
  .for-pc-tablet {
    display: none;
  }
}



</style>