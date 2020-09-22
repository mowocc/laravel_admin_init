<template>
    <div class="page-container">
        <div class="page-layout">
            <div class="page-layout-header">
                <div class="header-aside flex-start-title">
                    <div class="title">{{ $t('agency.setting.titleSide') }}</div>
                    <div class="option">
                        <div class="icon" :title="$t('agency.setting.tree.create-root')" @click="$refs.tree.dialogNodeOption('create-root')">
                            <i class="el-icon-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="header-main">
                    <div class="title">{{ $t('agency.setting.title') }}</div>
                </div>
            </div>
            <div class="page-layout-body">
                <div class="body-aside">
                    <!--代理平台-->
                    <agency-org ref="tree" @click="treeSelectNode"></agency-org>
                </div>
                <div class="body-main">
                    <vue-simplebar>
                        <div v-show="tree.id != null">
                            <!--基本信息-->
                            <agency-info :tree="tree"></agency-info>
                            <!--各种配置-->
                            <div class="container-content">
                                <div class="container-content-title">{{ $t('agency.setting.other.title') }}</div>
                                ...
                            </div>
                        </div>
                    </vue-simplebar>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AgencyOrg from './Org.vue'
    import AgencyInfo from './Info.vue'

    export default {
        components: {
            AgencyOrg,
            AgencyInfo,
        },
        data() {
            return {
                tree: {},
            }
        },
        methods: {
            treeSelectNode(data) {
                // 缓存数据
                this.tree = data;
            },
        }
    }
</script>

<style lang="scss" scoped>
    .header-aside .icon {
        flex-shrink: 0;
        width: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        cursor: pointer;
        color: $font-color-third;
    }

    .header-aside .icon:hover {
        color: $font-color-second;
    }

    /deep/ .container-content {
        padding: 20px;
    }

    /deep/ .container-content-title {
        margin-bottom: 20px;
        border-bottom: 1px solid $boder-color-third;
        padding-bottom: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: $font-color-first;
    }
</style>
