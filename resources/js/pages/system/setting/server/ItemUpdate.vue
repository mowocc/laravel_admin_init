<template>
    <el-dialog :title="$t('system.setting.server.dialog.update')" width="490px" :visible.sync="dialogVisible" @close="$emit('update:visible', false)">
        <div class="pr-5">
            <el-form :model="dataCache" label-width="88px">
                <el-form-item :label="$t('system.setting.server.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                    <el-input v-model="dataCache.name"></el-input>
                </el-form-item>
                <!--mysql_agency-->
                <div class="pt-1 pl-5 pb-3">
                    <el-divider content-position="left">{{ $t('system.setting.server.mysql_agency.title') }}</el-divider>
                </div>
                <el-form-item :label="$t('system.setting.server.mysql_agency.host')" :error="Boolean(msg.errors['mysql_agency.host']) ? msg.errors['mysql_agency.host'][0] : ''" required>
                    <el-input v-model="dataCache.mysql_agency.host"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.server.mysql_agency.port')" :error="Boolean(msg.errors['mysql_agency.port']) ? msg.errors['mysql_agency.port'][0] : ''" required>
                    <el-input v-model="dataCache.mysql_agency.port"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.server.mysql_agency.database')" :error="Boolean(msg.errors['mysql_agency.database']) ? msg.errors['mysql_agency.database'][0] : ''" required>
                    <el-input v-model="dataCache.mysql_agency.database"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.server.mysql_agency.username')" :error="Boolean(msg.errors['mysql_agency.username']) ? msg.errors['mysql_agency.username'][0] : ''" required>
                    <el-input v-model="dataCache.mysql_agency.username"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.server.mysql_agency.password')" :error="Boolean(msg.errors['mysql_agency.password']) ? msg.errors['mysql_agency.password'][0] : ''">
                    <el-input v-model="dataCache.mysql_agency.password"></el-input>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="onSubmit">{{ $t('action.save') }}</el-button>
                    <el-button @click="dialogVisible = false">{{ $t('action.cancel') }}</el-button>
                </el-form-item>
            </el-form>
        </div>
    </el-dialog>
</template>

<script>
    export default {
        name: "ItemUpdate",
        props: ['visible', 'data'],
        data() {
            return {
                dialogVisible: false,
                dataCache: {
                    id: 0,
                    name: '',
                    mysql_agency: {
                        host: '',
                        port: '',
                        database: '',
                        username: '',
                        password: '',
                    },
                },
                initMysqlAgency: {},
                msg: {
                    code: 200,
                    message: '',
                    errors: {},
                }
            }
        },
        watch: {
            visible: function (n, o) {
                if (n) {
                    // 初始化参数
                    this.initData(this.data);
                    // 显示模态框
                    this.dialogVisible = n;
                }
            }
        },
        methods: {
            initMsg() {
                this.msg.code = 200;
                this.msg.message = '';
                this.msg.errors = {};
            },
            initData(data) {
                this.initMsg();
                this.dataCache.id = data.id;
                this.dataCache.name = data.name;
                this.dataCache.mysql_agency = _.cloneDeep(data.mysql_agency);
            },
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/setting/server/saveItem', this.dataCache).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.edit')}),
                            showClose: true
                        });
                        this.dialogVisible = false;
                        // 刷新 Vuex 全局状态
                        this.$store.commit('admin/server/change');
                        // 同步数据到父组件
                        this.$emit('update', response.data.resp_data);
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
        }
    }
</script>

<style scoped>

</style>
