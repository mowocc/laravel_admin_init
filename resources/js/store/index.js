import Vue from 'vue'
import Vuex from 'vuex'
import AdminServer from './modules/admin/server.js'
import AdminMenu from './modules/admin/menu.js'
import AdminUser from './modules/admin/user.js'
import HomeConfig from './modules/home/config.js'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        admin: {
            namespaced: true,
            modules: {
                server: AdminServer,
                menu: AdminMenu,
                user: AdminUser,
            }
        },
        home: {
            namespaced: true,
            modules: {
                config: HomeConfig,
            }
        }
    }
})
