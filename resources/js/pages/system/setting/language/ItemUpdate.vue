<template>
    <el-dialog :title="$t('system.setting.language.dialog.update')" width="400px" :visible.sync="dialogVisible" @close="$emit('update:visible', false)">
        <div class="pr-5">
            <el-form :model="dataCache" label-width="90px">
                <el-form-item :label="$t('system.setting.language.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                    <el-input v-model="dataCache.name"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.language.lang')" :error="Boolean(msg.errors['lang']) ? msg.errors['lang'][0] : ''" required>
                    <el-input v-model="dataCache.lang"></el-input>
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
                    lang: '',
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
                this.dataCache.lang = data.lang;
            },
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/setting/language/saveItem', this.dataCache).then((response) => {
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
