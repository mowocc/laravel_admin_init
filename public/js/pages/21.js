webpackJsonp([21],{

/***/ 348:
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),

/***/ 405:
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(348)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),

/***/ 409:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(431)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(433)
/* template */
var __vue_template__ = __webpack_require__(444)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-45eccc12"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/pages/agency/setting/Index.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-45eccc12", Component.options)
  } else {
    hotAPI.reload("data-v-45eccc12", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 431:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(432);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("48a9de68", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-45eccc12\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Index.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-45eccc12\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Index.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 432:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n.header-aside .icon[data-v-45eccc12] {\n  -ms-flex-negative: 0;\n      flex-shrink: 0;\n  width: 44px;\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-align: center;\n      -ms-flex-align: center;\n          align-items: center;\n  -webkit-box-pack: center;\n      -ms-flex-pack: center;\n          justify-content: center;\n  font-size: 1.2rem;\n  cursor: pointer;\n  color: #909399;\n}\n.header-aside .icon[data-v-45eccc12]:hover {\n  color: #606266;\n}\n[data-v-45eccc12] .container-content {\n  padding: 20px;\n}\n[data-v-45eccc12] .container-content-title {\n  margin-bottom: 20px;\n  border-bottom: 1px solid #EBEEF5;\n  padding-bottom: 5px;\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-align: center;\n      -ms-flex-align: center;\n          align-items: center;\n  -webkit-box-pack: justify;\n      -ms-flex-pack: justify;\n          justify-content: space-between;\n  color: #303133;\n}\n", ""]);

// exports


/***/ }),

/***/ 433:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Org_vue__ = __webpack_require__(434);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Org_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__Org_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Info_vue__ = __webpack_require__(439);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__Info_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__Info_vue__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




/* harmony default export */ __webpack_exports__["default"] = ({
    components: {
        AgencyOrg: __WEBPACK_IMPORTED_MODULE_0__Org_vue___default.a,
        AgencyInfo: __WEBPACK_IMPORTED_MODULE_1__Info_vue___default.a
    },
    data: function data() {
        return {
            tree: {}
        };
    },

    computed: {
        isSuperAdmin: function isSuperAdmin() {
            return this.$store.state.admin.user.isSuperAdmin;
        }
    },
    methods: {
        treeSelectNode: function treeSelectNode(data) {
            // 缓存数据
            this.tree = data;
        }
    }
});

/***/ }),

