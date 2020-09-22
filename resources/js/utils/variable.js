import Vue from 'vue';

Vue.prototype.GLOBAL = {
    pickerOptions: {
        shortcuts: [{
            text: Vue.prototype.$lang('datePickerOptions.today'),
            onClick(picker) {
                let timed = new Date(new Date().toLocaleDateString());
                let end = new Date(timed.getTime() + 1000 * 3600 * 24 - 1);
                let start = new Date(timed.getTime());
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: Vue.prototype.$lang('datePickerOptions.yesterday'),
            onClick(picker) {
                let timed = new Date(new Date().toLocaleDateString());
                let end = new Date(timed.getTime() - 1000 * 1);
                let start = new Date(timed.getTime() - 1000 * 3600 * 24 * 1);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: Vue.prototype.$lang('datePickerOptions.lastWeek'),
            onClick(picker) {
                let timed = new Date(new Date().toLocaleDateString());
                let end = new Date(timed.getTime() + 1000 * 3600 * 24 - 1);
                let start = new Date(timed.getTime() - 1000 * 3600 * 24 * 6);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: Vue.prototype.$lang('datePickerOptions.lastMonth'),
            onClick(picker) {
                let timed = new Date(new Date().toLocaleDateString());
                let end = new Date(timed.getTime() + 1000 * 3600 * 24 - 1);
                let start = new Date(timed.getFullYear(), timed.getMonth(), 1);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: Vue.prototype.$lang('datePickerOptions.lastThreeMonths'),
            onClick(picker) {
                let timed = new Date(new Date().toLocaleDateString());
                let end = new Date(timed.getTime() + 1000 * 3600 * 24 - 1);
                let start = new Date(timed.getFullYear(), timed.getMonth() - 2, 1);
                picker.$emit('pick', [start, end]);
            }
        }, {
            text: Vue.prototype.$lang('datePickerOptions.lastSixMonths'),
            onClick(picker) {
                let timed = new Date(new Date().toLocaleDateString());
                let end = new Date(timed.getTime() + 1000 * 3600 * 24 - 1);
                let start = new Date(timed.getFullYear(), timed.getMonth() - 5, 1);
                picker.$emit('pick', [start, end]);
            }
        }]
    },
};
