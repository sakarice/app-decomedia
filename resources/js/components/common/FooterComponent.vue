<template>
    <footer id="footer">
        <div class="footer-area">
            <!-- 上段 -->
            <div class="footer-block-upper">
                <!-- 左側 -->
                <div class="footer-block-upper-left">
                </div>
                <!-- 右側 -->
                <div class="footer-block-upper-right">
                </div>
            </div>

            <!-- 下段 -->
            <div class="footer-block-bottom">
                <p class="copy-right white">Copyright ©2022 hiroshi sakamoto</p>
            </div>

        </div>
    </footer>
        
</template>



<script>
import { mapState, mapGetters } from "vuex";

export default {
    components : {
    },
    props : [],
    data : function(){
        return {
        }
    },
    computed : {
    },
    methods : {
        
    },
    mounted(){}
}

</script>

<style scoped>

#footer {
  z-index: 2;
  background-color: rgba(0, 0, 0, 1);
  padding: 5px;
  margin-top: 50px;
  width: 100%;
  height: 200px;
}

.footer-area {
  color: white;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
}

.footer-right {
  display: flex;
  justify-content: space-around;
}

.copy-right { font-size: 11px;}

/* 
カラー設定
 */
 
.white { color: white;}



</style>