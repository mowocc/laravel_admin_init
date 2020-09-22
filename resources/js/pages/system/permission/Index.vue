<template>
    <div class="page-container">
        <div class="page-layout">
            <div class="page-layout-header">
                <div class="header-aside flex-start-title">
                    <div class="title">{{ $t('system.permission.titleSide') }}</div>
                    <div class="option" v-if="isSuperAdmin">
                        <div class="icon" :title="$t('system.permission.tree.create-root')" @click="$refs.tree.dialogNodeOption('create-root')">
                            <i class="el-icon-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="header-main">
                    <div class="title">{{ $t('system.permission.title') }}</div>
                </div>
            </div>
            <div class="page-layout-body">
                <div class="body-aside">
                    <!--权限树-->
                    <permission-tree ref="tree" @click="treeSelectNode"></permission-tree>
                </div>
                <div class="body-main">
                    <vue-simplebar>
                        <div class="flex-center p-2" v-if="!tree.id">
                            <component-page-loading status="nodata"></component-page-loading>
                        </div>
                        <div class="container" v-else>
                            <div class="role-container">
                                <!-------------------授权名称---------------->
                                <div class="row role-item">
                                    <div class="col-2">
                                        <div class="item-title">{{ $t('system.permission.name') }}</div>
                                    </div>
                                    <div class="col-10">
                                        <div class="item-tree-name">{{tree.name}}</div>
                                    </div>
                                </div>
                                <!-------------------授权用户---------------->
                                <permission-user :tree="tree"></permission-user>
                                <!-------------------授权页面---------------->
                                <permission-menu :tree="tree"></permission-menu>
                            </div>
                        </div>
                    </vue-simplebar>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PermissionTree from './Tree.vue'
    import PermissionUser from './User.vue'
    import PermissionMenu from './Menu.vue'

    export default {
        components: {
            PermissionTree,
            PermissionUser,
            PermissionMenu,
        },
        data() {
            return {
                tree: {},
            }
        },
        computed: {
            isSuperAdmin() {
                return this.$store.state.admin.user.isSuperAdmin;
            }
        },
        methods: {
            treeSelectNode(data) {
                // 缓存数据
                this.tree = data;
            },
        }
    }
</script>

<style lang="scss" scoped>
    .header-aside .icon {
        flex-shrink: 0;
        width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        color: $font-color-third;
    }

    .header-aside .icon:hover {
        color: $font-color-second;
    }

    .role-container {
        padding: 15px;
        font-size: $font-size-third;
    }

    .role-item {
        padding: 30px 0;
    }

    .role-item + .role-item {
        border-top: 1px solid $boder-color-fourth;
    }

    .role-item .item-tree-name {
        color: $font-color-first;
    }

    .role-item .item-title {
        color: $font-color-first;
    }

</style>
