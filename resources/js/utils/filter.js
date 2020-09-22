import Vue from 'vue';

Vue.filter('hsFilterKeyword', function (string, keyword) {
    keyword = String(keyword).replace(/(^\s*)|(\s*$)/g, '');
    if (!string || !keyword) {
        return string;
    }
    keyword = keyword.replace(/\s+/g, ' ');
    let keys = keyword.split(' ');
    for (let i in keys) {
        let reg = new RegExp(keys[i], 'g');
        let em = '<em>' + keys[i] + '</em>';
        string = String(string).replace(reg, em);
    }
    return string;
})

