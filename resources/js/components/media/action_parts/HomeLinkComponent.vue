<template>
    <!-- HOME画面へのリンク付きロゴ -->
    <nav class="header-content logo-wrapper">
        <a class="logo" href="/home">Decomedia</a>
    </nav>
</template>

<script>

export default {
    components : {},
    props : [],
}

</script>

<style scoped>

.logo-wrapper{
    display: flex;
    justify-content: center;
    align-items: center;
    color: white;
    margin: 5px 20px;
    opacity: 0.9;
}

.logo-wrapper:hover {
  opacity: 1;
}

.logo {
    font-family: cursive;
}

/* aタグ全体の設定 */
a {
    color: white;
    text-decoration: none;
}
a:hover {
    color: aquamarine;
}


@media screen and (max-width:480px){
    .logo-wrapper {
        margin: 5px 0px;
    }
    .logo {
        font-size: 11px;
    }
    
}


</style>