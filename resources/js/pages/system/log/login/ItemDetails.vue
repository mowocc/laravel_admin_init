<template>
    <el-drawer :title="$t('system.log.login.drawer.details')" size="400px" :visible.sync="drawerVisible" @close="$emit('update:visible', false)">
        <vue-simplebar>
            <div class="pt-5 pb-5" v-if="data.id">
                <el-form label-width="120px" class="el-form-detail">
                    <!--登录人-->
                    <div v-if="data.user">
                        <el-form-item label="ID">
                            <el-tag effect="plain" size="mini" type="info">{{ data.user.id }}</el-tag>
                        </el-form-item>
                        <el-form-item :label="$t('system.log.login.drawer.user.name')">{{ data.user.name }}</el-form-item>
                        <el-form-item :label="$t('system.log.login.drawer.user.email')">{{ data.user.email }}</el-form-item>
                        <el-form-item :label="$t('system.log.login.created')">
                            <component-page-timestamp :timestamp="data.created"></component-page-timestamp>
                        </el-form-item>
                    </div>
                    <!--User-Agency-->
                    <div class="pt-4 pl-4 pr-4 pb-3">
                        <el-divider>User-Agency</el-divider>
                    </div>
                    <el-form-item :label="$t('system.log.login.languages')">{{ data.languages }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.device')">
                        <span v-if="Boolean(data.device)">{{ data.device }}</span>
                        <el-tag effect="plain" size="mini" type="info" v-else>null</el-tag>
                    </el-form-item>
                    <el-form-item :label="$t('system.log.login.platform')">{{ data.platform }} {{ data.platform_version }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.browser')">{{ data.browser }} {{ data.browser_version }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.robot')">
                        <span v-if="Boolean(data.robot)">{{ data.robot }}</span>
                        <el-tag effect="plain" size="mini" type="info" v-else>null</el-tag>
                    </el-form-item>
                    <!--Geoip-->
                    <div class="pt-4 pl-4 pr-4 pb-3">
                        <el-divider>Geoip</el-divider>
                    </div>
                    <el-form-item :label="$t('system.log.login.ip')">{{ data.ip }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.iso_code')">{{ data.iso_code }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.country')">{{ data.country }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.state')">{{ data.state }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.state_name')">{{ data.state_name }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.city')">{{ data.city }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.postal_code')">{{ data.postal_code }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.lon')">{{ data.lon }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.lat')">{{ data.lat }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.timezone')">{{ data.timezone }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.continent')">{{ data.continent }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.currency')">{{ data.currency }}</el-form-item>
                    <el-form-item :label="$t('system.log.login.default')">
                        <el-tag effect="plain" size="mini" type="info">{{ $t('form.booleanList')[data.default].label }}</el-tag>
                    </el-form-item>
                </el-form>
            </div>
        </vue-simplebar>
    </el-drawer>
</template>

<script>
    export default {
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

</style>
