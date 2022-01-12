<template>
    <div id="mypage-manu-bar"
     class="pos-f top0 z1 w60px h100 pt50 flex column j-center a-center">
        <!-- マイページトップ -->
        <div class="my-page-top mb10">
            <div class="content p10 flex j-center a-center">
                <i @click="switchToMypageDefault" class="fas fa-home fa-2x"></i>
            </div>
        </div>

        <!-- 作成済みmedia一覧 -->
        <div class="created-medias mb10">
            <div class="content p10 flex j-center a-center">
                <i @click="switchToCreatedMediaPreview" class="fas fa-tools fa-2x"></i>
            </div>
        </div>

        <!-- お気に入りmedia一覧 -->
        <div class="liked-medias mb10">
            <div class="content p10 flex j-center a-center">
                <i @click="switchToLikedMediaPreview" class="fas fa-thumbs-up fa-2x"></i>
            </div>
        </div>

    </div>
</template>



<script>

export default {
    components : {},
    props : [ // 'csrf',
    ],
    data : () => {return {}},
    methods : {
        switchToMypageDefault(){
            this.$parent.isShowCreatedMediaPreview = true;
            this.$parent.isShowLikedMediaPreview = true;
            this.$parent.isShowCreatedMediaListPreview = true;
        },
        switchToCreatedMediaPreview(){
            this.$parent.isShowCreatedMediaPreview = true;
            this.$parent.isShowLikedMediaPreview = false;
            this.$parent.isShowCreatedMediaListPreview = true;
        },
        switchToLikedMediaPreview(){
            this.$parent.isShowCreatedMediaPreview = false;
            this.$parent.isShowLikedMediaPreview = true;
            this.$parent.isShowCreatedMediaListPreview = false;
        },
        
    },
}

</script>

<style scoped>
@import "/resources/css/flexSetting.css";
@import "/resources/css/FrequentlyUseStyle.css";


#mypage-manu-bar {
    background-color: rgba(253, 253, 253, 1);
    box-shadow: 1px 1px 10px lightgrey;
}

.content {
    color: black;
    opacity: 0.9;
    margin: 10px 0;
}

.content:hover {
    opacity: 1;
    background-color: rgb(230,230,230);
}

/* aタグ全体の設定 */
a {
    color: white;
    text-decoration: none;
}
a:hover {
    color: aquamarine;
}

@media screen and (min-width: 481px) {
    #mypage-manu-bar {
        left: 0;
    }    
}

@media screen and (max-width: 480px) {
    #mypage-manu-bar {
        /* right: 0; */
        display: none;
    }
}


</style>