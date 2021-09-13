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

        <!-- 作成済みroom一覧 -->
        <div class="created-rooms">
            <div class="content">
                <!-- <a class="logo" href="/home/createdRoom"> -->
                    <!-- <i class="fas fa-hammer"></i> -->
                    <i @click="switchToCreatedRoomPreview" class="fas fa-clone"></i>
                <!-- </a> -->
            </div>
        </div>

        <!-- お気に入りroom一覧 -->
        <div class="liked-rooms">
            <div class="content">
                <!-- <a class="logo" href="/home/createdRoom"> -->
                    <i @click="switchToLikedRoomPreview" class="far fa-heart"></i>
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
            this.$parent.isShowCreatedRoomPreview = true;
            this.$parent.isShowLikedRoomPreview = true;
            this.$parent.isShowCreatedRoomListPreview = true;
        
        },
        switchToCreatedRoomPreview(){
            this.$parent.isShowCreatedRoomPreview = true;
            this.$parent.isShowLikedRoomPreview = false;
            this.$parent.isShowCreatedRoomListPreview = true;
        },
        switchToLikedRoomPreview(){
            this.$parent.isShowCreatedRoomPreview = false;
            this.$parent.isShowLikedRoomPreview = true;
            this.$parent.isShowCreatedRoomListPreview = false;
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