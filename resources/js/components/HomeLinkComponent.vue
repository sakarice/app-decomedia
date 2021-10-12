<template>
    <!-- HOME画面へのリンク付きロゴ -->
    <div class="header-content">
        <a class="logo" href="/home">Decomedia</a>
    </div>
</template>

<script>

export default {
    components : {},
    props : [],
}

</script>

<style scoped>

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


</style>