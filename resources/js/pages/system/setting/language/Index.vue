<template>
    <vue-simplebar>
        <div class="page-container" v-loading="loading">
            <div class="page-filter-option">
                <el-form :inline="true" :model="filterOption" class="d-flex flex-wrap">
                    <el-form-item>
                        <el-input :placeholder="$t('system.setting.language.name')" prefix-icon="el-icon-search" clearable
                                  v-model="filterOption.keyword" @change="filterChange"></el-input>
                    </el-form-item>
                    <el-form-item class="el-form-item-small">
                        <el-select :placeholder="$t('system.setting.language.deleted')" v-model="filterOption.deleted" clearable @change="filterChange">
                            <el-option v-for="(item, index) in $t('form.deletedList')" :key="index" :label="item.label" :value="item.value"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="filterChange">{{ $t('action.search') }}</el-button>
                    </el-form-item>
                    <el-form-item class="ml-auto mr-0">
                        <el-button type="primary" plain icon="el-icon-plus" @click="dialogItemCreate">{{ $t('action.new') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="page-container-inner-table flex-auto">
                <el-table style="width: 100%"
                          :data="dataList"
                          :default-sort="{prop: filterOption.order_column, order: filterOption.order_key}"
                          @sort-change="filterOrderChange"
                          highlight-current-row>
                    <el-table-column min-width="80" label="ID" prop="id" sortable="custom" :sort-orders="['descending', 'ascending']"></el-table-column>
                    <el-table-column min-width="150" :label="$t('system.setting.language.name')" prop="name">
                        <template slot-scope="scope">
                            <span v-html="$options.filters.hsFilterKeyword(scope.row.name, filterOption.keyword)"></span>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="150" :label="$t('system.setting.language.lang')" prop="mysql_agency" show-overflow-tooltip>
                        <template slot-scope="scope">{{ scope.row.lang }}</template>
                    </el-table-column>
                    <el-table-column min-width="220" :label="$t('system.setting.language.updated')" prop="updated" sortable="custom" :sort-orders="['descending', 'ascending']">
                        <template slot-scope="scope">
                            <component-page-timestamp :timestamp="scope.row.updated"></component-page-timestamp>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="100" :label="$t('system.setting.language.deleted')" prop="deleted">
                        <template slot-scope="scope">
                            <el-tag effect="plain" size="mini" :type="!scope.row.deleted ? 'info' : 'danger'">
                                {{ $t('form.deletedList')[Number(!!scope.row.deleted)].label }}
                            </el-tag>
                            <span class="operation-options-icon">
                               <i class="el-icon-edit" @click="dialogItemDeleted(scope)"></i>
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="100" :label="$t('system.setting.language.operation')" align="right" fixed="right">
                        <template slot-scope="scope">
                            <span class="operation-options-icon">
                                <i class="el-icon-edit-outline" @click="dialogItemUpdate(scope)"></i>
                            </span>
                            <span class="operation-options-icon">
                               <i class="el-icon-delete" @click="dialogItemDestroy(scope)"></i>
                            </span>
                        </template>
                    </el-table-column>
                </el-table>
                <div class="pt-4 text-right pb-4">
                    <el-pagination background layout="total, sizes, prev, pager, next, jumper"
                                   :current-page="filterOption.page"
                                   :page-size.sync="filterOption.page_size"
                                   :total="dataMeta.total"
                                   @size-change="filterChange"
                                   @current-change="filterPageChange">
                    </el-pagination>
                </div>
            </div>
        </div>
        <el-dialog :title="$t('system.setting.language.dialog.deleted')" center width="320px" :visible.sync="dialog.visible.deleted">
            <div class="text-center" v-html="$t('confirm.language', { status: !itemCache.deleted ? $t('action.off') : $t('action.on') })"></div>
            <div slot="footer">
                <el-button type="primary" @click="deleted">{{ $t('action.confirm') }}</el-button>
                <el-button @click="dialog.visible.deleted = false">{{ $t('action.cancel') }}</el-button>
            </div>
        </el-dialog>
        <el-dialog :title="$t('system.setting.language.dialog.destroy')" center width="340px" :visible.sync="dialog.visible.destroy">
            <div class="text-center" v-html="$t('confirm.language', { status: $t('action.delete') })"></div>
            <div class="text-center text-danger-custom">{{ $t('confirm.delete') }}</div>
            <div slot="footer">
                <el-button type="primary" @click="destroy">{{ $t('action.confirm') }}</el-button>
                <el-button @click="dialog.visible.destroy = false">{{ $t('action.cancel') }}</el-button>
            </div>
        </el-dialog>
        <item-create :visible.sync="dialog.visible.create" @create="filterChange"></item-create>
        <item-update :visible.sync="dialog.visible.update" :data="itemCache" @update="filterChange"></item-update>
    </vue-simplebar>
</template>

<script>
    import ItemCreate from './ItemCreate.vue'
    import ItemUpdate from './ItemUpdate.vue'

    export default {
        components: {
            ItemCreate,
            ItemUpdate,
        },
        data() {
            return {
                loading: false,
                filterOption: {
                    deleted: '',
                    keyword: '',
                    page: 1,
                    page_size: 10,
                    order: 'asc',
                    order_key: 'ascending',
                    order_column: 'id',
                },
                dataMeta: {
                    total: 0,
                },
                dataList: [],
                itemCache: {},
                itemCacheIndex: null,
                dialog: {
                    visible: {
                        create: false,
                        update: false,
                        deleted: false,
                        destroy: false,
                    }
                },
            }
        },
        created: function () {
            // 初始化数据
            this.filterChange();
        },
        methods: {
            clearFilterOption() {
                this.loading = true;
                this.filterOption.page = 1;
            },
            filterChange() {
                this.clearFilterOption();
                this.getDataList();
            },
            filterPageChange(page) {
                this.loading = true;
                this.filterOption.page = page;
                this.getDataList();
            },
            filterOrderChange(scope) {
                this.clearFilterOption();
                this.filterOption.order = scope.order == 'ascending' ? 'asc' : 'desc';
                this.filterOption.order_key = scope.order;
                this.filterOption.order_column = scope.prop;
                this.getDataList();
            },
            getDataList() {
                axios.get('/system/setting/language/getList', {
                    params: this.filterOption
                }).then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.dataMeta = response.data.resp_data.meta;
                        this.dataList = response.data.resp_data.data;
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
                axios.post(`/system/setting/language/${action}`, {
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
                axios.post('/system/setting/language/forceDestroy', {
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
            // 显示模态框（新增）
            dialogItemCreate() {
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
        }
    }
</script>

<style lang="scss">

</style>
