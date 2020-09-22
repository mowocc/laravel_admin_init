<template>
    <div class="container-content">
        <div class="container-content-title">{{ $t('agency.setting.info.title') }}</div>
        <el-form label-width="120px" class="el-form-detail">
            <el-row>
                <el-col :md="12">
                    <el-form-item :label="tree.name">
                        <el-tag effect="plain" size="mini" type="info">{{ tree.id }}</el-tag>
                    </el-form-item>
                </el-col>
                <el-col :md="12">
                    <el-form-item label="parent_id">
                        <el-tag effect="plain" size="mini" type="info">{{ tree.parent_id }}</el-tag>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>
    </div>
</template>

<script>
    export default {
        name: "AgencyInfo",
        props: ['tree'],
    }
</script>

<style scoped>

</style>
