<template>
  <div id="media-contents-field-wrapper">
    <div id="media-contents-field"
    :style="backGroundStyle">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import {mapGetters,mapMutations} from 'vuex';

  export default{
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('mediaContentsField', ['getMediaContentsField']),
      backGroundStyle:function(){
        const style = {
          'background-color' : this.getMediaContentsField['color'],
          'background-image' : 'url(' + this.getMediaContentsField['img_url'] + ')',
          'background-size' : this.getMediaContentsField['img_size_type'],
          'background-position' : 'center',
          'background-repeat' : 'no-repeat',
        }
        return style;
      }
    },
    methods : {
      ...mapMutations('mediaContentsField', ['updateMediaContentsFieldObjectItem']),
      ...mapMutations('mediaContentsField', ['updateIsInitializedContentsField']),
      initContentsField(){
        this.getContentsFieldSettingFromDB()
        .then(datas=>{
          for(let key in datas){
            this.updateMediaContentsFieldObjectItem({key:key,value:datas[key]});
          }
          this.updateIsInitializedContentsField(true);
        })
      },
      getContentsFieldSettingFromDB(){
        return new Promise((resolve, reject) => {
          const url = '/mediaContentsField/'+this.getMediaId;
          axios.get(url)
          .then(response=>{
            return resolve(response.data);
          })
          .catch(error=>{});
        })
      },
      updateBgImg(img_id,img_type,img_url){
        this.updateMediaContentsFieldObjectItem({key:'img_id', value:img_id});
        this.updateMediaContentsFieldObjectItem({key:'img_type', value:img_type});
        this.updateMediaContentsFieldObjectItem({key:'img_url', value:img_url});
      },
      setBgImg(event){
        const data = event.detail.img_data;
        this.updateBgImg(data.img_id,data.img_type,data.url);
      },
      clearBgImg(){
        this.updateBgImg(null,null,"");
      },
    },
    mounted(){
      const listener_elem = document.getElementById('media-contents-field-wrapper');
      listener_elem.addEventListener('setBgImg', this.setBgImg, false);      
      listener_elem.addEventListener('clearBgImg', this.clearBgImg, false);      
    },
  }
</script>

<style scoped>

  #media-contents-field-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    margin-top: 70px;
    margin-bottom: 10px;
  }

  #media-contents-field {
    overflow: hidden;
    position: relative;
    width: 90%;
    height: 90%;
    background-color: rgb(255,255,255);
    box-shadow: 1px 1px 3px lightgrey;
  }

  @media screen and (max-width:480px) {
    #media-contents-field-wrapper {
      margin-top: 45px;
      margin-bottom: 55px;
    }

    #media-contents-field {
      width: 95%;
    }    
  }

</style>