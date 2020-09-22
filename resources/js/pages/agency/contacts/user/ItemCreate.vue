<template>
    <el-dialog :title="$t('agency.contacts.user.dialog.create')" width="460px" :visible.sync="dialogVisible" @close="$emit('update:visible', false)">
        <div class="pr-5">
            <el-form :model="dataCache" label-width="85px">
                <el-form-item :label="$t('agency.contacts.user.org')">{{ tree.name }}</el-form-item>
                <el-form-item :label="$t('agency.contacts.user.roles')">
                    <el-checkbox :true-label="1" :false-label="0" v-model="dataCache.is_super_admin">
                        <el-tag effect="plain" size="mini" type="danger">Super-Admin</el-tag>
                    </el-checkbox>
                </el-form-item>
                <el-form-item :label="$t('agency.contacts.user.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                    <el-input v-model="dataCache.name"></el-input>
                </el-form-item>
                <el-form-item :label="$t('agency.contacts.user.email')" :error="(Boolean(msg.errors['email']) ? msg.errors['email'][0] : '') || (msg.code==44202 ? msg.message : '')" required>
                    <el-input v-model="dataCache.email"></el-input>
                </el-form-item>
                <el-form-item :label="$t('agency.contacts.user.dialog.password')" :error="Boolean(msg.errors['password']) ? msg.errors['password'][0] : ''" required>
                    <el-input v-model="dataCache.password" :placeholder="$t('agency.contacts.user.dialog.passwordPlaceholder.create')"></el-input>
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
        name: "ItemCreate",
        props: ['visible', 'tree'],
        data() {
            return {
                dialogVisible: false,
                dataCache: {
                    org_id: null,
                    is_super_admin: 0,
                    name: '',
                    email: '',
                    password: '',
                },
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
                    this.initData();
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
            initData() {
                this.initMsg();
                this.dataCache.org_id = this.tree.id;
                this.dataCache.is_super_admin = 0;
                this.dataCache.name = '';
                this.dataCache.email = '';
                this.dataCache.password = '';
            },
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/agency/contacts/user/saveItem', this.dataCache).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.new')}),
                            showClose: true
                        });
                        this.dialogVisible = false;
                        // 广播事件到父组件
                        this.$emit('create');
                    } else if (_.includes([42000, 44202], response.data.resp_msg.code)) {
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
        }
    }
</script>

<style scoped>

</style>
