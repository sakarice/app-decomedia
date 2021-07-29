<template>
    <div class="left-bar">
        <!-- マイページトップ -->
        <div class="my-page-top">
            <div class="content">
                <a class="logo" href="/home/mypage">
                    <i class="fas fa-door-open"></i>
                </a>
            </div>
        </div>

        <!-- 作成済みroom一覧 -->
        <div class="created-rooms">
            <div class="content">
                <!-- <a class="logo" href="/home/createdRoom"> -->
                    <!-- <i class="fas fa-hammer"></i> -->
                    <i class="fas fa-clone"></i>
                <!-- </a> -->
            </div>
        </div>

        <!-- お気に入りroom一覧 -->
        <div class="liked-rooms">
            <div class="content">
                <!-- <a class="logo" href="/home/createdRoom"> -->
                    <i class="far fa-heart"></i>
                <!-- </a> -->
            </div>
        </div>

    </div>
</template>



<script>

export default {
    components : {},
    props : [
        'csrf',
    ],
    data : () => {
        return {
        }
    },
    methods : {
        
    },
    mounted(){}
}

</script>

<style scoped>

.left-bar {
  position: fixed;
  top: 0;
  z-index: 1;
  width: 40px;
  height: 100%;
  padding: 50px 10px 10px 10px;
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
    margin: 20px 5px;
    opacity: 0.9;
}

.content:hover {
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

</style>