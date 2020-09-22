<template>
    <vue-simplebar>
        <div class="page-container">
            <user-info></user-info>
            <update-password></update-password>
        </div>
    </vue-simplebar>
</template>

<script>
    import UserInfo from './UserInfo.vue'
    import UpdatePassword from './UpdatePassword.vue'

    export default {
        components: {
            UserInfo,
            UpdatePassword,
        },
    }
</script>

<style scoped>
    /deep/ .el-form {
        max-width: 460px;
    }
</style>
