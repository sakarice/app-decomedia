<template>
  <div id="media-contents-field-wrapper">
    <div id="media-contents-field"
    :style="fieldStyle">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import {mapGetters,mapMutations} from 'vuex';

  export default{
    data : ()=>{
      return {
        scale : 1,
      }
    },
    computed : {
      ...mapGetters('media', ['getMediaId']),
      ...mapGetters('media', ['getMode']),
      ...mapGetters('mediaContentsField', ['getIsInitializedContentsField']),
      ...mapGetters('mediaContentsField', ['getMediaContentsField']),
      fieldStyle:function(){
        const style = {
          'background-color' : this.getMediaContentsField['color'],
          'background-image' : 'url(' + this.getMediaContentsField['img_url'] + ')',
          'background-size' : this.getMediaContentsField['img_size_type'],
          'background-position' : 'center',
          'background-repeat' : 'no-repeat',
          'width' : this.getMediaContentsField['width'] + "px",
          'height' : this.getMediaContentsField['height'] + "px",
          'transform' : "scale(" + this.scale + ")",
        }
        return style;
      }
    },
    watch : {
      // getMode:function(mode){if(mode!=3){ this.scale = 1}},
      $route(route){
        console.log("route:"+route);
        const mode = route.path;
        if(mode.match(/create/) || mode.match(/edit/)){
          this.scale = 1
        } else {
          this.scaleFieldSizeToWindow();
        }
      },
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
      scaleFieldSizeToWindow(){
        const ratio_w = window.innerWidth / this.getMediaContentsField['width'];
        const ratio_h = window.innerHeight / this.getMediaContentsField['height'];
        this.scale = ratio_w < ratio_h ? ratio_w*0.9 : ratio_h*0.8;
      }
    },
    mounted(){
      const listener_elem = document.getElementById('media-contents-field-wrapper');
      listener_elem.addEventListener('setBgImg', this.setBgImg, false);      
      listener_elem.addEventListener('clearBgImg', this.clearBgImg, false);      
      const mode = this.$route.path;
      if(mode.match(/create/)){
        this.updateMediaContentsFieldObjectItem({key:'width', value:Math.floor((window.innerWidth - 40)*0.9)});
        this.updateMediaContentsFieldObjectItem({key:'height', value:Math.floor((window.innerHeight - 100)*0.8)});
      }
    },
  }
</script>

<style scoped>

  #media-contents-field-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    /* width: 100%; */
    height: 100%;
  }

  #media-contents-field {
    overflow: hidden;
    position: relative;
    background-color: rgb(255,255,255);
  }

  @media screen and (max-width:480px) {
    #media-contents-field-wrapper {
      /* margin-top: 85px;
      margin-bottom: 55px; */
    }

    #media-contents-field {
      width: 95%;
    }    
  }

</style>