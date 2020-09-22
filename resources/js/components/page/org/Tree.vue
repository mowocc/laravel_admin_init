<template>
    <div class="h-100">
        <div class="p-2" v-if="!treeList.length">
            <component-page-loading :status="treeListStatus" @reload="getOrgList"></component-page-loading>
        </div>
        <vue-simplebar v-else>
            <el-tree highlight-current ref="treeOrg"
                     node-key="id"
                     :indent="18"
                     :data="treeList"
                     :props="treeProps"
                     :expand-on-click-node="false"
                     @current-change="selectNode">
                <div class="tree-node" slot-scope="{ node, data }">
                    <div class="node-info">
                        <i class="el-icon-folder node-icon"></i>{{ data.name }}
                    </div>
                </div>
            </el-tree>
        </vue-simplebar>
    </div>
</template>

<script>
    export default {
        name: "PageOrgTree",
        props: {
            currentRoot: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                treeListStatus: 'dle',
                treeList: [],
                treeProps: {
                    label: 'name',
                    children: 'children'
                }
            }
        },
        created: function () {
            this.getOrgList();
        },
        methods: {
            getOrgList() {
                this.treeListStatus = 'loading';
                axios.get('/common/getOrgTree').then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.treeList = response.data.resp_data;
                        this.treeListStatus = !this.treeList.length ? 'nodata' : 'dle';
                        // 初始化选中根节点
                        this.$nextTick(function () {
                            if (this.treeList.length && this.currentRoot) {
                                this.$refs.treeOrg.setCurrentKey(this.treeList[0].id);
                                this.selectNode(this.treeList[0]);
                            }
                        })
                    } else {
                        this.treeListStatus = 'error';
                    }
                })
            },
            // 点击选中 tree 节点
            selectNode(data) {
                this.$emit('click', data);
            },
        },
    }
</script>

<style lang="scss" scoped>
    .tree-node {
        display: flex;
        flex-direction: row;
        flex: auto;
        align-items: center;
        font-size: $font-size-third;
    }

    .tree-node .node-info {
        flex: auto;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .tree-node .node-icon {
        color: $tree-color-icon;
        margin-right: 5px;
    }
</style>
