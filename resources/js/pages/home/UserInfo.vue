<template>
    <div class="container">
        <div class="mb-2">{{ $t('home.user-info.title') }}</div>
        <div class="page-container-inner">
            <div class="p-2" v-if="data.id==null || dataStatus!='dle'">
                <component-page-loading :status="dataStatus" @reload="getUser"></component-page-loading>
            </div>
            <div class="pt-5 pb-3" v-else>
                <el-form label-width="180px" v-show="!dataEditStatus">
                    <el-form-item :label="$t('home.user-info.name')">{{ data.name }}</el-form-item>
                    <el-form-item :label="$t('home.user-info.email')">{{ data.email }}</el-form-item>
                    <el-form-item>
                        <el-button @click="editUser">{{ $t('action.edit') }}</el-button>
                    </el-form-item>
                </el-form>
                <el-form label-width="180px" :model="dataCache" v-show="dataEditStatus">
                    <el-form-item :label="$t('home.user-info.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                        <el-input v-model="dataCache.name"></el-input>
                    </el-form-item>
                    <el-form-item :label="$t('home.user-info.email')" :error="(Boolean(msg.errors['email']) ? msg.errors['email'][0] : '') || (msg.code==44202 ? msg.message : '')" required>
                        <el-input v-model="dataCache.email"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="onSubmit">{{ $t('action.save') }}</el-button>
                        <el-button @click="dataEditStatus = false">{{ $t('action.cancel') }}</el-button>
                    </el-form-item>
                </el-form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserInfo",
        data: function () {
            return {
                dataStatus: 'dle',
                dataEditStatus: false,
                dataCache: {},
                data: {},
                msg: {
                    code: 200,
                    message: '',
                    errors: {},
                }
            }
        },
        created: function () {
            this.getUser();
        },
        methods: {
            getUser() {
                this.dataStatus = 'loading';
                axios.get('/home/getUser').then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.data = response.data.resp_data;
                        this.dataStatus = 'dle';
                    } else {
                        this.dataStatus = 'error';
                    }
                });
            },
            initMsg() {
                this.msg.code = 200;
                this.msg.message = '';
                this.msg.errors = {};
            },
            editUser() {
                // 重置验证
                this.initMsg();
                // 克隆数据
                this.dataCache = _.cloneDeep(this.data);
                // 编辑状态
                this.dataEditStatus = true;
            },
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/home/update', this.dataCache).then((response) => {
                    // loading 状态 close
                    loading.close();
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.save')}),
                            showClose: true
                        });
                        this.data = response.data.resp_data;
                        // Vuex 全局存储
                        this.$store.commit('admin/user/init', this.data);
                        // 还原显示状态
                        this.dataEditStatus = false;
                    } else if (_.includes([42000, 44202], response.data.resp_msg.code)) {
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
    .page-container-inner {
        min-height: 270px;
    }
</style>