/***/ 434:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(435)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(437)
/* template */
var __vue_template__ = __webpack_require__(438)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-69ec4324"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/pages/agency/setting/Org.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-69ec4324", Component.options)
  } else {
    hotAPI.reload("data-v-69ec4324", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 435:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(436);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("4e08e432", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-69ec4324\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Org.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-69ec4324\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Org.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 436:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n.tree-node[data-v-69ec4324] {\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-orient: horizontal;\n  -webkit-box-direction: normal;\n      -ms-flex-direction: row;\n          flex-direction: row;\n  -webkit-box-flex: 1;\n      -ms-flex: auto;\n          flex: auto;\n  -webkit-box-align: center;\n      -ms-flex-align: center;\n          align-items: center;\n  font-size: 0.875rem;\n}\n.tree-node .node-info[data-v-69ec4324] {\n  -webkit-box-flex: 1;\n      -ms-flex: auto;\n          flex: auto;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  white-space: nowrap;\n}\n.tree-node .node-icon[data-v-69ec4324] {\n  color: #c7daf1;\n  margin-right: 5px;\n}\n.tree-node .node-option[data-v-69ec4324] {\n  display: none;\n  -ms-flex-negative: 0;\n      flex-shrink: 0;\n  text-align: center;\n  width: 50px;\n}\n.el-tree-node.is-current > .el-tree-node__content .tree-node .node-option[data-v-69ec4324],\n.el-tree-node__content:hover .node-option[data-v-69ec4324] {\n  display: inline-block;\n}\n", ""]);

// exports


/***/ }),

/***/ 437:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "AgencyOrg",
    data: function data() {
        return {
            dataList: [],
            treeListLoading: 'dle',
            treeList: [],
            treeProps: {
                label: 'name',
                children: 'children'
            },
            nodeCache: {
                data: {},
                node: {}
            },
            nodePreCache: {
                node: {},
                nodeKey: 0
            },
            dialog: {
                visible: {
                    create: false,
                    update: false,
                    destroy: false
                }
            },
            dataCreate: {
                name: '',
                parent_id: 0,
                parent_name: 0
            },
            dataUpdate: {
                id: 0,
                sort: 0,
                name: ''
            },
            msg: {
                code: 200,
                message: '',
                errors: {}
            }
        };
    },

    computed: {
        isSuperAdmin: function isSuperAdmin() {
            return this.$store.state.admin.user.isSuperAdmin;
        }
    },
    watch: {
        treeList: {
            deep: true,
            handler: function handler(n, o) {
                this.treeListLoading = !this.treeList.length ? 'nodata' : 'dle';
            }
        }
    },
    created: function created() {
        this.getTreeList();
    },
    methods: {
        getTreeList: function getTreeList() {
            var _this = this;

            this.treeListLoading = 'loading';
            axios.get('/agency/setting/org/getList').then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this.treeList = response.data.resp_data.treeList;
                    _this.dataList = response.data.resp_data.dataList;
                    // 初始化选中根节点
                    _this.$nextTick(function () {
                        if (this.treeList.length) {
                            this.$refs.treeOrg.setCurrentKey(this.treeList[0].id);
                            this.selectNode(this.treeList[0]);
                        }
                    });
                } else {
                    _this.treeListLoading = 'error';
                }
            });
        },

        // 节点有父级权限
        hasParentAgency: function hasParentAgency(data) {
            if (this.dataList[data.parent_id] != null) {
                return true;
            } else if (this.isSuperAdmin) {
                return true;
            }
            return false;
        },

        // 点击选中 tree 节点
        selectNode: function selectNode(data) {
            // 对外广播事件
            this.$emit('click', {
                id: data.id,
                name: data.name,
                parent_id: data.parent_id,
                isParentAgency: this.hasParentAgency(data)
            });
        },

        // 节点修改或删除
        nodeUpdateOrDelete: function nodeUpdateOrDelete(data) {
            if (this.nodeCache.node.key != this.nodePreCache.nodeKey) {
                return false;
            }
            if (_.isEmpty(data)) {
                this.$emit('click', {});
            } else {
                this.selectNode(data);
            }
        },

        // 打开 option 弹窗扩展处理
        nodeOptionSelectNode: function nodeOptionSelectNode(data, node) {
            // 缓存触发选中节点数据
            this.nodeCache.data = data;
            // 缓存触发选中节点
            this.nodeCache.node = node;
        },

        // 隐藏 option 弹窗扩展处理
        nodeOptionVisibleChange: function nodeOptionVisibleChange(visible) {
            if (visible) {
                // 缓存当前选中节点
                this.nodePreCache.node = this.$refs.treeOrg.getCurrentNode();
                // 缓存当前选中节点 key
                this.nodePreCache.nodeKey = this.$refs.treeOrg.getCurrentKey();
                // 修改当前选中节点
                this.$refs.treeOrg.setCurrentKey(this.nodeCache.node.key);
            } else {
                // 还原之前选中节点
                this.$refs.treeOrg.setCurrentKey(this.nodePreCache.nodeKey);
            }
        },

        // 分发 option 操作选项
        dialogNodeOption: function dialogNodeOption(option) {
            switch (option) {
                case 'create-root':
                    this.initDataCreateRoot();
                    this.dialog.visible.create = true;
                    break;
                case 'create':
                    this.initDataCreate();
                    this.dialog.visible.create = true;
                    break;
                case 'update':
                    this.initDataUpdate();
                    this.dialog.visible.update = true;
                    break;
                case 'destroy':
                    this.dialog.visible.destroy = true;
                    break;
            }
        },

        // 初始化参数
        initMsg: function initMsg() {
            this.msg.code = 200;
            this.msg.message = '';
            this.msg.errors = {};
        },

        // 初始化新增子节点数据【根节点】
        initDataCreateRoot: function initDataCreateRoot() {
            this.initMsg();
            this.dataCreate.name = '';
            this.dataCreate.parent_id = 0;
            this.dataCreate.parent_name = '';
        },

        // 初始化新增子节点数据
        initDataCreate: function initDataCreate() {
            this.initMsg();
            this.dataCreate.name = '';
            this.dataCreate.parent_id = this.nodeCache.data.id;
            this.dataCreate.parent_name = this.nodeCache.data.name;
        },

        // 编初始化辑节点数据
        initDataUpdate: function initDataUpdate() {
            this.initMsg();
            this.dataUpdate.id = this.nodeCache.data.id;
            this.dataUpdate.sort = this.nodeCache.data.sort;
            this.dataUpdate.name = this.nodeCache.data.name;
        },

        // 新增子节点
        treeNodeCreate: function treeNodeCreate() {
            var _this2 = this;

            this.initMsg();
            // loading 状态 start
            var loading = this.$loading();
            // 保存数据
            axios.post('/agency/setting/org/store', this.dataCreate).then(function (response) {
                // loading 状态 close
                loading.close();
                // 逻辑处理
                if (response.data.resp_msg.code == 200) {
                    _this2.$message({
                        type: 'success',
                        message: _this2.$t('messages.succeeded', { status: _this2.$t('action.new') }),
                        showClose: true
                    });
                    _this2.dialog.visible.create = false;
                    // 返回节点数据
                    var data = response.data.resp_data;
                    // 添加节点数据到列表
                    _this2.$set(_this2.dataList, data.id, data);
                    // 添加节点到 tree 组件
                    if (data.parent_id == 0) {
                        _this2.treeList.push(data);
                    } else {
                        _this2.$refs.treeOrg.append(data, _this2.nodeCache.node);
                    }
                } else if (_.includes([42000], response.data.resp_msg.code)) {
                    _this2.msg = response.data.resp_msg;
                } else {
                    _this2.$message({
                        type: 'error',
                        message: _this2.$t('messages.failed', { status: _this2.$t('action.new') }),
                        showClose: true
                    });
                }
            });
        },

        // 编辑节点
        treeNodeUpdate: function treeNodeUpdate() {
            var _this3 = this;

            this.initMsg();
            // loading 状态 start
            var loading = this.$loading();
            // 保存数据
            axios.post('/agency/setting/org/update', this.dataUpdate).then(function (response) {
                // loading 状态 close
                loading.close();
                // 逻辑处理
                if (response.data.resp_msg.code == 200) {
                    _this3.$message({
                        type: 'success',
                        message: _this3.$t('messages.succeeded', { status: _this3.$t('action.edit') }),
                        showClose: true
                    });
                    _this3.dialog.visible.update = false;
                    // 同步数据到 tree 节点
                    _this3.treeNodeSyncData(response.data.resp_data);
                    // 处理对外节点数据
                    _this3.nodeUpdateOrDelete(response.data.resp_data);
                } else if (_.includes([42000], response.data.resp_msg.code)) {
                    _this3.msg = response.data.resp_msg;
                } else {
                    _this3.$message({
                        type: 'error',
                        message: _this3.$t('messages.failed', { status: _this3.$t('action.edit') }),
                        showClose: true
                    });
                }
            });
        },

        // 同步数据到 tree 节点，并排序
        treeNodeSyncData: function treeNodeSyncData(data) {
            // 更新列表数据
            this.dataList[data.id].name = data.name;
            this.dataList[data.id].sort = data.sort;
            // 更新当前节点
            this.nodeCache.data.name = data.name;
            this.nodeCache.data.sort = data.sort;
        },

        // 删除节点
        treeNodeDestroy: function treeNodeDestroy() {
            var _this4 = this;

            this.dialog.visible.destroy = false;
            // loading 状态 start
            var loading = this.$loading();
            // 保存数据
            axios.post('/agency/setting/org/destroy', {
                id: this.nodeCache.data.id
            }).then(function (response) {
                // loading 状态 close
                loading.close();
                // 逻辑处理
                if (response.data.resp_msg.code == 200) {
                    _this4.$message({
                        type: 'success',
                        message: _this4.$t('messages.succeeded', { status: _this4.$t('action.delete') }),
                        showClose: true
                    });
                    // 从列表删除节点数据
                    _this4.$delete(_this4.dataList, _this4.nodeCache.data.id);
                    //  从 tree 组件删除节点
                    _this4.$refs.treeOrg.remove(_this4.nodeCache.node);
                    // 处理对外节点数据
                    _this4.nodeUpdateOrDelete(null);
                } else if (response.data.resp_msg.code == 44221) {
                    _this4.$message({
                        type: 'warning',
                        message: _this4.$t('messages.delete-tree-failed'),
                        showClose: true
                    });
                } else {
                    _this4.$message({
                        type: 'error',
                        message: _this4.$t('messages.failed', { status: _this4.$t('action.delete') }),
                        showClose: true
                    });
                }
            });
        }
    }
});

