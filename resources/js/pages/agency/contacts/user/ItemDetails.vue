<template>
    <el-drawer :title="$t('agency.contacts.user.drawer.details')" size="400px" :visible.sync="drawerVisible" @close="$emit('update:visible', false)">
        <vue-simplebar>
            <div class="pt-5 pb-5" v-if="data.id">
                <el-form label-width="120px" class="el-form-detail">
                    <el-form-item label="ID">{{ data.id }}</el-form-item>
                    <el-form-item :label="$t('agency.contacts.user.org')">
                        <span v-if="data.org != null">{{ data.org.name }}</span>
                        <el-tag effect="plain" size="mini" type="info" v-else>null</el-tag>
                    </el-form-item>
                    <el-form-item :label="$t('agency.contacts.user.name')">{{ data.name }}</el-form-item>
                    <el-form-item :label="$t('agency.contacts.user.email')">{{ data.email }}</el-form-item>
                    <el-form-item :label="$t('agency.contacts.user.roles')">
                        <span v-if="data.isSuperAdmin">
                            <el-tag effect="plain" size="mini" type="danger">Super-Admin</el-tag>
                        </span>
                        <span v-for="(tree,index) in data.tree_roles" class="mr-1">
                            <el-tag effect="plain" size="mini" type="warning">{{ tree.name }}</el-tag>
                        </span>
                        <span v-if="!data.isSuperAdmin && !data.tree_roles.length">
                            <el-tag effect="plain" size="mini" type="info">null</el-tag>
                        </span>
                    </el-form-item>
                    <el-form-item :label="$t('agency.contacts.user.created')">
                        <component-page-timestamp :timestamp="data.created"></component-page-timestamp>
                    </el-form-item>
                    <el-form-item :label="$t('agency.contacts.user.updated')">
                        <component-page-timestamp :timestamp="data.updated"></component-page-timestamp>
                    </el-form-item>
                    <el-form-item :label="$t('agency.contacts.user.deleted')">
                        <el-tag effect="plain" size="mini" :type="!data.deleted ? 'info' : 'danger'">
                            {{ $t('form.deletedList')[Number(!!data.deleted)].label }}
                        </el-tag>
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
