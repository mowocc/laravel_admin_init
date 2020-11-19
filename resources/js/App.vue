<template>
    <el-container>
        <el-aside :width="asideWidth + 'px'" :class="{ 'drag-move' : dragStartStatus }">
            <div class="aside-logo">
                <img class="logo-img" src="/img/logo/logo.png"/>
                <span class="site-name" :title="$t('sitename')">{{ $t('sitename') }}</span>
            </div>
            <div class="aside-menu">
                <component-layout-menu></component-layout-menu>
            </div>
        </el-aside>
        <div class="drag-line" :style="{ left : asideWidth + 'px' }" @mousedown="mouseDown"></div>
        <el-container :class="{ 'drag-move' : dragStartStatus }">
            <el-header>
                <div class="header-content">
                    <div class="header-content-app">
                        <component-layout-server></component-layout-server>
                    </div>
                    <div class="header-content-info"></div>
                    <div class="header-content-user">
                        <component-layout-user></component-layout-user>
                    </div>
                </div>
            </el-header>
            <el-main>
                <!--路由器视图-->
                <router-view></router-view>
                <!--顶部进度条-->
                <vue-progress-bar></vue-progress-bar>
            </el-main>
        </el-container>
    </el-container>
</template>

<script>
    export default {
        data() {
            return {
                asideWidth: 180,
                dragStartStatus: false,
            }
        },
        created() {
            // 进度条[开始](第一次启动页面)
            this.$Progress.start()
            this.$router.beforeEach((to, from, next) => {
                if (to.name === undefined) {
                    // 页面不存在
                    next({name: 'error.404'})
                } else if (!to.meta.hasPermissionExcept && !this.$store.getters['admin/menu/hasPermissionTo'](to.name)) {
                    // 没有权限
                    next({name: 'error.403'})
                } else {
                    // 进度条[开始]
                    this.$Progress.start()
                    // 管道钩子
                    next()
                }
            })
            this.$router.afterEach((to, from) => {
                // 进度条[结束]
                this.$Progress.finish()
            })
            this.$router.onError(() => {
                // 进度条[错误]
                this.$Progress.fail()
            })
        },
        mounted() {
            // 进度条[结束](路由不存在时)
            this.$Progress.finish()
        },
        methods: {
            // 事件处理【鼠标按下】【局部】
            mouseDown($event) {
                // 标记开始拖动
                this.dragStartStatus = true;
                // 添加鼠标抬起事件【全局】
                document.addEventListener('mouseup', this.mouseUp)
                // 添加鼠标移动事件【全局】
                document.addEventListener('mousemove', this.mouseMove)
            },
            // 事件处理【鼠标抬起】
            mouseUp($event) {
                // 删除鼠标移动事件【全局】
                document.removeEventListener('mousemove', this.mouseMove)
                // 删除鼠标抬起事件【全局】
                document.removeEventListener('mouseup', this.mouseUp)
                // 标记结束拖动
                this.dragStartStatus = false;
            },
            // 事件处理【鼠标移动】
            mouseMove($event) {
                if (180 <= $event.screenX && $event.screenX <= 500) {
                    this.asideWidth = $event.screenX;
                }
            },
        },
    }
</script>

<style lang="scss">
    .drag-line {
        background-color: transparent;
        cursor: w-resize;
        height: 100vh;
        width: 4px;
        margin-left: -2px;
        position: fixed;
        bottom: 0;
        top: 0;
        z-index: 11;
    }

    .drag-move {
        user-select: none;
        cursor: w-resize;
    }

    .el-aside {
        /*width: $layout-aside-width !important;*/
        background-color: $layout-background-color;
        background-image: linear-gradient(0deg, $layout-background-color-min, $layout-background-color-max);
    }

    .el-header {
        padding: 0 !important;
        height: $layout-header-height !important;
        background-color: $layout-background-color-header;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .el-main {
        height: calc(100vh - #{$layout-header-height});
        padding: 0 !important;
    }

    .aside-logo {
        height: $layout-header-height;
        display: flex;
        align-items: center;
        background-color: $layout-background-color-logo;
        padding: 0 15px;
        color: #ffffff;
    }

    .aside-logo .logo-img {
        height: calc(#{$layout-header-height} - 30px);
    }

    .aside-logo .site-name {
        font-size: $font-size-third;
        margin-left: 0.25rem;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .aside-menu {
        height: calc(100vh - #{$layout-header-height});
    }

    .header-content {
        display: flex;
        flex-direction: row;
        flex: auto;
        height: 100%;
    }

    .header-content-info {
        flex: auto;
    }

    .header-content-app {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }

    .header-content-user {
        display: flex;
        flex-wrap: wrap;
        flex-shrink: 0;
    }
</style>
