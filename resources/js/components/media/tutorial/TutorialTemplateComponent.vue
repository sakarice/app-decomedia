
<template>
  <transition name="slidein">
    <!-- 説明用モーダル -->
    <div id="tutorial-modal">
      <!-- 上部のヘッダ -->
      <div class="tutorial--modal--header">
        <div class="page-wrapper">
          <span class="page-index">{{page}}</span>
        </div>
        <p class="description-title">{{tutorialInfo['title']}}</p>
      </div>

      <!-- 補足用画像 -->
      <div class="tutorial--img--wrapper">
        <img class="tutorial--img" :src="tutorialInfo['imgUrl']" :alt="page">
      </div>

      <!-- 説明 -->
      <div class="tutorial--description--wrapper">
        <p class="description">{{tutorialInfo['description']}}</p>
      </div>
    </div>

  </transition>
</template>

<script>
import {mapGetters} from 'vuex';

  export default {
    components : {
    },
    props : [
      'page',
      'tutorialInfo',
    ],
    data : () => {
      return {
      }
    },
    computed : {
    },
    methods : {
    },
    created(){},
    mounted(){},

  }

</script>


<style scoped>

#tutorial-modal {
  /* width: 320px;
  height: 380px; */
  width: 100%;
  height: 100%;
  background-color: white;
  border-radius: 2px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

#tutorial-modal .tutorial--modal--header {
  display: flex;
  align-items: center;
  width: 100%;
}

.page-wrapper {
  background: linear-gradient(-225deg,
  black, black 50%, white 50%, white);
  width: 50px;
  height: 50px;
}

.page-index {
  color: skyblue;
  font-size: 20px;
  margin-left: 3px;
}

.description-title {
  margin: 0;
  font-size: 18px;
}

#tutorial-modal .tutorial--img--wrapper {
  width: 90%;
  height: 40%;
  padding: 10px;
}

.tutorial--img {
  width: 100%;
  height: 100%;
}

.tutorial--description--wrapper {
  margin-top: 20px;
}


.red {color: red;}
.white {color:white;}



/* モーダル表示アニメーション */
.slidein-enter-active, .slidein-leave-active {
  opacity: 1;
  transform: translate(0px, 0px);
  transition: all 220ms;
}

.slidein-enter, .slidein-leave-to {
  opacity: 0;
  transform: translateX(12px);
}

</style>