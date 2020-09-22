export default {
    namespaced: true,
    state: {
        timestamp: 0,
    },
    mutations: {
        change(state, data) {
            state.timestamp = Date.now();
        }
    },
}
