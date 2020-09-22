<template>
    <el-dialog :title="$t('system.user.dialog.update')" width="460px" :visible.sync="dialogVisible" @close="$emit('update:visible', false)">
        <div class="pr-5">
            <el-form :model="dataCache" label-width="85px">
                <el-form-item :label="$t('system.user.roles')" v-if="isSuperAdmin">
                    <el-checkbox :true-label="1" :false-label="0" v-model="dataCache.is_super_admin">
                        <el-tag effect="plain" size="mini" type="danger">Super-Admin</el-tag>
                    </el-checkbox>
                </el-form-item>
                <el-form-item :label="$t('system.user.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                    <el-input v-model="dataCache.name"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.user.email')" :error="(Boolean(msg.errors['email']) ? msg.errors['email'][0] : '') || (msg.code==44202 ? msg.message : '')" required>
                    <el-input v-model="dataCache.email"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.user.dialog.password')" :error="Boolean(msg.errors['password']) ? msg.errors['password'][0] : ''">
                    <el-input v-model="dataCache.password" :placeholder="$t('system.user.dialog.passwordPlaceholder.update')"></el-input>
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
                    is_super_admin: 0,
                    name: '',
                    email: '',
                    password: ''
                },
                msg: {
                    code: 200,
                    message: '',
                    errors: {},
                }
            }
        },
        computed: {
            isSuperAdmin() {
                return this.$store.state.admin.user.isSuperAdmin;
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
                this.dataCache.is_super_admin = Number(data.isSuperAdmin);
                this.dataCache.name = data.name;
                this.dataCache.email = data.email;
                this.dataCache.password = '';
            },
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/user/saveItem', this.dataCache).then((response) => {
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
                        // 同步数据到父组件
                        this.$emit('update', response.data.resp_data);
                    } else if (_.includes([42000, 44202], response.data.resp_msg.code)) {
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
