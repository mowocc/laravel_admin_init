export default {
    namespaced: true,
    state: {
        id: 0,
        name: '',
        isSuperAdmin: false,
    },
    mutations: {
        init(state, data) {
            state.id = data.id;
            state.name = data.name;
            state.isSuperAdmin = data.isSuperAdmin;
        }
    },
}
