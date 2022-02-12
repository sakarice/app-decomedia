<template>
    <div class="media-title-wrapper flex">
        <div type="text" id="media-title" spellcheck="false"
        :class="{'editable':contentEditable}"
        :contenteditable="contentEditable"
        @input="updateMediaTitle($event)"
        @keydown="checkAndBlockEnterInput">
        {{getMediaSetting['name']}}
        </div>
    </div>
        
</template>



<script>
import { mapGetters,mapMutations } from 'vuex';

export default {
    components : {
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
        contentEditable:function(){
            return this.isMyMedia && this.mode!=3 ? true : false;
        },

    },
    methods : {
        ...mapMutations('mediaSetting', ['updateMediaSettingObjectItem']),
        updateMediaTitle(event){
            console.log('update media title');
            const new_title = event.target.innerText;
            console.log(new_title);
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
        doneGetMediaId : function(val){
            this.checkIsMyMedia();
        },

    },

}

</script>

<style scoped>
@import '/resources/css/flexSetting.css';

#media-title {
    display: inline-block;
    color:white;
    padding: 0 5px;
    min-width: 140px;
    max-width: 350px;
    border-radius: 2px;
    white-space: nowrap;
    overflow-x: hidden;
}

.editable {
    background-color: rgba(255,255,255,0.1);
    outline: 1px solid rgba(255,255,255,0.3);
}
.editable:hover {
    background-color: rgba(255,255,255,0.3);
}

@media screen and (max-width:480px) {
    #media-title {
        max-width: 100px;
    }
    
}


</style>