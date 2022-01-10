<template>
  <transition :name="transitionName">
    <div id="select-modal">

      <div id="area-wrapper" class="">
        <div class="media-info-wrapper w90 mt20 mb20 flex column ">
          <div id="media-creater-info-wrapper" class="mb35">
          <h3 class="info-title mb5 font-13 grey">作成者</h3>
          <div class="flex a-center">
            <div class="avatar-wrapper">
              <img class="avatar w20px h20px border-r-50per" v-if="mediaOwnerInfo['profile_img_url'] !== null" :src="mediaOwnerInfo['profile_img_url']" alt="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/user-solid.svg">
              <img class="avatar w20px h20px border-r-50per" v-else src="https://app-decomedia-dev.s3.ap-northeast-1.amazonaws.com/app-decomedia/user-solid.svg" alt="">
            </div>
            <div>
              <span class="ml10">{{mediaOwnerInfo['name']}}</span>
            </div>
          </div>
          </div>

          <div id="media-name-wraper" class="media-info mb35">
            <h3 class="info-title mb5 font-13 grey">Media名</h3>
            <label for="">
              <p id="media-name" class="m0">{{getMediaSetting['name']}}</p>
            </label>
          </div>

          <div id="media-description-wrapper" class="media-info mb35 w100">
            <h3 class="info-title mb5 font-13 grey">説明</h3>
            <textarea :value="getMediaSetting['description']" type="text" id="media-description" class="w90" readonly></textarea>
          </div>
        </div>
      </div>

      <close-modal-bar class="for-mobile"></close-modal-bar>
      <close-modal-icon class="for-pc-tablet"></close-modal-icon>

    </div>
  </transition>
</template>



<script>
import { mapGetters } from 'vuex';
import closeModalBar from '../change_display_parts/CloseModalBarComponent.vue'
import closeModalIcon from '../change_display_parts/CloseModalIconComponent.vue'

export default{
  components : {
    closeModalBar,
    closeModalIcon,
  },
  props: [
    'transitionName',
  ],
  data : () => {
    return {
      mediaOwnerInfo : {},
    }
  },
  computed : {
    ...mapGetters('mediaSetting', ['getMediaSetting']),
    ...mapGetters('mediaSetting', ['getIsInitializedSetting']),
  },
  watch : {
    getIsInitializedSetting:function(val){
      if(val==true){ this.getProfile();}
    }
  },
  methods : {
    closeModal() {
      this.$emit('close-modal');
    },
    getProfile(){ // DBからログイン中ユーザのidとプロフィール情報を取得
      let url = '/user/mediaOwner/profile/show/' + this.getMediaSetting['id'];
      axios.get(url)
      .then(res => {
        this.mediaOwnerInfo['id'] = res.data.id;
        this.mediaOwnerInfo['name'] = res.data.name;
        this.mediaOwnerInfo['profile_img_url'] = res.data.profile_img_url;
      })
      .catch(error => {
        alert('Media作成者を取得できませんでした。');
      })
    },
  },

}

</script>



<style scoped>

@import "/resources/css/mediaEditModals.css";
@import "/resources/css/FrequentlyUseStyle.css";
@import "/resources/css/flexSetting.css";

.media-info-wrapper {
  overflow-y: scroll;
}

#media-description {
  height: 150px;
}

.grey { color: grey;}

</style>