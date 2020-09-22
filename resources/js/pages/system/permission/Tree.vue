<template>
    <div class="h-100">
        <div class="p-2" v-if="!treeList.length || treeListLoading!='dle'">
            <component-page-loading :status="treeListLoading" @reload="getTreeList"></component-page-loading>
        </div>
        <vue-simplebar v-else>
            <el-tree highlight-current ref="treeRole"
                     node-key="id"
                     :indent="18"
                     :data="treeList"
                     :props="treeProps"
                     :expand-on-click-node="false"
                     @current-change="selectNode">
                <div class="tree-node" slot-scope="{ node, data }">
                    <div class="node-info">
                        <i class="el-icon-folder node-icon"></i>{{ data.name }}
                    </div>
                    <el-dropdown class="node-option"
                                 size="small"
                                 trigger="click"
                                 placement="bottom"
                                 @command="dialogNodeOption"
                                 @visible-change="nodeOptionVisibleChange">
                        <i class="el-icon-more" @click.stop="nodeOptionSelectNode(data, node)"></i>
                        <el-dropdown-menu slot="dropdown">
                            <el-dropdown-item command="update" v-if="hasParentPermission(data)">
                                <i class="el-icon-folder"></i>{{ $t('action.edit') }}
                            </el-dropdown-item>
                            <el-dropdown-item command="destroy" v-if="hasParentPermission(data)">
                                <i class="el-icon-folder-delete"></i>{{ $t('action.delete') }}
                            </el-dropdown-item>
                            <el-dropdown-item command="create">
                                <i class="el-icon-folder-add"></i>{{ $t('system.permission.tree.create') }}
                            </el-dropdown-item>
                            <el-dropdown-item disabled>ID : {{ data.id }}</el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </div>
            </el-tree>
        </vue-simplebar>
        <el-dialog :title="$t('system.permission.tree.dialog.create')" width="400px" :visible.sync="dialog.visible.create" @submit.native.prevent>
            <div class="pr-5">
                <el-form :model="dataCreate" label-width="80px">
                    <el-form-item :label="$t('system.permission.tree.dialog.parent')">
                        <el-tag type="info" size="mini">{{ dataCreate.parent_id }}</el-tag>
                        <span class="ml-1">{{ dataCreate.parent_name }}</span>
                    </el-form-item>
                    <el-form-item :label="$t('system.permission.tree.dialog.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                        <el-input v-model="dataCreate.name"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="treeNodeCreate">{{ $t('action.confirm') }}</el-button>
                        <el-button @click="dialog.visible.create = false">{{ $t('action.cancel') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-dialog>
        <el-dialog :title="$t('system.permission.tree.dialog.update')" width="400px" :visible.sync="dialog.visible.update">
            <div class="pr-5">
                <el-form :model="dataUpdate" label-width="80px">
                    <el-form-item :label="$t('system.permission.tree.dialog.sort')" :error="Boolean(msg.errors['sort']) ? msg.errors['sort'][0] : ''" required>
                        <el-input v-model="dataUpdate.sort" :placeholder="$t('system.permission.tree.dialog.sortPlaceholder')"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('system.permission.tree.dialog.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                        <el-input v-model="dataUpdate.name"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="treeNodeUpdate">{{ $t('action.confirm') }}</el-button>
                        <el-button @click="dialog.visible.update = false">{{ $t('action.cancel') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </el-dialog>
        <el-dialog :title="$t('system.permission.tree.dialog.destroy')" center width="340px" :visible.sync="dialog.visible.destroy">
            <div class="text-center" v-html="$t('confirm.tree-delete', { name: nodeCache.data.name })"></div>
            <div slot="footer">
                <el-button type="primary" @click="treeNodeDestroy">{{ $t('action.confirm') }}</el-button>
                <el-button @click="dialog.visible.destroy = false">{{ $t('action.cancel') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "PermissionTree",
        data() {
            return {
                dataList: [],
                treeListLoading: 'dle',
                treeList: [],
                treeProps: {
                    label: 'name',
                    children: 'children'
                },
                nodeCache: {
                    data: {},
                    node: {}
                },
                nodePreCache: {
                    node: {},
                    nodeKey: 0
                },
                dialog: {
                    visible: {
                        create: false,
                        update: false,
                        destroy: false
                    }
                },
                dataCreate: {
                    name: '',
                    parent_id: 0,
                    parent_name: 0
                },
                dataUpdate: {
                    id: 0,
                    sort: 0,
                    name: '',
                },
                msg: {
                    code: 200,
                    message: '',
                    errors: {},
                },
            }
        },
        computed: {
            isSuperAdmin() {
                return this.$store.state.admin.user.isSuperAdmin;
            }
        },
        watch: {
            treeList: {
                deep: true,
                handler(n, o) {
                    this.treeListLoading = !this.treeList.length ? 'nodata' : 'dle';
                }
            },
        },
        created: function () {
            this.getTreeList();
        },
        methods: {
            getTreeList() {
                this.treeListLoading = 'loading';
                axios.get('/system/permission/tree/getList').then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.treeList = response.data.resp_data.treeList;
                        this.dataList = response.data.resp_data.dataList;
                        // 初始化选中根节点
                        this.$nextTick(function () {
                            if (this.treeList.length) {
                                this.$refs.treeRole.setCurrentKey(this.treeList[0].id);
                                this.selectNode(this.treeList[0]);
                            }
                        })
                    } else {
                        this.treeListLoading = 'error';
                    }
                })
            },
            // 节点有父级权限
            hasParentPermission(data) {
                if (this.dataList[data.parent_id] != null) {
                    return true;
                } else if (this.isSuperAdmin) {
                    return true;
                }
                return false;
            },
            // 点击选中 tree 节点
            selectNode(data) {
                // 对外广播事件
                this.$emit('click', {
                    id: data.id,
                    name: data.name,
                    parent_id: data.parent_id,
                    isParentPermission: this.hasParentPermission(data),
                });
            },
            // 节点修改或删除
            nodeUpdateOrDelete(data) {
                if (this.nodeCache.node.key != this.nodePreCache.nodeKey) {
                    return false;
                }
                if (_.isEmpty(data)) {
                    this.$emit('click', {});
                } else {
                    this.selectNode(data);
                }
            },
            // 打开 option 弹窗扩展处理
            nodeOptionSelectNode(data, node) {
                // 缓存触发选中节点数据
                this.nodeCache.data = data;
                // 缓存触发选中节点
                this.nodeCache.node = node;
            },
            // 隐藏 option 弹窗扩展处理
            nodeOptionVisibleChange(visible) {
                if (visible) {
                    // 缓存当前选中节点
                    this.nodePreCache.node = this.$refs.treeRole.getCurrentNode();
                    // 缓存当前选中节点 key
                    this.nodePreCache.nodeKey = this.$refs.treeRole.getCurrentKey();
                    // 修改当前选中节点
                    this.$refs.treeRole.setCurrentKey(this.nodeCache.node.key);
                } else {
                    // 还原之前选中节点
                    this.$refs.treeRole.setCurrentKey(this.nodePreCache.nodeKey);
                }
            },
            // 分发 option 操作选项
            dialogNodeOption(option) {
                switch (option) {
                    case 'create-root':
                        this.initDataCreateRoot();
                        this.dialog.visible.create = true;
                        break;
                    case 'create':
                        this.initDataCreate();
                        this.dialog.visible.create = true;
                        break;
                    case 'update':
                        this.initDataUpdate();
                        this.dialog.visible.update = true;
                        break;
                    case 'destroy':
                        this.dialog.visible.destroy = true;
                        break;
                }
            },
            // 初始化参数
            initMsg() {
                this.msg.code = 200;
                this.msg.message = '';
                this.msg.errors = {};
            },
            // 初始化新增子节点数据【根节点】
            initDataCreateRoot() {
                this.initMsg();
                this.dataCreate.name = '';
                this.dataCreate.parent_id = 0;
                this.dataCreate.parent_name = '';
            },
            // 初始化新增子节点数据
            initDataCreate() {
                this.initMsg();
                this.dataCreate.name = '';
                this.dataCreate.parent_id = this.nodeCache.data.id;
                this.dataCreate.parent_name = this.nodeCache.data.name;
            },
            // 编初始化辑节点数据
            initDataUpdate() {
                this.initMsg();
                this.dataUpdate.id = this.nodeCache.data.id;
                this.dataUpdate.sort = this.nodeCache.data.sort;
                this.dataUpdate.name = this.nodeCache.data.name;
            },
            // 新增子节点
            treeNodeCreate() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/permission/tree/store', this.dataCreate).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.new')}),
                            showClose: true
                        });
                        this.dialog.visible.create = false;
                        // 返回节点数据
                        let data = response.data.resp_data;
                        // 添加节点数据到列表
                        this.$set(this.dataList, data.id, data);
                        // 添加节点到 tree 组件
                        if (data.parent_id == 0) {
                            this.treeList.push(data);
                        } else {
                            this.$refs.treeRole.append(data, this.nodeCache.node)
                        }
                    } else if (_.includes([42000], response.data.resp_msg.code)) {
                        this.msg = response.data.resp_msg;
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.new')}),
                            showClose: true
                        });
                    }
                })
            },
            // 编辑节点
            treeNodeUpdate() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/permission/tree/update', this.dataUpdate).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.edit')}),
                            showClose: true
                        });
                        this.dialog.visible.update = false;
                        // 同步数据到 tree 节点
                        this.treeNodeSyncData(response.data.resp_data);
                        // 处理对外节点数据
                        this.nodeUpdateOrDelete(response.data.resp_data);
                    } else if (_.includes([42000], response.data.resp_msg.code)) {
                        this.msg = response.data.resp_msg;
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.edit')}),
                            showClose: true
                        });
                    }
                })
            },
            // 同步数据到 tree 节点，并排序
            treeNodeSyncData(data) {
                // 更新列表数据
                this.dataList[data.id].name = data.name;
                this.dataList[data.id].sort = data.sort;
                // 更新当前节点
                this.nodeCache.data.name = data.name;
                this.nodeCache.data.sort = data.sort;
            },
            // 删除节点
            treeNodeDestroy() {
                this.dialog.visible.destroy = false;
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/permission/tree/destroy', {
                    id: this.nodeCache.data.id
                }).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.delete')}),
                            showClose: true
                        });
                        // 从列表删除节点数据
                        this.$delete(this.dataList, this.nodeCache.data.id);
                        //  从 tree 组件删除节点
                        this.$refs.treeRole.remove(this.nodeCache.node);
                        // 处理对外节点数据
                        this.nodeUpdateOrDelete(null);
                    } else if (response.data.resp_msg.code == 44221) {
                        this.$message({
                            type: 'warning',
                            message: this.$t('messages.delete-agent-failed'),
                            showClose: true
                        });
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.delete')}),
                            showClose: true
                        });
                    }
                })
            }
        },
    }
</script>

<style lang="scss" scoped>
    .tree-node {
        display: flex;
        flex-direction: row;
        flex: auto;
        align-items: center;
        font-size: $font-size-third;
    }

    .tree-node .node-info {
        flex: auto;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .tree-node .node-icon {
        color: $tree-color-icon;
        margin-right: 5px;
    }

    .tree-node .node-option {
        display: none;
        flex-shrink: 0;
        text-align: center;
        width: 50px;
    }

    .el-tree-node.is-current > .el-tree-node__content .tree-node .node-option,
    .el-tree-node__content:hover .node-option {
        display: inline-block;
    }
</style>
