<template>
    <vue-simplebar>
        <div class="page-container" v-loading="loading">
            <div class="page-filter-option">
                <el-form :inline="true" :model="filterOption" class="d-flex flex-wrap">
                    <el-form-item>
                        <el-input :placeholder="$t('system.log.activity.causer') + ' ID / ' + $t('system.log.activity.subject')" prefix-icon="el-icon-search" clearable
                                  v-model="filterOption.keyword" @change="filterChange"></el-input>
                    </el-form-item>
                    <el-form-item class="el-form-item-small">
                        <el-select :placeholder="$t('system.log.activity.description')" v-model="filterOption.description" clearable @change="filterChange">
                            <el-option v-for="(item, index) in ['created', 'updated', 'deleted', 'restored']" :key="index" :label="item" :value="item"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button @click="filterChange">{{ $t('action.search') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
            <div class="page-container-inner-table flex-auto">
                <el-table ref="tableActivity" style="width: 100%"
                          :data="dataList"
                          :default-sort="{prop: filterOption.order_column, order: filterOption.order_key}"
                          @sort-change="filterOrderChange"
                          highlight-current-row>
                    <el-table-column min-width="150" :label="$t('system.log.activity.causer')">
                        <template slot-scope="scope">
                            <el-tag effect="plain" size="mini" type="info" v-if="filterOption.keyword.search(':') != -1">{{ String(scope.row.causer_id) }}</el-tag>
                            <el-tag effect="plain" size="mini" type="info" v-else v-html="$options.filters.hsFilterKeyword(String(scope.row.causer_id), filterOption.keyword)"></el-tag>
                            <span v-if="scope.row.causer != null">{{ scope.row.causer.name }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="150" :label="$t('system.log.activity.description')">
                        <template slot-scope="scope">
                            <el-tag effect="plain" size="mini" :type="tagTypes[scope.row.description] || 'info'">
                                {{scope.row.description }}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="320" :label="$t('system.log.activity.subject')">
                        <template slot-scope="scope">
                            <em v-if="hasSubjectString(scope.row, filterOption.keyword)">{{ filterOption.keyword }}</em>
                            <span v-else>{{ scope.row.subject_type }}:{{ scope.row.subject_id }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="200" :label="$t('system.log.activity.log_name')" prop="log_name"></el-table-column>
                    <el-table-column min-width="220" :label="$t('system.log.activity.created')" prop="created" sortable="custom" :sort-orders="['descending', 'ascending']">
                        <template slot-scope="scope">
                            <component-page-timestamp :timestamp="scope.row.created"></component-page-timestamp>
                        </template>
                    </el-table-column>
                    <el-table-column min-width="100" :label="$t('system.log.activity.operation')" align="right" fixed="right">
                        <template slot-scope="scope">
                            <span class="operation-options-icon">
                                <i class="el-icon-view" @click="drawerItemDetails(scope)"></i>
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
        <item-details :visible.sync="drawer.visible.details" :data="itemCache" :tagTypes="tagTypes"></item-details>
    </vue-simplebar>
</template>

<script>
    import ItemDetails from './ItemDetails.vue'

    export default {
        components: {
            ItemDetails,
        },
        data() {
            return {
                loading: false,
                filterOption: {
                    description: '',
                    keyword: '',
                    page: 1,
                    page_size: 10,
                    order: 'desc',
                    order_key: 'descending',
                    order_column: 'created',
                },
                dataMeta: {
                    total: 0,
                },
                dataList: [],
                itemCache: {},
                drawer: {
                    visible: {
                        details: false,
                    }
                },
                tagTypes: {
                    'created': 'success',
                    'updated': 'warning',
                    'deleted': 'danger',
                    'restored': 'success',
                },
            }
        },
        created: function () {
            // 初始化数据
            this.filterChange();
        },
        methods: {
            hasSubjectString: function (data, keyword) {
                // 删除首尾空格
                keyword = String(keyword).replace(/(^\s*)|(\s*$)/g, '');
                // 判断对象匹配
                return this.makeSubjectString(data) == keyword;
            },
            makeSubjectString: function (data) {
                return [data.subject_type, data.subject_id].join(':');
            },
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
                axios.get('/system/log/activity/getList', {
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

<style lang="scss">

</style>
