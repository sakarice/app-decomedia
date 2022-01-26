<template>
  <section id="public-audio-wrapper" class="flex j-center a-center w100">

    <ul id="audio-wrapper" class="flex a-center">
      <li :id="index" class="audio-list flex column a-center mb10" v-for="(publicAudio, index) in publicAudios" :key="publicAudio.url">
        <span class="font-12 grey">{{publicAudios[index]['name']}}</span>
        <div class="audio-wrapper">
          <img class="public-audio-thumbnail" :src="publicAudio['thumbnail_url']" :alt="publicAudio['thumbnail_url']" />
        </div>
        <!-- <i class="fas fa-cog mt10 mb10"></i> -->
        <span class="font-12 grey">{{publicAudios[index]['category']}}</span>
        <button class="mt3 mb3" @click="toggleShowSetting(index)">
          設定
        </button>

        <div class="setting-wrapper w90 h70 p5 pos-a top10 flex column a-center z1">
          <div class="setting">
            <span>カテゴリ</span>
          </div>
          <select id="" v-model="publicAudios[index]['category']">
            <option v-for="category in audioCategory" :value="category" :key="category.id">
              {{category}}
            </option>
          </select>
          <button class="mt10" @click="updateCategory(index)">更新</button>
        </div>
      </li>
    </ul>

  </section>
</template>

<script>

export default {
  components : {
  },
  props : [
  ],
  data : () => {
    return {
      publicAudios : [],
      audioCategory : [],
      settings : "",
      isShowSetting : [],
    }
  },
  computed : {
    audioNum : function(){return this.publicAudios.length;}
  },
  methods : {
    getPublicAudios(){
      const url = '/ajax/getPublicAudios';
      axios.get(url)
        .then(response => {
          response.data.audios.forEach(audio => {            
            this.publicAudios.unshift(audio);
          });
        })
        .catch(error => {
          alert('音楽取得失敗');
        })
    },
    getAudioCategory(){
      const url = '/audioCategory';
      axios.get(url)
      .then(res=>{
        res.data.category.forEach(category=>{
          this.audioCategory.push(category);
        })
      })
    },
    updateCategory(index){
      const audio_id = this.publicAudios[index]['audio_id'];
      const url = '/publicAudio/category/update';
      const data = {
        'audio_id' : audio_id,
        'category' : this.publicAudios[index]['category'],
      }
      axios.post(url, data)
      .then(res=>{
        console.log('category update success');
      }).catch(error=>{
        console.log('category update failed');
      })

    },
    toggleShowSetting(index){
      this.settings[index].style.display = this.isShowSetting[index]==true ? "none" : "flex";
      this.isShowSetting[index] = !this.isShowSetting[index];
    },
    initIsShowSetting(){
      for(let i=0; i < this.audioNum; i++){
        this.isShowSetting.push(false);
      }
    },
  },
  created(){
    this.getPublicAudios();
    this.getAudioCategory();
  },
  mounted(){
    this.$nextTick(function () { // DOMの更新を待つ
      this.settings = document.getElementsByClassName('setting-wrapper');
    });
  },
  watch : {
    audioNum:function(val){
      if(val>0){this.initIsShowSetting();}
    }
  }
}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

  #audio-wrapper {
    flex-wrap: wrap;
    align-content: flex-start;

    width: 92%;
    height: 80vh;
    padding-left: 0;
    overflow-y: scroll;
  }

  .audio-list {
    position: relative;
    width: 14%;
    margin: 0px 1px 15px 1px;
    list-style: none;
    transition: transform 0.3s;
  }

  .audio-list:hover {
    cursor: pointer;
    transform: scale(0.98,0.98);
  }
  .audio-list:hover .icon-cover {
    z-index: 2;
    background-color: rgba(130, 130, 130, 0.6);
  }

  .audio-wrapper {
    position: relative;
    width: 100%;
    background-color: grey;
  }
  .audio-wrapper:before {
    content: "";
    display: block;
    padding-top: 100%;
  }


  .public-audio-thumbnail{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    margin: auto;
    object-fit: cover;
  }

  .setting-wrapper {
    display: none;
    background-color: rgba(250,250,250,0.8);
  }

  .grey { color: grey;}


  @media screen and (max-width:480px) {
    .audio-list {
      width: 24%;
    }
    
  }

</style>