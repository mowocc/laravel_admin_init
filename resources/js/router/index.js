import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

// 解决ElementUI导航栏中的vue-router在3.0版本以上重复点菜单报错问题
const originalPush = VueRouter.prototype.push
VueRouter.prototype.push = function push(location) {
    return originalPush.call(this, location).catch(err => err)
}

export default new VueRouter({
    saveScrollPosition: true,
    routes: [
        {
            name: 'index',
            path: '/index',
            component: resolve => require(['../pages/index/Index.vue'], resolve),
            meta: {
                title: 'menu.index',
            }
        },
        {
            name: 'chart',
            path: '/chart',
            component: resolve => require(['../pages/chart/Index.vue'], resolve),
            meta: {
                title: 'menu.chart',
            }
        },
        {
            name: 'agency.setting',
            path: '/agency/setting',
            component: resolve => require(['../pages/agency/setting/Index.vue'], resolve),
            meta: {
                title: 'menu.agency.setting',
            }
        },
        {
            name: 'agency.contacts',
            path: '/agency/contacts',
            component: resolve => require(['../pages/agency/contacts/Index.vue'], resolve),
            meta: {
                title: 'menu.agency.contacts',
            }
        },
        {
            name: 'game',
            path: '/game',
            component: resolve => require(['../pages/game/Index.vue'], resolve),
            meta: {
                title: 'menu.game',
            }
        },
        {
            name: 'system.setting.language',
            path: '/system/setting/language',
            component: resolve => require(['../pages/system/setting/language/Index.vue'], resolve),
            meta: {
                title: 'menu.system.setting.language',
            }
        },
        {
            name: 'system.setting.server',
            path: '/system/setting/server',
            component: resolve => require(['../pages/system/setting/server/Index.vue'], resolve),
            meta: {
                title: 'menu.system.setting.server',
            }
        },
        {
            name: 'system.setting.menu',
            path: '/system/setting/menu',
            component: resolve => require(['../pages/system/setting/menu/Index.vue'], resolve),
            meta: {
                title: 'menu.system.setting.menu',
            }
        },
        {
            name: 'system.user',
            path: '/system/user',
            component: resolve => require(['../pages/system/user/Index.vue'], resolve),
            meta: {
                title: 'menu.system.user',
            }
        },
        {
            name: 'system.permission',
            path: '/system/permission',
            component: resolve => require(['../pages/system/permission/Index.vue'], resolve),
            meta: {
                title: 'menu.system.permission',
            }
        },
        {
            name: 'system.log.activity',
            path: '/system/log/activity',
            component: resolve => require(['../pages/system/log/activity/Index.vue'], resolve),
            meta: {
                title: 'menu.system.log.activity',
            }
        },
        {
            name: 'system.log.login',
            path: '/system/log/login',
            component: resolve => require(['../pages/system/log/login/Index.vue'], resolve),
            meta: {
                title: 'menu.system.log.login',
            }
        },
        {
            name: 'home',
            path: '/home',
            component: resolve => require(['../pages/home/Index.vue'], resolve),
            meta: {
                hasPermissionExcept: true,
            }
        },
        {
            name: 'error.403',
            path: '/error/403',
            component: require('../pages/errors/403.vue'),
            meta: {
                hasPermissionExcept: true,
            }
        },
        {
            name: 'error.404',
            path: '/error/404',
            component: require('../pages/errors/404.vue'),
            meta: {
                hasPermissionExcept: true,
            }
        },
        {
            path: "*",
            component: require('../pages/Index.vue')
        }
    ]
});
