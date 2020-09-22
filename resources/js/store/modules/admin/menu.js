export default {
    namespaced: true,
    state: {
        menuActions: [],
        routeNames: [],
        routeNamesLeaf: [],
    },
    getters: {
        hasPermissionTo: (state) => (code) => {
            return state.routeNames.concat(state.menuActions).indexOf(code) >= 0;
        }
    },
    mutations: {
        init(state, data) {
            state.menuActions = data.menuActions;
            state.routeNames = data.routeNames;
            state.routeNamesLeaf = data.routeNamesLeaf;
        }
    },
}
