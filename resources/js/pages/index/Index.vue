<template>
    <vue-simplebar>
        <div class="page-container">
            <div class="page-container-inner flex-auto">
                {{ $t($route.meta.title) }}
            </div>
        </div>
    </vue-simplebar>
</template>

<script>
    export default {
        name: "Index"
    }
</script>

<style scoped>

</style>
