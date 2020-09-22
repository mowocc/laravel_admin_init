<template>
    <el-drawer :title="$t('system.setting.server.drawer.details')" size="400px" :visible.sync="drawerVisible" @close="$emit('update:visible', false)">
        <vue-simplebar>
            <div class="pt-5 pb-5" v-if="data.id">
                <el-form label-width="120px" class="el-form-detail">
                    <el-form-item label="ID">{{ data.id }}</el-form-item>
                    <el-form-item :label="$t('system.setting.server.name')">{{ data.name }}</el-form-item>
                    <el-form-item :label="$t('system.setting.server.created')">
                        <component-page-timestamp :timestamp="data.created"></component-page-timestamp>
                    </el-form-item>
                    <el-form-item :label="$t('system.setting.server.updated')">
                        <component-page-timestamp :timestamp="data.updated"></component-page-timestamp>
                    </el-form-item>
                    <el-form-item :label="$t('system.setting.server.deleted')">
                        <el-tag effect="plain" size="mini" :type="!data.deleted ? 'info' : 'danger'">
                            {{ $t('form.deletedList')[Number(!!data.deleted)].label }}
                        </el-tag>
                    </el-form-item>
                    <!--mysql_agency-->
                    <div class="pt-4 pl-4 pr-4 pb-3">
                        <el-divider content-position="left">{{ $t('system.setting.server.mysql_agency.title') }}</el-divider>
                    </div>
                    <el-form-item :label="$t('system.setting.server.mysql_agency.host')">{{ data.mysql_agency.host }}</el-form-item>
                    <el-form-item :label="$t('system.setting.server.mysql_agency.port')">{{ data.mysql_agency.port }}</el-form-item>
                    <el-form-item :label="$t('system.setting.server.mysql_agency.database')">{{ data.mysql_agency.database }}</el-form-item>
                    <el-form-item :label="$t('system.setting.server.mysql_agency.username')">{{ data.mysql_agency.username }}</el-form-item>
                    <el-form-item :label="$t('system.setting.server.mysql_agency.password')">
                        <span v-if="Boolean(data.mysql_agency.password)">{{ data.mysql_agency.password }}</span>
                        <el-tag effect="plain" size="mini" type="info" v-else>null</el-tag>
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

</style>
