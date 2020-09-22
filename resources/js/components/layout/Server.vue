<template>
    <div class="flex-center pl-4" v-if="!server.id || serversStatus!='dle'">
        <component-page-loading :status="serversStatus" @reload="getServers"></component-page-loading>
    </div>
    <div class="server-container" v-else>
        <el-popover popper-class="popover-padding-0" v-model="selectOpenStatus">
            <div class="server-info" slot="reference">
                <span class="name text-truncate" :title="server.name">{{ server.name }}</span>
                <i class="el-icon-arrow-up select-caret" :class="{'is-reverse': selectOpenStatus}"></i>
            </div>
            <div class="menu-list">
                <a class="menu-list-item" href="javascript:;" @click="selectServer(item)" v-for="(item, index) in servers" :key="index">
                    <font-awesome-icon fixed-width icon="server"></font-awesome-icon>
                    <span class="ml-2 mr-1">{{ item.name }}</span>
                </a>
            </div>
        </el-popover>
    </div>
</template>

<script>
    export default {
        name: "LayoutServer",
        data: function () {
            return {
                selectOpenStatus: false,
                server: {},
                serversStatus: 'dle',
                servers: []
            }
        },
        watch: {
            // 分区配置有改变
            '$store.state.admin.server.timestamp': function (n, o) {
                this.getServers();
            }
        },
        created: function () {
            this.getServers();
        },
        methods: {
            getServers() {
                this.serversStatus = 'loading';
                axios.get('/admin/getServers').then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        this.server = response.data.resp_data.server;
                        this.servers = response.data.resp_data.servers;
                        this.serversStatus = !this.servers.length ? 'nodata' : 'dle';
                    } else {
                        this.serversStatus = 'error';
                    }
                });
            },
            selectServer(item) {
                // 关闭弹出框
                this.selectOpenStatus = false;
                // 屏蔽重复选择
                if (item.id == this.server.id) {
                    return false;
                }
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/admin/setServer', {
                    id: item.id
                }).then((response) => {
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('action.switch')}),
                            showClose: true,
                            duration: 1000,
                            onClose: function () {
                                window.location.reload();
                            }
                        });
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('action.switch')}),
                            showClose: true
                        });
                        // loading 状态 close
                        loading.close();
                    }
                });
            },
        },
    }
</script>

<style lang="scss" scoped>
    .server-container {
        height: 100%;
        padding: 0 $page-container-padding;
        cursor: pointer;
    }

    .server-info {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .server-info .name {
        margin-right: 5px;
    }

    .server-info .select-caret {
        color: $font-color-fourth;
        font-size: $font-size-third;
        transition: transform .3s;
        transform: rotate(180deg);
    }

    .server-info .select-caret.is-reverse {
        transform: rotate(0deg);
    }

    .menu-list {
        line-height: 50px;
    }

    .menu-list-item {
        display: flex;
        align-items: center;
        white-space: nowrap;
        text-decoration: none;
        color: $body-color !important;
        padding: 0 10px;
    }

    .menu-list-item:hover {
        background-color: $body-bg;
    }
</style>
