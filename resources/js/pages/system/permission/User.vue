<template>
    <div class="row role-item">
        <div class="col-2">
            <div class="item-title">{{ $t('system.permission.users.title') }}</div>
        </div>
        <div class="col-8">
            <component-page-loading :status="userListStatus" v-if="['loading', 'error'].indexOf(userListStatus) >= 0" @reload="getUsers"></component-page-loading>
            <component-page-loading status="nodata" v-else-if="!userList.length"></component-page-loading>
            <div class="nub-list" v-else>
                <div class="nub-item" v-for="(item, index) in userList" :key="index">
                    <div class="nub-icon icon-user">
                        <font-awesome-icon icon="user-tie"></font-awesome-icon>
                    </div>
                    <div class="nub-text">{{item.email}}</div>
                </div>
            </div>
        </div>
        <div class="col-2 text-right">
            <a class="role-item-option" href="javascript:;" @click="dialogVisible = true">{{ $t('action.modify') }}</a>
        </div>
        <el-dialog :title="$t('system.permission.users.title')" width="700px" :visible.sync="dialogVisible">
            <div v-loading="loading">
                <div class="pb-3">
                    <div v-if="!userListCache.length">
                        <component-page-loading status="nodata"></component-page-loading>
                    </div>
                    <div class="nub-list" v-if="userListCache.length">
                        <div class="nub-item" v-for="(item, index) in userListCache" :key="index">
                            <div class="nub-icon icon-user">
                                <font-awesome-icon icon="user-tie"></font-awesome-icon>
                            </div>
                            <div class="nub-text">{{item.email}}</div>
                            <div class="nub-close" @click="tagCheckedDelete(item, index)">
                                <i class="el-icon-close"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pb-3">
                    <el-input :placeholder="$t('system.permission.users.dialog.email')" v-model="filterOption.keyword" clearable @change="filterChange">
                        <el-button slot="append" icon="el-icon-search" @click="filterChange"></el-button>
                    </el-input>
                </div>
                <el-table ref="multipleTable" :data="dataList"
                          style="width: 100%"
                          @select="handleSelection"
                          @select-all="handleSelectionAll">
                    <el-table-column width="80" type="selection"></el-table-column>
                    <el-table-column :label="$t('system.permission.users.dialog.email')" prop="email">
                        <template slot-scope="scope">
                            <span v-html="$options.filters.hsFilterKeyword(scope.row.email, filterOption.keyword)"></span>
                        </template>
                    </el-table-column>
                    <el-table-column :label="$t('system.permission.users.dialog.name')" prop="name"></el-table-column>
                </el-table>
                <div class="pt-2 text-right" v-if="dataMeta.total">
                    <el-pagination background layout="prev, pager, next"
                                   :current-page="filterOption.page"
                                   :page-size="filterOption.page_size"
                                   :total="dataMeta.total"
                                   @current-change="filterPageChange">
                    </el-pagination>
                </div>
            </div>
            <div slot="footer" class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ $t('action.cancel') }}</el-button>
                <el-button type="primary" @click="onSubmit">{{ $t('action.confirm') }}</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        name: "PermissionUser",
        props: ['tree'],
        data() {
            return {
                userListStatus: 'dle',
                userList: [],
                userListCache: [],
                dialogVisible: false,
                loading: false,
                filterOption: {
                    keyword: '',
                    page: 1,
                    page_size: 5,
                },
                dataMeta: {
                    total: 0,
                },
                dataList: []
            }
        },
        watch: {
            tree: {
                deep: true,
                immediate: true,
                handler(n, o) {
                    // 获取授权用户
                    this.getUsers();
                }
            },
            dialogVisible: function (n, o) {
                if (n) {
                    // 初始化选中数据
                    this.userListCache = _.cloneDeep(this.userList);
                } else {
                    // 初始化表格参数
                    this.clearFilterOption();
                }
            }
        },
        methods: {
            // 获取授权用户
            getUsers() {
                this.userListStatus = 'loading';
                axios.get('/system/permission/tree-user/getUsers', {
                    params: {
                        tree_id: this.tree.id
                    }
                }).then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.userList = response.data.resp_data;
                        this.userListStatus = 'dle';
                    } else {
                        this.userListStatus = 'error';
                    }
                })
            },
            clearFilterOption() {
                this.filterOption.keyword = '';
                this.filterOption.page = 1;
                this.dataMeta.total = 0;
                this.dataList = [];
            },
            filterChange() {
                this.filterOption.page = 1;
                this.getDataList();
            },
            filterPageChange(page) {
                this.filterOption.page = page;
                this.getDataList();
            },
            getDataList() {
                this.loading = true;
                axios.get('/system/permission/tree-user/getUserList', {
                    params: this.filterOption
                }).then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.dataMeta = response.data.resp_data.meta;
                        this.dataList = response.data.resp_data.data;
                        // 初始化选中表格
                        this.$nextTick(function () {
                            this.$refs.multipleTable.clearSelection();
                            // 初始化表格选中
                            for (let i in this.dataList) {
                                for (let j in this.userListCache) {
                                    if (this.userListCache[j].id == this.dataList[i].id) {
                                        this.$refs.multipleTable.toggleRowSelection(this.dataList[i]);
                                        break;
                                    }
                                }
                            }
                        });
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.search')}),
                            showClose: true
                        });
                    }
                    this.loading = false;
                })
            },
            onSubmit() {
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/permission/tree-user/update', {
                    tree_id: this.tree.id,
                    user_ids: _.map(this.userListCache, 'id')
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
                        this.userList = response.data.resp_data;
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.save')}),
                            showClose: true
                        });
                    }
                })
            },
            // 标签删除已选用户
            tagCheckedDelete(data, index) {
                this.userListCache.splice(index, 1);
                for (let i in this.dataList) {
                    if (this.dataList[i].id == data.id) {
                        this.$refs.multipleTable.toggleRowSelection(this.dataList[i]);
                        break;
                    }
                }
            },
            // 表格选择或取消选中用户
            handleSelection(selection, row) {
                for (let i in this.userListCache) {
                    if (this.userListCache[i].id == row.id) {
                        this.userListCache.splice(i, 1);
                        return;
                    }
                }
                this.userListCache.push(row);
            },
            // 表格选择或取消选中用户【全选】
            handleSelectionAll(selection) {
                if (_.isEmpty(selection)) {
                    for (let item of this.dataList) {
                        for (let i in this.userListCache) {
                            if (this.userListCache[i].id == item.id) {
                                this.userListCache.splice(i, 1);
                                break;
                            }
                        }
                    }
                } else {
                    for (let item of selection) {
                        let isExists = false;
                        for (let i in this.userListCache) {
                            if (this.userListCache[i].id == item.id) {
                                isExists = true;
                                break;
                            }
                        }
                        if (!isExists) {
                            this.userListCache.push(item);
                        }
                    }
                }
            },
        }
    }
</script>

<style lang="scss" scoped>
    /deep/ .el-dialog__body {
        min-height: 440px;
        padding-top: 15px;
        padding-bottom: 15px;
    }
</style>
