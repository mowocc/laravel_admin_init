import Vue from 'vue';
import momentTimezone from 'moment-timezone';

Vue.prototype.$helper = {
    // 获取GET参数
    getQueryString(name) {
        let reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        let reg_rewrite = new RegExp("(^|/)" + name + "/([^/]*)(/|$)", "i");
        let r = window.location.search.substr(1).match(reg);
        let q = window.location.pathname.substr(1).match(reg_rewrite);
        if (r != null) {
            return unescape(r[2]);
        } else if (q != null) {
            return unescape(q[2]);
        } else {
            return null;
        }
    },
    // 将时间戳转换为PRC可视时间
    makeTimestampToDatetime(timestamp, format) {
        // 转换为数字
        timestamp = Number(timestamp);
        // 转换为毫秒
        timestamp = timestamp > 10000000000 ? timestamp : timestamp * 1000;
        // 时间转换
        return momentTimezone(timestamp).tz('PRC').format(format);
    },
    // 将PRC可视时间转换为时间戳
    makeDatetimeToTimestamp(timezone, datetime) {
        return momentTimezone.tz(datetime, timezone || 'PRC').unix();
    },
    // 是否存在交集
    isIntersectionExist(...arrays) {
        if (_.intersection(...arrays).length) {
            return true;
        } else {
            return false;
        }
    },
};
