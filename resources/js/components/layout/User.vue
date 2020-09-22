<template>
    <div class="flex-center pr-4" v-if="!user.id || userStatus!='dle'">
        <component-page-loading :status="userStatus" @reload="getUser"></component-page-loading>
    </div>
    <div class="user-container" v-else>
        <el-popover trigger="hover" popper-class="popover-padding-0" v-model="selectOpenStatus">
            <div class="user-info" slot="reference">
                <i class="icon flex-center">
                    <font-awesome-icon icon="user-tie"></font-awesome-icon>
                </i>
                <span class="name text-truncate" :title="user.name">{{ user.name }}</span>
                <i class="el-icon-caret-top select-caret" :class="{'is-reverse': selectOpenStatus}"></i>
            </div>
            <div class="menu-list">
                <a class="menu-list-item" href="javascript:;" @click="changeLocale()">
                    <font-awesome-icon fixed-width icon="language"></font-awesome-icon>
                    <span class="ml-2">{{ $t('topMenu.language') }}</span>
                </a>
                <a class="menu-list-item" href="javascript:;" @click="home()">
                    <font-awesome-icon fixed-width icon="cog"></font-awesome-icon>
                    <span class="ml-2">{{ $t('topMenu.setting') }}</span>
                </a>
                <a class="menu-list-item" href="javascript:;" @click="logout()">
                    <font-awesome-icon fixed-width icon="sign-out-alt"></font-awesome-icon>
                    <span class="ml-2">{{ $t('topMenu.logout') }}</span>
                </a>
            </div>
        </el-popover>
    </div>
</template>

<script>
    export default {
        name: "LayoutUser",
        data: function () {
            return {
                userStatus: 'dle',
                selectOpenStatus: false,
            }
        },
        computed: {
            user() {
                return this.$store.state.admin.user;
            }
        },
        created: function () {
            this.getUser();
        },
        methods: {
            getUser() {
                this.userStatus = 'loading';
                axios.get('/home/getUser').then((response) => {
                    if (response.data.resp_msg.code == 200) {
                        // Vuex 全局存储
                        this.$store.commit('admin/user/init', response.data.resp_data);
                        this.userStatus = 'dle';
                    } else {
                        this.userStatus = 'error';
                    }
                });
            },
            // 设置语言
            changeLocale() {
                // 关闭弹出框
                this.selectOpenStatus = false;
                // 语言切换
                if (this.$i18n.locale === 'zh-CN') {
                    this.$i18n.locale = 'en';
                } else if (this.$i18n.locale === 'en') {
                    this.$i18n.locale = 'zh-CN';
                }
                this.$cookie.set('lang', this.$i18n.locale, {expires: '1Y'});
                // 保存数据
                axios.post('/home/setLanguage', {'lang': this.$i18n.locale});
            },
            // 个人中心
            home() {
                // 关闭弹出框
                this.selectOpenStatus = false;
                // 跳转到个人中心
                this.$router.push({name: 'home'});
            },
            // 退出
            logout() {
                // 关闭弹出框
                this.selectOpenStatus = false;
                // loading 状态 start
                let loading = this.$loading();
                // 保存数据
                axios.post('/auth/logout').then((response) => {
                    // 逻辑处理
                    if (response.data.resp_msg.code == 200) {
                        this.$message({
                            type: 'success',
                            message: this.$t('messages.succeeded', {status: this.$t('topMenu.logout')}),
                            showClose: true,
                            duration: 1000,
                            onClose: function () {
                                window.location.reload();
                            }
                        });
                    } else {
                        this.$message({
                            type: 'error',
                            message: this.$t('messages.failed', {status: this.$t('topMenu.logout')}),
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
    .user-container {
        height: 100%;
        padding: 0 $page-container-padding;
        min-width: 160px;
        cursor: pointer;
    }

    .user-info {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 10px;
    }

    .user-info .icon {
        width: 28px;
        height: 28px;
        border-radius: 50% !important;
        background-color: #c2ccd9;
        color: #ffffff;
    }

    .user-info .select-caret {
        color: $font-color-fourth;
        transition: transform .3s;
        transform: rotate(180deg);
    }

    .user-info .select-caret.is-reverse {
        transform: rotate(0deg);
    }

    .user-info .name {
        max-width: 120px;
        margin: 0 3px 0 5px;
    }

    .menu-list {
        line-height: 50px;
    }

    .menu-list-item {
        display: block;
        white-space: nowrap;
        text-align: center;
        text-decoration: none;
        color: $body-color !important;
        padding: 0 10px;
    }

    .menu-list-item:hover {
        background-color: $body-bg;
    }
</style>
