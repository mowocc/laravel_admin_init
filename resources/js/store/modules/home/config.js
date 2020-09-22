export default {
    namespaced: true,
    state: {
        config: {
            tz_code: null,
            cr_code: null
        },
        cr: {
            base: 'CNY',
            rates: {
                CNY: 1
            }
        },
        tz: {
            base: 'UTC+8',
            timezone: {
                "UTC+8": "Etc/GMT-8"
            }
        },
    },
    getters: {
        filterCoinToRates: (state) => (coin) => {
            return Number(coin || 0) * (state.cr.rates[state.config.cr_code] || 1);
        },
        filterRatesToCoin: (state) => (rates) => {
            return Number(rates || 0) / (state.cr.rates[state.config.cr_code] || 1);
        },
        getTimezone: (state) => {
            return state.tz.timezone[state.config.tz_code || state.tz.base];
        }
    },
    mutations: {
        initData(state, data) {
            Vue.set(state, 'config', data.config);
            Vue.set(state, 'cr', data.cr);
            Vue.set(state, 'tz', data.tz);
        }
    },
    actions: {
        getData(context) {
            axios.get('/user/home/getItemByConfig').then((response) => {
                if (response.data.resp_msg.code == 200) {
                    context.commit('initData', response.data.resp_data)
                }
            })
        },
    },
}