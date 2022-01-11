<template>
  <transition name="flowup">

    <div id="comment-delete-confirm-wrapper" v-show="isShow"
      class="pos-f top0 left0 z20 w100 h100 flex j-center a-center bg-black white">
      <div id="confirm-item-wrapper" class="flex column a-center j-center">
        <span class="confirm-message">本当に削除してよろしいですか？</span>
        <div class="btn-wrapper flex mt20 a-center">
          <button class="cancel-btn mr5" @click="hide">
            キャンセル
          </button>
          <button class="ok-btn ml5" @click="noticeDeleteOk">
            削除する
          </button>
        </div>
      </div>
    </div>

  </transition>
</template>


<script>
import { showOverLay, hideOverLay } from '../../../../functions/overlayDispHelper';

export default{
  props: [],
  data : () => {
    return {
      isShow : false,
      target_elem : "",
    }
  },
  computed : {
  },
  methods : {
    show(e){
      this.target_elem = e.detail.elem;
      showOverLay();
      this.isShow = true;
    },
    hide(){
      hideOverLay();
      this.isShow = false;
    },
    noticeDeleteOk(){
      const event = new CustomEvent('deleteComment');
      this.target_elem.dispatchEvent(event);
      this.hide();
    },
  },
  created(){
    document.body.addEventListener('showCommentDeleteConfirm', this.show, false);
  },

}

</script>

<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.bg-black { background-color: rgba(0,0,0,0.5);}
.white { color: white;}

</style>