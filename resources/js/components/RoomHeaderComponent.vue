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
            @create-room="createRoom">
            </room-create-button>

            <room-update-button v-if="isShowUpdateButton"
            @update-room="updateRoom">
            </room-update-button>

            <a v-if="isShowLinkToShow" :href="roomShowLink(roomId)" class="action-trigger-wrapper link-to-show-room">
                <div class="action-trigger goto-show-room-icon-wrapper">
                    <i class="fas fa-door-open fa-2x goto-show-room-icon "></i>
                </div>
                <span class="action-trigger-subtitle">閲覧画面へ</span>
            </a>
            <a v-if="isShowLinkToEdit" :href="roomEditLink(roomId)" class="action-trigger-wrapper link-to-edit-room">
                <div class="action-trigger goto-edit-room-icon-wrapper">
                    <i class="fas fa-pen fa-2x goto-edit-room-icon "></i>
                </div>
                <span class="action-trigger-subtitle">編集画面へ</span>
            </a>

        </div>

        <!-- 右側 -->
        <div class="header-block header-right">
            <span :v-if="roomName" class="header-content room-name">{{roomName}}</span>
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
        'isShowLinkToShow',
        'isShowLinkToEdit',
        'roomId',
        'roomName',
    ],
    data : () => {
        return {
        }
    },
    methods : {
        getFinishTime(){
            this.$emit('getFinishTime');
        },
        createRoom(){
            this.$emit('create-room');
        },
        updateRoom(){
            this.$emit('update-room');
        },
        roomShowLink : function(id) {
            return "/room/" + id;
        },
        roomEditLink : function(id) {
            return "/room/" + id + "/edit";
        },
    },

}

</script>

<style scoped>

/* ボタン共通のCSS。対象にはaction-buttonクラスを付けること */
@import "../../css/button.css";

.header {
  position: fixed;
  top: 0;
  padding: 5px 0;
  z-index: 10;
  width: 100%;
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
}


</style>