require('./bootstrap.js');
// Vue
window.Vue = require('vue');


// vue-cookie
Vue.use(require('vue-cookie'));


// vue-progressbar 进度条
import VueProgressBar from 'vue-progressbar'
// 注册 vue-progressbar
Vue.use(VueProgressBar);


// simplebar 滚动条
import VueSimplebar from 'vue-simplebar'
import 'vue-simplebar/dist/vue-simplebar.min.css'
// 注册 simplebar
Vue.use(VueSimplebar, {options: {autoHide: false}})


// vue-i18n 国际化
import VueI18n from 'vue-i18n';
// element-ui
import ElementUI from 'element-ui';
import enLocale from 'element-ui/lib/locale/lang/en';
import zhLocale from 'element-ui/lib/locale/lang/zh-CN';
import 'element-ui/lib/theme-chalk/index.css';
// 创建 VueI18n 实例
const i18n = new VueI18n({
    locale: Vue.prototype.$cookie.get('lang') || 'zh-CN',
    messages: {
        'en': Object.assign(require(`./lang/en`).default, enLocale),
        'zh-CN': Object.assign(require(`./lang/zh-CN`).default, zhLocale)
    }
});
// 添加 Vue 私有属性
Vue.prototype.$lang = function (key) {
    return i18n.t(key);
};
// element-ui 国际化
Vue.use(ElementUI, {i18n: (key, value) => i18n.t(key, value)});


// font-awesome
import {library} from '@fortawesome/fontawesome-svg-core';
import {fas} from '@fortawesome/free-solid-svg-icons';
import {far} from '@fortawesome/free-regular-svg-icons';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
// 注册字体
library.add(fas);
library.add(far);
Vue.component('font-awesome-icon', FontAwesomeIcon);


// 公共组件
Vue.component('component-layout-server', require('./components/layout/Server.vue'));
Vue.component('component-layout-menu', require('./components/layout/Menu.vue'));
Vue.component('component-layout-user', require('./components/layout/User.vue'));
Vue.component('component-page-loading', require('./components/page/Loading.vue'));
Vue.component('component-page-timestamp', require('./components/page/Timestamp.vue'));
Vue.component('component-page-org-tree', require('./components/page/org/Tree.vue'));


// 引入 filter 公共过滤库
require('./utils/filter.js');
// 引入 helper 公共函数库
require('./utils/helper.js');
// 引入 variable 公共变量库
require('./utils/variable.js');


// 根 App.vue
import App from './App.vue';
// 路由 router
import router from './router/index.js';
// 状态管理 Vuex
import store from './store'


const app = new Vue({
    el: '#app',
    router,
    store,
    i18n,
    render: h => h(App)
});
