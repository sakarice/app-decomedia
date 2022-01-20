<template>
  <section id="public-img-wrapper" class="flex j-center a-center w100">

    <ul id="img-wrapper" class="flex a-center">
      <li :id="index" class="img-list flex column a-center mb10" v-for="(publicImg, index) in publicImgs" :key="publicImg.url">
        <div class="img-wrapper">
          <img class="public-img" :src="publicImg['url']" :alt="publicImg['url']" />
        </div>
        <!-- <i class="fas fa-cog mt10 mb10"></i> -->
        <span class="font-12 grey">{{publicImgs[index]['category']}}</span>
        <button class="mt3 mb3" @click="toggleShowSetting(index)">
          設定
        </button>
        <div class="setting-wrapper w90 h70 p5 pos-a top10 flex column a-center z1">
          <div class="setting">
            <span>カテゴリ</span>
          </div>
          <select id="" v-model="publicImgs[index]['category']">
            <option v-for="category in imgCategory" :value="category" :key="category.id">
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
      publicImgs : [],
      imgCategory : [],
      settings : "",
      isShowSetting : [],
    }
  },
  computed : {
    imgNum : function(){return this.publicImgs.length;}
  },
  methods : {
    getPublicImgs(){
      const url = '/ajax/getPublicImgs';
      axios.get(url)
        .then(response => {
          response.data.file_datas.forEach(file_data => {            
            this.publicImgs.unshift(file_data);
          });
        })
        .catch(error => {
          alert('画像取得失敗');
        })
    },
    getImgCategory(){
      const url = '/imgCategory';
      axios.get(url)
      .then(res=>{
        res.data.category.forEach(category=>{
          this.imgCategory.push(category);
        })
      })
    },
    updateCategory(index){
      const img_id = this.publicImgs[index]['id'];
      const url = '/publicImg/category/update';
      const data = {
        'img_id' : img_id,
        'category' : this.publicImgs[index]['category'],
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
      for(let i=0; i < this.imgNum; i++){
        this.isShowSetting.push(false);
      }
    },
  },
  created(){
    this.getPublicImgs();
    this.getImgCategory();
  },
  mounted(){
    this.$nextTick(function () { // DOMの更新を待つ
      this.settings = document.getElementsByClassName('setting-wrapper');
    });
  },
  watch : {
    imgNum:function(val){
      if(val>0){this.initIsShowSetting();}
    }


  }
}
</script>


<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

  #img-wrapper {
    flex-wrap: wrap;
    align-content: flex-start;

    width: 92%;
    height: 80vh;
    padding-left: 0;
    overflow-y: scroll;
  }

  .img-list {
    position: relative;
    width: 14%;
    margin: 0px 1px 15px 1px;
    list-style: none;
    transition: transform 0.3s;
  }

  .img-list:hover {
    cursor: pointer;
    transform: scale(0.98,0.98);
  }
  .img-list:hover .icon-cover {
    z-index: 2;
    background-color: rgba(130, 130, 130, 0.6);
  }

  .img-wrapper {
    position: relative;
    width: 100%;
    background-color: grey;
  }
  .img-wrapper:before {
    content: "";
    display: block;
    padding-top: 100%;
  }


  .public-img{
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

</style>