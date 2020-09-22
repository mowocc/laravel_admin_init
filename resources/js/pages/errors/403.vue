<template>
    <div class="page-container">
        <div class="page-container-inner flex-auto flex-center">
            <div class="error">
                <div class="flex-center">
                    <i class="icon el-icon-warning-outline"></i>
                    <span class="info">{{ $t('error.403') }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {}
</script>


<style scoped>
    .error {
        color: #F56C6C;
    }

    .error .icon {
        font-size: 2rem;
    }

    .error .info {
        margin-left: 0.5rem;
        font-size: 1.5rem;
    }
</style>
