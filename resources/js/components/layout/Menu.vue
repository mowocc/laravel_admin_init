<template>
    <div class="p-2" v-if="!menus.length || menusStatus!='dle'">
        <component-page-loading :status="menusStatus" @reload="getMenus"></component-page-loading>
    </div>
    <vue-simplebar class="theme-bright" v-else>
        <el-menu :router="true" :default-active="$route.path" class="el-menu-router">
            <!--递归创建目录菜单-->
            <menu-tree :menus="menus"></menu-tree>
        </el-menu>
    </vue-simplebar>
</template>

<script>
    import MenuTree from './MenuTree.vue'

    export default {
        components: {
            MenuTree,
        },
        name: "LayoutMenu",
        data: function () {
            return {
                menusStatus: 'dle',
                menus: []
            }
        },
        created: function () {
            this.getMenus();
        },
        methods: {
            getMenus() {
                this.menusStatus = 'loading';
                axios.get('/admin/getMenus').then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.menus = response.data.resp_data.menus;
                        this.menusStatus = !this.menus.length ? 'nodata' : 'dle';
                        // Vuex 全局存储
                        this.$store.commit('admin/menu/init', response.data.resp_data);
                        // 路由初始化完成
                        this.$router.onReady(() => {
                            // 跳转到首个页面
                            this.routeToFirstMenu(response.data.resp_data.routeNamesLeaf);
                        });
                    } else {
                        this.menusStatus = 'error';
                    }
                });
            },
            routeToFirstMenu(routeNamesLeaf) {
                if (this.$route.meta.hasPermissionExcept || this.$store.getters['admin/menu/hasPermissionTo'](this.$route.name)) {
                    return false;
                }
                if (routeNamesLeaf.length) {
                    return this.$router.push({name: routeNamesLeaf[0]});
                }
            }
        },
    }
</script>

<style lang="scss" scoped>
    .el-menu-router /deep/ .fa-fw {
        width: 20px;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }

    .el-menu-router /deep/,
    .el-menu-router /deep/ .el-menu {
        background-color: inherit;
        border-right: inherit;
    }

    .el-menu-router /deep/ .el-menu-item,
    .el-menu-router /deep/ .el-submenu__title {
        transition: inherit;
        border-left: 3px solid transparent;
        color: $menu-font-color;

        height: 50px;
        line-height: 50px;
        font-size: $font-size-third;

        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
    }

    .el-menu-router /deep/ .el-menu-item:focus,
    .el-menu-router /deep/ .el-menu-item:hover,
    .el-menu-router /deep/ .el-submenu__title:focus,
    .el-menu-router /deep/ .el-submenu__title:hover {
        background-color: inherit;
        color: $menu-hover-font-color;
    }

    .el-menu-router /deep/ .el-menu-item.is-active {
        background-color: $menu-hover-background-color;
        border-left-color: $menu-hover-border-color;
        color: $menu-hover-font-color;
    }

    .el-menu-router /deep/ .el-menu-item,
    .el-menu-router /deep/ .el-submenu__title,
    .el-menu-router /deep/ .el-submenu .el-menu-item {
        min-width: auto;
        padding: 0;
    }
</style>
