<template>
    <div class="container">
        <div class="mb-2 mt-4">{{ $t('home.update-password.title') }}</div>
        <div class="page-container-inner">
            <div class="pt-5 pb-3">
                <el-form label-width="180px" :model="data">
                    <el-form-item :label="$t('home.update-password.password_current')" required
                                  :error="(Boolean(msg.errors['password_current']) ? msg.errors['password_current'][0] : '') || (msg.code==44002 ? msg.message : '')">
                        <el-input type="password" v-model="data.password_current"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('home.update-password.password')" required
                                  :error="Boolean(msg.errors['password']) ? msg.errors['password'][0] : ''">
                        <el-input type="password" v-model="data.password"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('home.update-password.password_confirmation')" required
                                  :error="Boolean(msg.errors['password_confirmation']) ? msg.errors['password_confirmation'][0] : ''">
                        <el-input type="password" v-model="data.password_confirmation"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">{{ $t('action.modify') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UpdatePassword",
        data() {
            return {
                data: {
                    password_current: '',
                    password: '',
                    password_confirmation: ''
                },
                msg: {
                    code: 200,
                    message: '',
                    errors: {},
                }
            };
        },
        methods: {
            initMsg() {
                this.msg.code = 200;
                this.msg.message = '';
                this.msg.errors = {};
            },
            initData() {
                this.data.password_current = '';
                this.data.password = '';
                this.data.password_confirmation = '';
            },
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/home/updatePassword', this.data).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.modify')}),
                            showClose: true
                        });
                        // 初始化数据
                        this.initData();
                    } else if (_.includes([42000, 44002], response.data.resp_msg.code)) {
                        this.msg = response.data.resp_msg;
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.modify')}),
                            showClose: true
                        });
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