/***/ }),

/***/ 438:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "h-100" },
    [
      !_vm.treeList.length || _vm.treeListLoading != "dle"
        ? _c(
            "div",
            { staticClass: "p-2" },
            [
              _c("component-page-loading", {
                attrs: { status: _vm.treeListLoading },
                on: { reload: _vm.getTreeList }
              })
            ],
            1
          )
        : _c(
            "vue-simplebar",
            [
              _c("el-tree", {
                ref: "treeOrg",
                attrs: {
                  "highlight-current": "",
                  "node-key": "id",
                  indent: 18,
                  data: _vm.treeList,
                  props: _vm.treeProps,
                  "expand-on-click-node": false
                },
                on: { "current-change": _vm.selectNode },
                scopedSlots: _vm._u([
                  {
                    key: "default",
                    fn: function(ref) {
                      var node = ref.node
                      var data = ref.data
                      return _c(
                        "div",
                        { staticClass: "tree-node" },
                        [
                          _c("div", { staticClass: "node-info" }, [
                            _c("i", {
                              staticClass: "el-icon-folder node-icon"
                            }),
                            _vm._v(_vm._s(data.name) + "\n                ")
                          ]),
                          _vm._v(" "),
                          _c(
                            "el-dropdown",
                            {
                              staticClass: "node-option",
                              attrs: {
                                size: "small",
                                trigger: "click",
                                placement: "bottom"
                              },
                              on: {
                                command: _vm.dialogNodeOption,
                                "visible-change": _vm.nodeOptionVisibleChange
                              }
                            },
                            [
                              _c("i", {
                                staticClass: "el-icon-more",
                                on: {
                                  click: function($event) {
                                    $event.stopPropagation()
                                    return _vm.nodeOptionSelectNode(data, node)
                                  }
                                }
                              }),
                              _vm._v(" "),
                              _c(
                                "el-dropdown-menu",
                                {
                                  attrs: { slot: "dropdown" },
                                  slot: "dropdown"
                                },
                                [
                                  _vm.hasParentAgency(data)
                                    ? _c(
                                        "el-dropdown-item",
                                        { attrs: { command: "update" } },
                                        [
                                          _c("i", {
                                            staticClass: "el-icon-folder"
                                          }),
                                          _vm._v(
                                            _vm._s(_vm.$t("action.edit")) +
                                              "\n                        "
                                          )
                                        ]
                                      )
                                    : _vm._e(),
                                  _vm._v(" "),
                                  _c(
                                    "el-dropdown-item",
                                    { attrs: { command: "destroy" } },
                                    [
                                      _c("i", {
                                        staticClass: "el-icon-folder-delete"
                                      }),
                                      _vm._v(
                                        _vm._s(_vm.$t("action.delete")) +
                                          "\n                        "
                                      )
                                    ]
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "el-dropdown-item",
                                    { attrs: { command: "create" } },
                                    [
                                      _c("i", {
                                        staticClass: "el-icon-folder-add"
                                      }),
                                      _vm._v(
                                        _vm._s(
                                          _vm.$t("agency.setting.tree.create")
                                        ) + "\n                        "
                                      )
                                    ]
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "el-dropdown-item",
                                    { attrs: { disabled: "" } },
                                    [_vm._v("ID : " + _vm._s(data.id))]
                                  )
                                ],
                                1
                              )
                            ],
                            1
                          )
                        ],
                        1
                      )
                    }
                  }
                ])
              })
            ],
            1
          ),
      _vm._v(" "),
      _c(
        "el-dialog",
        {
          attrs: {
            title: _vm.$t("agency.setting.tree.dialog.create"),
            width: "400px",
            visible: _vm.dialog.visible.create
          },
          on: {
            "update:visible": function($event) {
              return _vm.$set(_vm.dialog.visible, "create", $event)
            }
          },
          nativeOn: {
            submit: function($event) {
              $event.preventDefault()
            }
          }
        },
        [
          _c(
            "div",
            { staticClass: "pr-5" },
            [
              _c(
                "el-form",
                { attrs: { model: _vm.dataCreate, "label-width": "80px" } },
                [
                  _c(
                    "el-form-item",
                    {
                      attrs: {
                        label: _vm.$t("agency.setting.tree.dialog.parent")
                      }
                    },
                    [
                      _c("el-tag", { attrs: { type: "info", size: "mini" } }, [
                        _vm._v(_vm._s(_vm.dataCreate.parent_id))
                      ]),
                      _vm._v(" "),
                      _c("span", { staticClass: "ml-1" }, [
                        _vm._v(_vm._s(_vm.dataCreate.parent_name))
                      ])
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-form-item",
                    {
                      attrs: {
                        label: _vm.$t("agency.setting.tree.dialog.name"),
                        error: Boolean(_vm.msg.errors["name"])
                          ? _vm.msg.errors["name"][0]
                          : "",
                        required: ""
                      }
                    },
                    [
                      _c("el-input", {
                        model: {
                          value: _vm.dataCreate.name,
                          callback: function($$v) {
                            _vm.$set(_vm.dataCreate, "name", $$v)
                          },
                          expression: "dataCreate.name"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-form-item",
                    [
                      _c(
                        "el-button",
                        {
                          attrs: { type: "primary" },
                          on: { click: _vm.treeNodeCreate }
                        },
                        [_vm._v(_vm._s(_vm.$t("action.confirm")))]
                      ),
                      _vm._v(" "),
                      _c(
                        "el-button",
                        {
                          on: {
                            click: function($event) {
                              _vm.dialog.visible.create = false
                            }
                          }
                        },
                        [_vm._v(_vm._s(_vm.$t("action.cancel")))]
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "el-dialog",
        {
          attrs: {
            title: _vm.$t("agency.setting.tree.dialog.update"),
            width: "400px",
            visible: _vm.dialog.visible.update
          },
          on: {
            "update:visible": function($event) {
              return _vm.$set(_vm.dialog.visible, "update", $event)
            }
          }
        },
        [
          _c(
            "div",
            { staticClass: "pr-5" },
            [
              _c(
                "el-form",
                { attrs: { model: _vm.dataUpdate, "label-width": "80px" } },
                [
                  _c(
                    "el-form-item",
                    {
                      attrs: {
                        label: _vm.$t("agency.setting.tree.dialog.sort"),
                        error: Boolean(_vm.msg.errors["sort"])
                          ? _vm.msg.errors["sort"][0]
                          : "",
                        required: ""
                      }
                    },
                    [
                      _c("el-input", {
                        attrs: {
                          placeholder: _vm.$t(
                            "agency.setting.tree.dialog.sortPlaceholder"
                          )
                        },
                        model: {
                          value: _vm.dataUpdate.sort,
                          callback: function($$v) {
                            _vm.$set(_vm.dataUpdate, "sort", $$v)
                          },
                          expression: "dataUpdate.sort"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-form-item",
                    {
                      attrs: {
                        label: _vm.$t("agency.setting.tree.dialog.name"),
                        error: Boolean(_vm.msg.errors["name"])
                          ? _vm.msg.errors["name"][0]
                          : "",
                        required: ""
                      }
                    },
                    [
                      _c("el-input", {
                        model: {
                          value: _vm.dataUpdate.name,
                          callback: function($$v) {
                            _vm.$set(_vm.dataUpdate, "name", $$v)
                          },
                          expression: "dataUpdate.name"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-form-item",
                    [
                      _c(
                        "el-button",
                        {
                          attrs: { type: "primary" },
                          on: { click: _vm.treeNodeUpdate }
                        },
                        [_vm._v(_vm._s(_vm.$t("action.confirm")))]
                      ),
                      _vm._v(" "),
                      _c(
                        "el-button",
                        {
                          on: {
                            click: function($event) {
                              _vm.dialog.visible.update = false
                            }
                          }
                        },
                        [_vm._v(_vm._s(_vm.$t("action.cancel")))]
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _c(
        "el-dialog",
        {
          attrs: {
            title: _vm.$t("agency.setting.tree.dialog.destroy"),
            center: "",
            width: "320px",
            visible: _vm.dialog.visible.destroy
          },
          on: {
            "update:visible": function($event) {
              return _vm.$set(_vm.dialog.visible, "destroy", $event)
            }
          }
        },
        [
          _c("div", {
            staticClass: "text-center",
            domProps: {
              innerHTML: _vm._s(
                _vm.$t("confirm.tree-delete", { name: _vm.nodeCache.data.name })
              )
            }
          }),
          _vm._v(" "),
          _c("div", {
            staticClass: "text-center",
            domProps: { innerHTML: _vm._s(_vm.$t("confirm.tree-delete-org")) }
          }),
          _vm._v(" "),
          _c(
            "div",
            { attrs: { slot: "footer" }, slot: "footer" },
            [
              _c(
                "el-button",
                {
                  attrs: { type: "primary" },
                  on: { click: _vm.treeNodeDestroy }
                },
                [_vm._v(_vm._s(_vm.$t("action.confirm")))]
              ),
              _vm._v(" "),
              _c(
                "el-button",
                {
                  on: {
                    click: function($event) {
                      _vm.dialog.visible.destroy = false
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.$t("action.cancel")))]
              )
            ],
            1
          )
        ]
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-69ec4324", module.exports)
  }
}

/***/ }),

/***/ 439:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(440)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(442)
/* template */
var __vue_template__ = __webpack_require__(443)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-db0adcc4"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/pages/agency/setting/Info.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-db0adcc4", Component.options)
  } else {
    hotAPI.reload("data-v-db0adcc4", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 440:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(441);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("7451cca1", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-db0adcc4\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Info.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-db0adcc4\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Info.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 441:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n", ""]);

// exports


/***/ }),

/***/ 442:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "AgencyInfo",
    props: ['tree']
});

/***/ }),

/***/ 443:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "container-content" },
    [
      _c("div", { staticClass: "container-content-title" }, [
        _vm._v(_vm._s(_vm.$t("agency.setting.info.title")))
      ]),
      _vm._v(" "),
      _c(
        "el-form",
        { staticClass: "el-form-detail", attrs: { "label-width": "120px" } },
        [
          _c(
            "el-row",
            [
              _c(
                "el-col",
                { attrs: { md: 12 } },
                [
                  _c(
                    "el-form-item",
                    { attrs: { label: _vm.tree.name } },
                    [
                      _c(
                        "el-tag",
                        {
                          attrs: { effect: "plain", size: "mini", type: "info" }
                        },
                        [_vm._v(_vm._s(_vm.tree.id))]
                      )
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "el-col",
                { attrs: { md: 12 } },
                [
                  _c(
                    "el-form-item",
                    { attrs: { label: "parent_id" } },
                    [
                      _c(
                        "el-tag",
                        {
                          attrs: { effect: "plain", size: "mini", type: "info" }
                        },
                        [_vm._v(_vm._s(_vm.tree.parent_id))]
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-db0adcc4", module.exports)
  }
}

/***/ }),

/***/ 444:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "page-container" }, [
    _c("div", { staticClass: "page-layout" }, [
      _c("div", { staticClass: "page-layout-header" }, [
        _c("div", { staticClass: "header-aside flex-start-title" }, [
          _c("div", { staticClass: "title" }, [
            _vm._v(_vm._s(_vm.$t("agency.setting.titleSide")))
          ]),
          _vm._v(" "),
          _vm.isSuperAdmin
            ? _c("div", { staticClass: "option" }, [
                _c(
                  "div",
                  {
                    staticClass: "icon",
                    attrs: { title: _vm.$t("agency.setting.tree.create-root") },
                    on: {
                      click: function($event) {
                        return _vm.$refs.tree.dialogNodeOption("create-root")
                      }
                    }
                  },
                  [_c("i", { staticClass: "el-icon-plus" })]
                )
              ])
            : _vm._e()
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "header-main" }, [
          _c("div", { staticClass: "title" }, [
            _vm._v(_vm._s(_vm.$t("agency.setting.title")))
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "page-layout-body" }, [
        _c(
          "div",
          { staticClass: "body-aside" },
          [
            _c("agency-org", { ref: "tree", on: { click: _vm.treeSelectNode } })
          ],
          1
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "body-main" },
          [
            _c("vue-simplebar", [
              _c(
                "div",
                {
                  directives: [
                    {
                      name: "show",
                      rawName: "v-show",
                      value: _vm.tree.id != null,
                      expression: "tree.id != null"
                    }
                  ]
                },
                [
                  _c("agency-info", { attrs: { tree: _vm.tree } }),
                  _vm._v(" "),
                  _c("div", { staticClass: "container-content" }, [
                    _c("div", { staticClass: "container-content-title" }, [
                      _vm._v(_vm._s(_vm.$t("agency.setting.other.title")))
                    ]),
                    _vm._v(
                      "\n                            ...\n                        "
                    )
                  ])
                ],
                1
              )
            ])
          ],
          1
        )
      ])
    ])
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-45eccc12", module.exports)
  }
}

/***/ })

});