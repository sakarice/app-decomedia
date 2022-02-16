<template>
    <header class="header">
        <!-- 左側 -->
        <div class="header-block header-left">
            <home-link-component></home-link-component>
        </div>

        <!-- 中央 -->
        <div class="header-block header-center">
            <media-title></media-title>
            <!-- <span :v-if="getMediaSetting['name']" class="header-content media-name">{{getMediaSetting['name']}}</span> -->
        </div>

        <!-- 右側 -->
        <div class="header-block header-right">
        </div>
    </header>
        
</template>



<script>
import { mapGetters } from 'vuex';
import MediaTitle from './MediaTitleComponent.vue';
import HomeLink from '../action_parts/HomeLinkComponent.vue';
import ToMypageButton from '../action_parts/ToMypageButtonComponent.vue';
import MediaCreateButton from '../action_parts/MediaCreateButtonComponent.vue';
import MediaUpdateButton from '../action_parts/MediaUpdateButtonComponent.vue';

export default {
    components : {
        MediaTitle,
        HomeLink,
        ToMypageButton,
        MediaCreateButton,
        MediaUpdateButton,
    },
    props : [
        'csrf',
    ],
    data : () => {
        return {
            isMyMedia : false,
        }
    },
    computed : {
        ...mapGetters('loginState', ['getIsLogin']),
        ...mapGetters('media', ['getMediaId']),
        ...mapGetters('media', ['getMode']),
        ...mapGetters('mediaSetting', ['getMediaSetting']),
        doneGetMediaId : function(){
            if(this.getMediaId != ""){return true}
        },
        mode : function(){ 
            if(this.$route.path.match(/create/)){
                return 1;
            }
            else if(this.$route.path.match(/edit/)){
                return 2;
            } else {return 3}
        },
    },
    methods : {
        checkIsMyMedia(){
            let url = '/ajax/judgeIsMyMedia/' + this.getMediaId;
            axios.get(url)
            .then(response =>{
                this.isMyMedia = response.data.isMyMedia;
            })
            .catch(error => {})
        },
    },
    watch : {
        doneGetMediaId : function(val){
            this.checkIsMyMedia();
        },
    }

}

</script>

<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "/resources/css/button.css";

.header {
  position: fixed;
  top: 0;
  padding: 5px 0;
  z-index: 10;
  width: 100%;
  height: 40px;
  background-color: rgba(0, 0, 0, 1);
  display: flex;

  justify-content: space-between;
  align-items: center;
}

.header-block {
  display: flex;
  justify-content: space-around;
}

.header-left {
  margin-left: 10px;
}

.header-center {
    margin: 0 10px;
}

.header-right {
  margin-right: 10px;
}

.header-content {
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    margin: 5px 20px;
    opacity: 0.9;
}

.header-content:hover {
  opacity: 1;
}

/* aタグ全体の設定 */
a {
    color: white;
    text-decoration: none;
}
a:hover {
    color: aquamarine;
}

.user-icon {
    background-color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 36px;
    height: 36px;
    padding: 2px;    
    border-radius: 50%;
}

#profile-img {
    width: 30px;
    height: 30px;
    color: white;
}

@media screen and (max-width:480px){
    .header-content {
        margin: 5px 0px;
    }

    .header-left {
        min-width: 55px;
    }
    .header-right {
        min-width: 55px;
    }

}


</style>