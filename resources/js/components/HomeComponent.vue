<template>
    <div>
        <div>初めてのVue Resource</div>
        <router-link to="/test">testへ</router-link>
        <router-view></router-view>
        <!-- <overlay></overlay> -->
        <!-- <loading></loading> -->
    </div>
</template>

<script>
import Overlay from './OverlayComponent.vue'
import Loading from './LoadingComponent.vue'

export default {
    components : {
        Overlay,
        Loading,
    },
    props : [],
}

</script>

<style scoped>

</style>