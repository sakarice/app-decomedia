<template>
    <div class="header">
        <!-- 左側 -->
        <div class="header-block header-left">
            <home-link-component></home-link-component>
        </div>

        <!-- 中央 -->
        <div class="header-block header-center">
            <cancel-button></cancel-button>

            <room-create-button v-if="isShowCreateButton"
            @getFinishTime="getFinishTime">
            </room-create-button>

            <room-update-button v-if="isShowUpdateButton"
            @getFinishTime="getFinishTime">
            </room-update-button>
        </div>

        <!-- 右側 -->
        <div class="header-block header-right">
            <!-- ログイン -->
            <!-- <div class="header-content" v-show="!(isLogin)">
                <a class="login" href="/login">ログイン</a>
            </div> -->

        </div>
    </div>
        
</template>



<script>
import HomeLink from './HomeLinkComponent.vue';
import CancelButton from './CancelButtonComponent.vue';
import RoomCreateButton from './RoomCreateButtonComponent.vue';
import RoomUpdateButton from './RoomUpdateButtonComponent.vue';


export default {
    components : {
        HomeLink,
        CancelButton,
        RoomCreateButton,
        RoomUpdateButton,
    },
    props : [
        'csrf',
        'isShowCreateButton',
        'isShowUpdateButton',
    ],
    data : () => {
        return {
        }
    },
    methods : {
        getFinishTime(){
            this.$emit('getFinishTime');
        },
    },
    mounted(){}
}

</script>

<style scoped>

.header {
  position: fixed;
  top: 0;
  z-index: 10;
  width: 100%;
  padding: 10px 0;
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



</style>