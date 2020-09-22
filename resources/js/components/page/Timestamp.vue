<template>
    <span v-if="type == 'format'">
        {{ timestampByJS | timestampToView(storeTimezone, format) }}
    </span>
    <span v-else-if="type == 'year'">
        {{ timestampByJS | timestampToView(storeTimezone, 'YYYYZ') }}</span>
    <span v-else-if="type == 'month'">
        {{ timestampByJS | timestampToView(storeTimezone, 'YYYY-MMZ') }}</span>
    <span v-else-if="type == 'day'">
        {{ timestampByJS | timestampToView(storeTimezone, 'YYYY-MM-DDZ') }}</span>
    <span v-else-if="type == 'week'">
        {{ timestampByJS | timestampToView(storeTimezone, $t('timestamp.week')) }}</span>
    <span v-else-if="type == 'week-day'">
        {{ timestampByJS | timestampToView(storeTimezone, 'YYYY-MM-DDZ') }} ~ {{ timestampByJS + 86400 * 6000 | timestampToView(storeTimezone, 'YYYY-MM-DDZ') }}</span>
    <span v-else-if="type == 'hour'">
        {{ timestampByJS | timestampToView(storeTimezone, 'YYYY-MM-DDTHH:00Z') }}</span>
    <span v-else-if="type == 'minute'">
        {{ timestampByJS | timestampToView(storeTimezone, 'YYYY-MM-DDTHH:mmZ') }}</span>
    <span v-else-if="type == 'second'">
        {{ timestampByJS | timestampToView(storeTimezone, 'YYYY-MM-DDTHH:mm:ssZ') }}</span>
    <span v-else>
        {{ timestampByJS | timestampToView(storeTimezone) }}
    </span>
</template>

<script>
    import momentTimezone from 'moment-timezone'

    export default {
        name: "Timestamp",
        props: {
            type: {
                type: String,
                default: 'default'
            },
            timestamp: {
                type: [String, Number],
                default: 0
            },
            format: String,
        },
        computed: {
            timestampByJS: function () {
                // 转换为数字
                let timestamp = Number(this.timestamp);
                // 转换为毫秒
                return timestamp > 10000000000 ? timestamp : timestamp * 1000;
            },
            storeTimezone() {
                return this.$store ? this.$store.getters['home/config/getTimezone'] : 'PRC';
            },
        },
        filters: {
            // 将时间戳转换为PRC可视时间
            timestampToView: function (timestamp, storeTimezone, format) {
                return momentTimezone(timestamp).tz(storeTimezone).format(format);
            }
        },
    }
</script>

<style scoped>

</style>
