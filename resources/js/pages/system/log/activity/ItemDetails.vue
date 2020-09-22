<template>
    <el-drawer :title="$t('system.log.activity.drawer.details')" size="400px" :visible.sync="drawerVisible" @close="$emit('update:visible', false)">
        <vue-simplebar>
            <div class="pt-5 pb-5" v-if="data.id">
                <el-form label-width="120px" class="el-form-detail">
                    <!--基本信息-->
                    <el-form-item :label="$t('system.log.activity.description')">
                        <el-tag effect="plain" size="mini" :type="tagTypes[data.description] || 'info'">
                            {{data.description }}
                        </el-tag>
                    </el-form-item>
                    <el-form-item :label="$t('system.log.activity.created')">
                        <component-page-timestamp :timestamp="data.created"></component-page-timestamp>
                    </el-form-item>
                    <el-form-item :label="$t('system.log.activity.subject')">
                        {{ data.subject_type }}:{{ data.subject_id }}
                    </el-form-item>
                    <el-form-item :label="$t('system.log.activity.log_name')">
                        {{ data.log_name }}
                    </el-form-item>
                    <!--变化信息-->
                    <div class="pt-4 pl-4 pr-4 pb-3">
                        <el-divider>{{ $t('system.log.activity.changes') }}</el-divider>
                    </div>
                    <div class="pl-5" v-if="drawerVisible">
                        <json-view :data="data.changes" :deep="2"></json-view>
                    </div>
                    <!--操作人-->
                    <div v-if="data.causer_id">
                        <div class="pt-5 pl-4 pr-4 pb-3">
                            <el-divider>{{ $t('system.log.activity.causer') }}</el-divider>
                        </div>
                        <el-form-item label="ID">
                            <el-tag effect="plain" size="mini" type="info">{{ data.causer.id }}</el-tag>
                        </el-form-item>
                        <el-form-item :label="$t('system.log.activity.drawer.causer.name')">{{ data.causer.name }}</el-form-item>
                        <el-form-item :label="$t('system.log.activity.drawer.causer.email')">{{ data.causer.email }}</el-form-item>
                    </div>
                </el-form>
            </div>
        </vue-simplebar>
    </el-drawer>
</template>

<script>
    import jsonView from 'vue-json-views';

    export default {
        components: {
            jsonView
        },
        name: "ItemDetails",
        props: ['visible', 'data', 'tagTypes'],
        data() {
            return {
                drawerVisible: false,
            }
        },
        watch: {
            visible: function (n, o) {
                if (n) {
                    // 显示抽屉
                    this.drawerVisible = n;
                }
            }
        },
    }
</script>

<style scoped>
    .json-view-container /deep/ .json-view .json-value {
        white-space: nowrap !important;
    }
</style>
