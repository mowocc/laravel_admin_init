<template>
    <vue-simplebar>
        <div class="page-container">
            <div class="page-container-inner flex-auto">
                <div class="app-option-container" v-for="n in 2">
                    <h5 class="container-title">Komoditas</h5>
                    <div class="option-list">
                        <div class="option-list-item">
                            <div class="icon icon-red">
                                <font-awesome-icon icon="atom"></font-awesome-icon>
                            </div>
                            <div class="name">Energy</div>
                        </div>
                        <div class="option-list-item">
                            <div class="icon icon-green">
                                <font-awesome-icon icon="burn"></font-awesome-icon>
                            </div>
                            <div class="name">Softs</div>
                        </div>
                        <div class="option-list-item">
                            <div class="icon icon-yellow">
                                <font-awesome-icon icon="laugh"></font-awesome-icon>
                            </div>
                            <div class="name">Metals</div>
                        </div>
                        <div class="option-list-item">
                            <div class="icon icon-blue">
                                <font-awesome-icon icon="biking"></font-awesome-icon>
                            </div>
                            <div class="name">Grains</div>
                        </div>
                        <div class="option-list-item">
                            <div class="icon icon-purple">
                                <font-awesome-icon icon="globe-asia"></font-awesome-icon>
                            </div>
                            <div class="name">Livestock</div>
                        </div>
                        <div class="option-list-item">
                            <div class="icon">
                                <font-awesome-icon icon="icons"></font-awesome-icon>
                            </div>
                            <div class="name">Icons</div>
                        </div>
                    </div>
                </div>
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
    .app-option-container {
        margin: 20px 0;
    }

    .app-option-container .container-title {
        padding-left: 20px;
        margin-bottom: 30px;
    }

    .app-option-container .option-list {
        display: flex;
        flex-wrap: wrap;
    }

    .app-option-container .option-list-item {
        width: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        cursor: pointer;
        margin-bottom: 40px;
    }

    .app-option-container .option-list-item .name {
        text-align: center;
        margin-top: 15px;
    }

    .app-option-container .option-list-item .icon {
        width: 60px;
        height: 60px;
        border-radius: 30%;
        background-color: #98adc5;
        font-size: 1.6rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transform: rotate(45deg);
        transition: .3s;
    }

    .app-option-container .option-list-item:hover .icon {
        box-shadow: 0 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .app-option-container .option-list-item .icon [data-icon] {
        transform: rotate(-45deg);
    }

    .app-option-container .option-list-item .icon.icon-green {
        background-color: #7fc7bb;
    }

    .app-option-container .option-list-item .icon.icon-yellow {
        background-color: #ebb27c;
    }

    .app-option-container .option-list-item .icon.icon-blue {
        background-color: #7abeeb;
    }

    .app-option-container .option-list-item .icon.icon-purple {
        background-color: #bb89c9;
    }

    .app-option-container .option-list-item .icon.icon-red {
        background-color: #eb917f;
    }
</style>
