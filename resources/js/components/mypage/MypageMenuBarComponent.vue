<template>
    <div class="mypage-manu-bar">
        <!-- マイページトップ -->
        <div class="my-page-top">
            <div class="content">
                <!-- <a class="logo" href="/mypage"> -->
                <i @click="switchToMypageDefault" class="fas fa-door-open"></i>
                <!-- </a> -->
            </div>
        </div>

        <!-- 作成済みmedia一覧 -->
        <div class="created-medias">
            <div class="content">
                <!-- <a class="logo" href="/home/createdMedia"> -->
                    <!-- <i class="fas fa-hammer"></i> -->
                    <i @click="switchToCreatedMediaPreview" class="fas fa-clone"></i>
                <!-- </a> -->
            </div>
        </div>

        <!-- お気に入りmedia一覧 -->
        <div class="liked-medias">
            <div class="content">
                <!-- <a class="logo" href="/home/createdMedia"> -->
                    <i @click="switchToLikedMediaPreview" class="far fa-heart"></i>
                <!-- </a> -->
            </div>
        </div>

    </div>
</template>



<script>

export default {
    components : {},
    props : [
        // 'csrf',
    ],
    data : () => {
        return {
        }
    },
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
    mounted(){}
}

</script>

<style scoped>

.mypage-manu-bar {
  position: fixed;
  top: 0;
  z-index: 1;
  width: 40px;
  height: 100%;
  padding: 50px 0;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;

  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.content {
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    margin: 7px 0;
    padding: 10px;
    opacity: 0.9;
}

.content:hover {
  opacity: 1;
  background-color: grey;
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
    .mypage-manu-bar {
        left: 0;
    }    
}

@media screen and (max-width: 480px) {
    .mypage-manu-bar {
        right: 0;
    }
}


</style>