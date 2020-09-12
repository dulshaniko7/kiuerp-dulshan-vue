<template>
    <div>
        <Navi></Navi>
        <router-view></router-view>
    </div>
</template>

<script>
    import Test from "./components/Test";
    import Navi from "./components/sub/Navi";

    export default {
        components: {Navi, Test}
    }
</script>

<style scoped>

</style>
