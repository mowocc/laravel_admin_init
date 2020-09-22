<template>
    <el-dialog :title="$t('system.setting.menu.dialog.create')" width="460px" :visible.sync="dialogVisible" @close="$emit('update:visible', false)">
        <div class="pr-5 mr-3">
            <el-form :model="dataCache" label-width="105px">
                <el-form-item :label="$t('system.setting.menu.parent')">
                    <el-tag type="info" size="mini">{{ data.id }}</el-tag>
                    <span class="ml-1" v-if="$te(`menu.${data.route_name}`)">{{ $t(`menu.${data.route_name}`) }}</span>
                </el-form-item>
                <el-form-item :label="$t('system.setting.menu.name')" :error="Boolean(msg.errors['name']) ? msg.errors['name'][0] : ''" required>
                    <el-input v-model="dataCache.name"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.menu.icon')" :error="Boolean(msg.errors['icon']) ? msg.errors['icon'][0] : ''">
                    <el-input v-model="dataCache.icon"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.menu.route_path')" :error="Boolean(msg.errors['route_path']) ? msg.errors['route_path'][0] : ''" required>
                    <el-input v-model="dataCache.route_path"></el-input>
                </el-form-item>
                <el-form-item :label="$t('system.setting.menu.route_name')" :error="Boolean(msg.errors['route_name']) ? msg.errors['route_name'][0] : ''" required>
                    <el-input v-model="dataCache.route_name"></el-input>
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
        props: ['visible', 'data'],
        data() {
            return {
                dialogVisible: false,
                dataCache: {
                    parent_id: 0,
                    name: '',
                    icon: '',
                    route_path: '',
                    route_name: '',
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
                this.dataCache.parent_id = this.data.id;
                this.dataCache.name = '';
                this.dataCache.icon = '';
                this.dataCache.route_path = '';
                this.dataCache.route_name = '';
            },
            onSubmit() {
                this.initMsg();
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/system/setting/menu/store', this.dataCache).then((response) => {
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
                    } else if (_.includes([42000], response.data.resp_msg.code)) {
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
