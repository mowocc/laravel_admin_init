<template>
    <div class="page-container">
        <div class="page-layout">
            <div class="page-layout-header">
                <div class="header-aside flex-start-title">
                    <div class="title">{{ $t('agency.contacts.titleSide') }}</div>
                </div>
                <div class="header-main">
                    <div class="title">{{ $t('agency.contacts.title') }}</div>
                </div>
            </div>
            <div class="page-layout-body">
                <div class="body-aside">
                    <!--代理平台-->
                    <component-page-org-tree @click="treeSelectNode"></component-page-org-tree>
                </div>
                <div class="body-main">
                    <agency-user :tree="tree"></agency-user>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import AgencyUser from './user/Index.vue'

    export default {
        components: {
            AgencyUser,
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

</style>
