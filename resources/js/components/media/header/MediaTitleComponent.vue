<template>
    <div class="media-title-wrapper flex">
        <input type="text" id="media-title" spellcheck="false"
        :class="{'editable':!isDisable}"
        :disabled="isDisable"
        :value="getMediaSetting['name']"
        @input="updateMediaTitle($event)"
        @keydown="checkAndBlockEnterInput">
    </div>
</template>


<script>
import { mapGetters,mapMutations } from 'vuex';

export default {
    components : {
    },
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
        isDisable:function(){
            return this.mode==3 || (this.mode==2 && !this.isMyMedia) ? true : false;
        }
    },
    methods : {
        ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
        updateMediaTitle(event){
            const new_title = event.target.value;
            this.updateMediaSettingObjectItem({key:'name', value:new_title});
        },
        checkIsMyMedia(){
            let url = '/ajax/judgeIsMyMedia/' + this.getMediaId;
            axios.get(url)
            .then(response =>{
                this.isMyMedia = response.data.isMyMedia;
            })
            .catch(error => {})
        },
        checkAndBlockEnterInput(e){ // Enterキーによる改行を阻止する。
            if(e.keyCode===13){e.preventDefault()};
        },
    },
    watch : {
        doneGetMediaId : function(val){this.checkIsMyMedia();},
    },
}

</script>

<style scoped>
@import '/resources/css/flexSetting.css';

#media-title {
    color:white;
    padding: 0 5px;
    min-width: 140px;
    max-width: 350px;
    border: none;
    border-radius: 1px;
    white-space: nowrap;
    overflow-x: hidden;
}

.editable {
    background-color: rgba(255,255,255,0.2);
    outline: 1px solid rgba(255,255,255,0.3);
}
.editable:hover {
    background-color: rgba(255,255,255,0.3);
    cursor: pointer;
}

@media screen and (max-width:480px) {
    #media-title {
        max-width: 100px;
    }
    
}


</style>