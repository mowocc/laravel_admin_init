<template>
    <vue-simplebar>
        <div class="page-container">
            <div class="page-container-inner flex-auto">
                <div class="app-option-container">
                    <div class="game-list">
                        <div class="game-list-item" v-for="n in 4">
                            <div class="game-card">
                                <div class="game-card-body">
                                    <el-image>
                                        <div slot="error" class="image-slot">
                                            <i class="el-icon-picture-outline"></i>
                                        </div>
                                    </el-image>
                                    <div class="pt-3 text-truncate text-center">星际传奇</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </vue-simplebar>
</template>

<script>
    export default {
        name: "Game"
    }
</script>

<style lang="scss" scoped>
    .app-option-container {
        margin: 20px;
    }

    .game-list {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        margin: -10px;
    }

    .game-list-item {
        padding: 10px;
        width: 240px;
        position: relative;
    }

    .game-card {
        overflow: hidden;
        border-radius: 7px;
        background-color: #fff;
        border: 1px solid $boder-color-fourth;
        transition: .3s;
        cursor: pointer;
    }

    .game-card:hover {
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, .1);
    }

    .game-card-body {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 20px;
        color: $font-color-first;
    }

    .game-card-body .el-image {
        height: 120px;
        width: 100%;
        background: #f5f7fa;
    }

    /deep/ .game-card-body .image-slot {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: $body-bg;
        color: $font-color-third;
        font-size: 25px;
    }
</style>
