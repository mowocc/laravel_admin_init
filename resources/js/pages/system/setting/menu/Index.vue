<template>
    <vue-simplebar>
        <div class="page-container" v-loading="loading">
            <div class="page-filter-option">
                <el-form :inline="true" :model="filterOption" class="d-flex flex-wrap">
                    <el-form-item class="el-form-item-small">
                        <el-select :placeholder="$t('system.setting.menu.deleted')" v-model="filterOption.deleted" clearable @change="filterChange">
                            <el-option v-for="(item, index) in $t('form.deletedList')" :key="index" :label="item.label" :value="item.value"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="filterChange">{{ $t('action.search') }}</el-button>
                    </el-form-item>
                    <el-form-item class="ml-auto mr-0">
                        <el-button type="primary" plain icon="el-icon-plus" @click="dialogItemCreateRoot">{{ $t('action.new') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="page-container-inner-table flex-auto pb-2">
                <el-table style="width: 100%"
                          :data="dataList"
                          :indent="20"
                          row-key="id"
                          default-expand-all
                          :tree-props="{children: 'children', hasChildren: 'hasChildren'}"
                          highlight-current-row>
                    <el-table-column min-width="300" :label="$t('system.setting.menu.name')" prop="id" class-name="menu-name">
                        <template slot-scope="scope">
                            <font-awesome-icon fixed-width class="icon" :icon="scope.row.icon" v-if="scope.row.icon"></font-awesome-icon>
                            <span class="ml-1">{{ $te(`menu.${scope.row.route_name}`) ? $t(`menu.${scope.row.route_name}`) : scope.row.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="200" :label="$t('system.setting.menu.route_path')" prop="route_path"></el-table-column>
                    <el-table-column min-width="200" :label="$t('system.setting.menu.route_name')" prop="route_name"></el-table-column>
                    <el-table-column min-width="100" :label="$t('system.setting.menu.sort')" prop="sort"></el-table-column>
                    <el-table-column min-width="100" :label="$t('system.setting.menu.deleted')" prop="deleted">
                        <template slot-scope="scope">
                            <el-tag effect="plain" size="mini" :type="!scope.row.deleted ? 'info' : 'danger'">
                                {{ $t('form.deletedList')[Number(!!scope.row.deleted)].label }}
                            </el-tag>
                            <span class="operation-options-icon">
                               <i class="el-icon-edit" @click="dialogItemDeleted(scope)"></i>
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="120" :label="$t('system.setting.menu.operation')" align="right" fixed="right">
                        <template slot-scope="scope">
                            <span class="operation-options-icon">
                                <i class="el-icon-view" @click="drawerItemDetails(scope)"></i>
                            </span>
                            <span class="operation-options-icon">
                                <i class="el-icon-edit-outline" @click="dialogItemUpdate(scope)"></i>
                            </span>
                            <span class="operation-options-icon">
                               <i class="el-icon-delete" @click="dialogItemDestroy(scope)"></i>
                            </span>
                            <span class="operation-options-icon">
                                <i class="el-icon-circle-plus-outline" @click="dialogItemCreate(scope)"></i>
                            </span>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </div>
        <el-dialog :title="$t('system.setting.menu.dialog.deleted')" center width="320px" :visible.sync="dialog.visible.deleted">
            <div class="text-center" v-html="$t('confirm.menu', { status: !itemCache.deleted ? $t('action.off') : $t('action.on') })"></div>
            <div class="text-center text-danger-custom">
                {{ $t(!itemCache.deleted ? 'confirm.tree-children' : 'confirm.tree-parent', { status: !itemCache.deleted ? $t('action.off') : $t('action.on') }) }}
            </div>
            <div slot="footer">
                <el-button type="primary" @click="deleted">{{ $t('action.confirm') }}</el-button>
                <el-button @click="dialog.visible.deleted = false">{{ $t('action.cancel') }}</el-button>
            </div>
        </el-dialog>
        <el-dialog :title="$t('system.setting.menu.dialog.destroy')" center width="340px" :visible.sync="dialog.visible.destroy">
            <div class="text-center" v-html="$t('confirm.menu', { status: $t('action.delete') })"></div>
            <div class="text-center text-danger-custom">{{ $t('confirm.tree-children', { status: $t('action.delete') }) }}</div>
            <div class="text-center text-danger-custom">{{ $t('confirm.delete') }}</div>
            <div slot="footer">
                <el-button type="primary" @click="destroy">{{ $t('action.confirm') }}</el-button>
                <el-button @click="dialog.visible.destroy = false">{{ $t('action.cancel') }}</el-button>
            </div>
        </el-dialog>
        <item-create :visible.sync="dialog.visible.create" :data="itemCache" @create="filterChange"></item-create>
        <item-update :visible.sync="dialog.visible.update" :data="itemCache" @update="filterChange"></item-update>
        <item-details :visible.sync="drawer.visible.details" :data="itemCache"></item-details>
    </vue-simplebar>
</template>

<script>
    import ItemCreate from './ItemCreate.vue'
    import ItemUpdate from './ItemUpdate.vue'
    import ItemDetails from './ItemDetails.vue'

    export default {
        components: {
            ItemCreate,
            ItemUpdate,
            ItemDetails,
        },
        data() {
            return {
                loading: false,
                filterOption: {
                    deleted: '',
                },
                dataList: [],
                itemCache: {},
                dialog: {
                    visible: {
                        create: false,
                        update: false,
                        deleted: false,
                        destroy: false,
                    }
                },
                drawer: {
                    visible: {
                        details: false,
                    }
                }
            }
        },
        created: function () {
            // 初始化数据
            this.filterChange();
        },
        methods: {
            filterChange() {
                this.loading = true;
                this.getDataList();
            },
            getDataList() {
                axios.get('/system/setting/menu/getList', {
                    params: this.filterOption
                }).then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.dataList = response.data.resp_data;
                    } else {
                        this.$message({
                            type: 'error',
                            message: response.data.resp_msg.message,
                            showClose: true
                        });
                    }
                    this.loading = false;
                })
            },
            // 删除数据/恢复数据
            deleted() {
                // 关闭提示框
                this.dialog.visible.deleted = false;
                // loading 状态 start
                let loading = this.$loading();
                // 删除/恢复
                let action = !this.itemCache.deleted ? 'destroy' : 'restore';
                // 保存数据
                axios.post(`/system/setting/menu/${action}`, {
                    id: this.itemCache.id
                }).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {
                                status: !this.itemCache.deleted ? this.$t('action.off') : this.$t('action.on')
                            }),
                            showClose: true
                        });
                        // 刷新列表
                        this.filterChange();
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {
                                status: !this.itemCache.deleted ? this.$t('action.off') : this.$t('action.on')
                            }),
                            showClose: true
                        });
                    }
                })
            },
            // 彻底删除数据
            destroy() {
                // 关闭提示框
                this.dialog.visible.destroy = false;
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/setting/menu/forceDestroy', {
                    id: this.itemCache.id
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
                        // 刷新列表
                        this.filterChange();
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.delete')}),
                            showClose: true
                        });
                    }
                })
            },
            // 显示模态框（新增根菜单）
            dialogItemCreateRoot() {
                // 缓存数据
                this.itemCache = {id: 0, name: ''};
                // 显示模态框
                this.dialog.visible.create = true;
            },
            // 显示模态框（新增子菜单）
            dialogItemCreate(scope) {
                // 缓存数据
                this.itemCache = scope.row;
                // 显示模态框
                this.dialog.visible.create = true;
            },
            // 显示模态框（编辑）
            dialogItemUpdate(scope) {
                // 缓存数据
                this.itemCache = scope.row;
                // 显示模态框
                this.dialog.visible.update = true;
            },
            // 显示模态框（删除/恢复）
            dialogItemDeleted(scope) {
                // 缓存数据
                this.itemCache = scope.row;
                // 显示模态框
                this.dialog.visible.deleted = true;
            },
            // 显示模态框（彻底删除）
            dialogItemDestroy(scope) {
                // 缓存数据
                this.itemCache = scope.row;
                // 显示模态框
                this.dialog.visible.destroy = true;
            },
            // 打开抽屉（详情）
            drawerItemDetails(scope) {
                // 缓存数据
                this.itemCache = scope.row;
                // 显示抽屉
                this.drawer.visible.details = true;
            },
        }
    }
</script>

<style lang="scss" scoped>
    /deep/ .menu-name .cell {
        display: flex;
        align-items: center;
    }

    /deep/ .menu-name .icon {
        width: 20px;
        color: $tree-color-icon;
    }

    /deep/ .el-table [class*=el-table__row--level] .el-table__expand-icon {
        margin-right: 0;
    }
</style>
