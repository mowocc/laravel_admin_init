<template>
    <el-drawer :title="$t('system.setting.menu.drawer.details')" size="400px" :visible.sync="drawerVisible" @close="$emit('update:visible', false)">
        <vue-simplebar>
            <div class="pt-5 pb-5">
                <el-form label-width="120px" class="el-form-detail">
                    <el-form-item label="ID">{{ data.id }}</el-form-item>
                    <el-form-item :label="$t('system.setting.menu.name')" class="menu-name">
                        <font-awesome-icon fixed-width class="icon" :icon="data.icon" v-if="data.icon"></font-awesome-icon>
                        <span class="ml-1">{{ $te(`menu.${data.route_name}`) ? $t(`menu.${data.route_name}`) : data.name }}</span>
                    </el-form-item>
                    <el-form-item :label="$t('system.setting.menu.route_path')">{{ data.route_path }}</el-form-item>
                    <el-form-item :label="$t('system.setting.menu.route_name')">{{ data.route_name }}</el-form-item>
                    <el-form-item :label="$t('system.setting.menu.sort')">{{ data.sort }}</el-form-item>
                    <el-form-item :label="$t('system.setting.menu.created')">
                        <component-page-timestamp :timestamp="data.created"></component-page-timestamp>
                    </el-form-item>
                    <el-form-item :label="$t('system.setting.menu.updated')">
                        <component-page-timestamp :timestamp="data.updated"></component-page-timestamp>
                    </el-form-item>
                    <el-form-item :label="$t('system.setting.menu.deleted')">
                        <el-tag effect="plain" size="mini" :type="!data.deleted ? 'info' : 'danger'">
                            {{ $t('form.deletedList')[Number(!!data.deleted)].label }}
                        </el-tag>
                    </el-form-item>
                    <!--权限-->
                    <div class="pt-4 pl-4 pr-4 pb-3">
                        <el-divider>{{ $t('system.setting.menu.drawer.actions.title') }}</el-divider>
                    </div>
                    <div class="pl-4 pr-4" v-if="!editActionStatus">
                        <div class="text-center" v-if="dataListLoading!='dle'">
                            <component-page-loading :status="dataListLoading" @reload="getDataList"></component-page-loading>
                        </div>
                        <div v-else>
                            <el-table border stripe size="mini" :data="dataList" style="width: 100%">
                                <el-table-column align="center" min-width="100" :label="$t('system.setting.menu.drawer.actions.name')" prop="name">
                                    <template slot-scope="scope">{{ $te(`actions.system.${scope.row.action}`) ? $t(`actions.system.${scope.row.action}`) : scope.row.name }}</template>
                                </el-table-column>
                                <el-table-column align="center" min-width="100" :label="$t('system.setting.menu.drawer.actions.action')" prop="action"></el-table-column>
                            </el-table>
                            <div class="text-right mt-3">
                                <el-button size="small" @click="editData">{{ $t('action.edit') }}</el-button>
                            </div>
                        </div>
                    </div>
                </el-form>
                <!--编辑权限-->
                <el-form label-width="120px" size="small " v-if="editActionStatus">
                    <el-form-item :label="$t('system.setting.menu.drawer.actions.system')">
                        <el-checkbox-group v-model="actionsCache.system">
                            <el-col :span="10" v-for="(item, index) in systemActions" :key="index">
                                <el-checkbox :label="item.action">{{ item.name }}</el-checkbox>
                            </el-col>
                        </el-checkbox-group>
                    </el-form-item>
                    <el-form-item :label="!index ?$t('system.setting.menu.drawer.actions.custom') : ''" v-for="(item, index) in actionsCache.custom" :key="index"
                                  :error="Boolean(msg.errors[`actions.custom.${index}.name`]) ? msg.errors[`actions.custom.${index}.name`][0] : '' ||
                                                Boolean(msg.errors[`actions.custom.${index}.action`]) ? msg.errors[`actions.custom.${index}.action`][0] : ''">
                        <el-col :span="8">
                            <el-input v-model="item.name" :placeholder="$t('system.setting.menu.drawer.actions.name')"></el-input>
                        </el-col>
                        <el-col :span="8" class="ml-1">
                            <el-input v-model="item.action" :placeholder="$t('system.setting.menu.drawer.actions.action')"></el-input>
                        </el-col>
                        <el-col :span="2" class="ml-1">
                            <el-button circle size="mini" icon="el-icon-close" @click="removeCustomAction(index)"></el-button>
                        </el-col>
                    </el-form-item>
                    <el-form-item :label="!actionsCache.custom.length ?$t('system.setting.menu.drawer.actions.custom') : ''">
                        <el-button icon="el-icon-plus" @click="addCustomAction">{{ $t('action.add') }}</el-button>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">{{ $t('action.save') }}</el-button>
                        <el-button @click="editActionStatus = false">{{ $t('action.cancel') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </vue-simplebar>
    </el-drawer>
</template>

<script>
    export default {
        name: "ItemDetails",
        props: ['visible', 'data'],
        data() {
            return {
                drawerVisible: false,
                editActionStatus: false,
                dataListLoading: 'dle',
                dataList: [],
                actions: {
                    system: [],
                    custom: [],
                },
                actionsCache: {
                    system: [],
                    custom: [],
                },
                actionItem: {action: '', name: ''},
                msg: {
                    code: 200,
                    message: '',
                    errors: {},
                }
            }
        },
        computed: {
            systemActions() {
                let [actions, systems] = [[], this.$t('actions.system')];
                for (let action in systems) {
                    actions.push({action: action, name: systems[action]});
                }
                return actions;
            }
        },
        watch: {
            visible: function (n, o) {
                if (n) {
                    // 显示抽屉
                    this.drawerVisible = n;
                    // 获取数据
                    this.getDataList();
                } else {
                    // 初始化数据
                    this.dataList = [];
                    this.dataListLoading = 'dle';
                    // 隐藏表单
                    this.editActionStatus = false;
                }
            }
        },
        methods: {
            // 获取数据
            getDataList() {
                this.dataListLoading = 'loading';
                axios.get('/system/setting/menu/action/getList', {
                    params: {menu_id: this.data.id}
                }).then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.dataList = response.data.resp_data.data;
                        this.actions = response.data.resp_data.actions;
                        this.dataListLoading = 'dle';
                    } else {
                        this.dataListLoading = 'error';
                    }
                })
            },
            initMsg() {
                this.msg.code = 200;
                this.msg.message = '';
                this.msg.errors = {};
            },
            // 编辑数据
            editData() {
                this.initMsg();
                // 表单数据
                this.actionsCache = _.cloneDeep(this.actions);
                // 制作数据
                this.actionsCache.system = _.map(this.actionsCache.system, 'action');
                // 显示表单
                this.editActionStatus = true;
            },
            // 添加一条自定义功能
            addCustomAction() {
                this.actionsCache.custom.push(_.cloneDeep(this.actionItem));
            },
            // 删除一条自定义功能
            removeCustomAction(index) {
                this.actionsCache.custom.splice(index, 1);
            },
            // 保存数据
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/setting/menu/action/save', {
                    menu_id: this.data.id,
                    actions: {
                        system: _.filter(this.systemActions, (o) => {
                            return this.actionsCache.system.indexOf(o.action) >= 0;
                        }),
                        custom: this.actionsCache.custom,
                    }
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
                        this.editActionStatus = false;
                        // 同步数据
                        this.dataList = response.data.resp_data.data;
                        this.actions = response.data.resp_data.actions;
                    } else if (response.data.resp_msg.code == 42000) {
                        this.msg = response.data.resp_msg;
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

<style scoped>

</style>
