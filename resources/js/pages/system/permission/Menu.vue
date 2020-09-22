<template>
    <div class="row role-item">
        <div class="col-2">
            <div class="item-title">{{ $t('system.permission.menus.title') }}</div>
        </div>
        <div class="col-8">
            <component-page-loading :status="menuListStatus" v-if="['loading', 'error'].indexOf(menuListStatus) >= 0" @reload="getMenus"></component-page-loading>
            <component-page-loading status="nodata" v-else-if="!menuList.length"></component-page-loading>
            <div class="menu-tree-container" v-else>
                <el-tree :data="menuList" :props="{children: 'children',label: 'name'}" :expand-on-click-node="false">

                    <div class="tree-node" slot-scope="{ node, data }">
                        <div class="node-info">{{ $t(`menu.${data.route_name}`) }}</div>
                        <div class="node-option" v-if="menuActionList[data.id] != null">
                            <div class="node-option-item" v-for="(item, index) in menuActionList[data.id]" :key="index">
                                {{ $te(`actions.${item.type}.${item.action}`) ? $t(`actions.${item.type}.${item.action}`) : item.name }}
                            </div>
                        </div>
                    </div>

                </el-tree>
            </div>
        </div>
        <div class="col-2 text-right">
            <a class="role-item-option" href="javascript:;" @click="dialogVisible = true">{{ $t('action.modify') }}</a>
        </div>
        <el-dialog :title="$t('system.permission.menus.title')" width="700px" :visible.sync="dialogVisible">
            <div class="p-2" v-if="!dataList.length || dataListLoading!='dle'">
                <component-page-loading :status="dataListLoading" @reload="getDataList"></component-page-loading>
            </div>
            <vue-simplebar v-else-if="dialogVisible">
                <el-tree ref="treeMenu" @check-change="menuCheckChange"
                         :data="dataList"
                         :props="{children: 'children',label: 'name'}"
                         :expand-on-click-node="false"
                         show-checkbox
                         node-key="route_name"
                         :default-checked-keys="menus.route_names_leaf==null ? [] : menus.route_names_leaf">

                    <div class="tree-node" slot-scope="{ node, data }">
                        <div class="node-info">{{ $t(`menu.${data.route_name}`) }}</div>
                        <div class="node-option" v-if="menuActionsCache[data.id] != null" @click.stop>
                            <el-checkbox-group v-model="menuActionsCache[data.id]" :disabled="!node.checked">
                                <el-checkbox size="medium" :label="item.menu_action" v-for="(item, index) in dataActionList[data.id]" :key="index">
                                    {{ $te(`actions.${item.type}.${item.action}`) ? $t(`actions.${item.type}.${item.action}`) : item.name }}
                                </el-checkbox>
                            </el-checkbox-group>
                        </div>
                    </div>

                </el-tree>
            </vue-simplebar>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ $t('action.cancel') }}</el-button>
                <el-button type="primary" @click="onSubmit">{{ $t('action.confirm') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "PermissionMenu",
        props: ['tree'],
        data() {
            return {
                menuListStatus: 'dle',
                menuList: [],
                menus: {},
                menuActionList: {},
                menuActions: {},
                menuActionsCache: {},
                dialogVisible: false,
                dataListLoading: 'dle',
                dataList: [],
                dataActionList: {},
                dataActions: {},
            }
        },
        watch: {
            tree: {
                deep: true,
                immediate: true,
                handler(n, o) {
                    // 获取授权页面
                    this.getMenus();
                }
            },
            dialogVisible: function (n, o) {
                if (n) {
                    // 获取菜单数据
                    this.getDataList();
                } else {
                    // 初始化菜单数据
                    this.menuActionsCache = {};
                    this.dataActions = {};
                    this.dataActionList = {};
                    this.dataList = [];
                    this.dataListLoading = 'dle';
                }
            }
        },
        methods: {
            // 获取授权页面
            getMenus() {
                this.menuListStatus = 'loading';
                axios.get('/system/permission/tree-menu/getMenus', {
                    params: {
                        tree_id: this.tree.id
                    }
                }).then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.menuList = response.data.resp_data.menuList;
                        this.menus = response.data.resp_data.menus;
                        this.menuActionList = response.data.resp_data.menuActionList;
                        this.menuActions = response.data.resp_data.menuActions;
                        this.menuListStatus = 'dle';
                    } else {
                        this.menuListStatus = 'error';
                    }
                })
            },
            getDataList() {
                this.dataListLoading = 'loading';
                axios.get('/system/permission/tree-menu/getMenuList', {
                    params: {
                        tree_id: this.tree.isParentPermission ? this.tree.parent_id : this.tree.id
                    }
                }).then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.dataList = response.data.resp_data.menuList;
                        this.dataActionList = response.data.resp_data.menuActionList;
                        this.dataActions = response.data.resp_data.menuActions;
                        // 初始化页面功能
                        for (let menuId in this.dataActionList) {
                            this.$set(this.menuActionsCache, menuId, this.menuActions[menuId] || []);
                        }
                        this.dataListLoading = !this.dataList.length ? 'nodata' : 'dle';
                    } else {
                        this.dataListLoading = 'error';
                    }
                })
            },
            // 目录菜单勾选状态改变
            menuCheckChange(data) {
                if (this.dataActions[data.id] != null) {
                    // 获取当前节点
                    let node = this.$refs.treeMenu.getNode(data.route_name);
                    // 重置页面功能勾选状态
                    this.$set(this.menuActionsCache, data.id, node.checked ? this.dataActions[data.id] : []);
                }
            },
            onSubmit() {
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/permission/tree-menu/update', {
                    tree_id: this.tree.id,
                    menus: {
                        route_names: _.concat(this.$refs.treeMenu.getCheckedKeys(), this.$refs.treeMenu.getHalfCheckedKeys()),
                        route_names_leaf: this.$refs.treeMenu.getCheckedKeys(true),
                    },
                    menu_actions: this.menuActionsCache
                }).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.save')}),
                            showClose: true
                        });
                        this.dialogVisible = false;
                        // 同步数据
                        this.menuList = response.data.resp_data.menuList;
                        this.menus = response.data.resp_data.menus;
                        this.menuActionList = response.data.resp_data.menuActionList;
                        this.menuActions = response.data.resp_data.menuActions;
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.save')}),
                            showClose: true
                        });
                    }
                })
            },
        }
    }
</script>

<style lang="scss" scoped>
    /deep/ .el-dialog__body {
        padding-top: 15px;
        padding-bottom: 15px;
        height: 440px;
    }

    .menu-tree-container {
        border: 1px solid $boder-color-fourth;
        padding: 6px 0;
    }

    .tree-node {
        flex-direction: row;
        flex: auto;
        display: flex;
        align-items: center;
        font-size: $font-size-third;
    }

    .tree-node .node-info {
        flex-grow: 1;
        margin-right: 30px;
    }

    .tree-node .node-option {
        display: flex;
        flex-direction: row-reverse;
    }

    .tree-node .node-option-item {
        font-size: $font-size-fourth;
        margin-right: 15px;
    }

    .node-option /deep/ .el-checkbox {
        margin-right: 15px;
    }

    .node-option /deep/ .el-checkbox__label {
        font-size: $font-size-fourth;
        padding-left: 7px;
    }
</style>
