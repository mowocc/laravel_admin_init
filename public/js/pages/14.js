webpackJsonp([14],{

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

/***/ 415:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(525)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(527)
/* template */
var __vue_template__ = __webpack_require__(548)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-1451f9f9"
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
Component.options.__file = "resources/js/pages/system/permission/Index.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-1451f9f9", Component.options)
  } else {
    hotAPI.reload("data-v-1451f9f9", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 525:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(526);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("ff8101cc", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-1451f9f9\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Index.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-1451f9f9\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Index.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 526:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n.header-aside .icon[data-v-1451f9f9] {\n  -ms-flex-negative: 0;\n      flex-shrink: 0;\n  width: 44px;\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-align: center;\n      -ms-flex-align: center;\n          align-items: center;\n  -webkit-box-pack: center;\n      -ms-flex-pack: center;\n          justify-content: center;\n  font-size: 1.2rem;\n  cursor: pointer;\n  color: #909399;\n}\n.header-aside .icon[data-v-1451f9f9]:hover {\n  color: #606266;\n}\n.role-container[data-v-1451f9f9] {\n  padding: 15px;\n  font-size: 0.875rem;\n}\n.role-item[data-v-1451f9f9] {\n  padding: 30px 0;\n}\n.role-item + .role-item[data-v-1451f9f9] {\n  border-top: 1px solid #F2F6FC;\n}\n.role-item .item-tree-name[data-v-1451f9f9] {\n  color: #303133;\n}\n.role-item .item-title[data-v-1451f9f9] {\n  color: #303133;\n}\n", ""]);

// exports


/***/ }),

/***/ 527:
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Tree_vue__ = __webpack_require__(528);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__Tree_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__Tree_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__User_vue__ = __webpack_require__(533);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__User_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__User_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Menu_vue__ = __webpack_require__(538);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_2__Menu_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_2__Menu_vue__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__Org_vue__ = __webpack_require__(543);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_3__Org_vue___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_3__Org_vue__);
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
    components: {
        PermissionTree: __WEBPACK_IMPORTED_MODULE_0__Tree_vue___default.a,
        PermissionUser: __WEBPACK_IMPORTED_MODULE_1__User_vue___default.a,
        PermissionMenu: __WEBPACK_IMPORTED_MODULE_2__Menu_vue___default.a,
        PermissionOrg: __WEBPACK_IMPORTED_MODULE_3__Org_vue___default.a
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

/***/ 528:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(529)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(531)
/* template */
var __vue_template__ = __webpack_require__(532)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-b14ebc72"
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
Component.options.__file = "resources/js/pages/system/permission/Tree.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b14ebc72", Component.options)
  } else {
    hotAPI.reload("data-v-b14ebc72", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 529:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(530);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("7236c699", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b14ebc72\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Tree.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-b14ebc72\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Tree.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 530:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n.tree-node[data-v-b14ebc72] {\n  display: -webkit-box;\n  display: -ms-flexbox;\n  display: flex;\n  -webkit-box-orient: horizontal;\n  -webkit-box-direction: normal;\n      -ms-flex-direction: row;\n          flex-direction: row;\n  -webkit-box-flex: 1;\n      -ms-flex: auto;\n          flex: auto;\n  -webkit-box-align: center;\n      -ms-flex-align: center;\n          align-items: center;\n  font-size: 0.875rem;\n}\n.tree-node .node-info[data-v-b14ebc72] {\n  -webkit-box-flex: 1;\n      -ms-flex: auto;\n          flex: auto;\n  overflow: hidden;\n  text-overflow: ellipsis;\n  white-space: nowrap;\n}\n.tree-node .node-icon[data-v-b14ebc72] {\n  color: #c7daf1;\n  margin-right: 5px;\n}\n.tree-node .node-option[data-v-b14ebc72] {\n  display: none;\n  -ms-flex-negative: 0;\n      flex-shrink: 0;\n  text-align: center;\n  width: 50px;\n}\n.el-tree-node.is-current > .el-tree-node__content .tree-node .node-option[data-v-b14ebc72],\n.el-tree-node__content:hover .node-option[data-v-b14ebc72] {\n  display: inline-block;\n}\n", ""]);

// exports


/***/ }),

/***/ 531:
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

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "PermissionTree",
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
            axios.get('/system/permission/tree/getList').then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this.treeList = response.data.resp_data.treeList;
                    _this.dataList = response.data.resp_data.dataList;
                    // 初始化选中根节点
                    _this.$nextTick(function () {
                        if (this.treeList.length) {
                            this.$refs.treeRole.setCurrentKey(this.treeList[0].id);
                            this.selectNode(this.treeList[0]);
                        }
                    });
                } else {
                    _this.treeListLoading = 'error';
                }
            });
        },

        // 节点有父级权限
        hasParentPermission: function hasParentPermission(data) {
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
                isParentPermission: this.hasParentPermission(data)
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
                this.nodePreCache.node = this.$refs.treeRole.getCurrentNode();
                // 缓存当前选中节点 key
                this.nodePreCache.nodeKey = this.$refs.treeRole.getCurrentKey();
                // 修改当前选中节点
                this.$refs.treeRole.setCurrentKey(this.nodeCache.node.key);
            } else {
                // 还原之前选中节点
                this.$refs.treeRole.setCurrentKey(this.nodePreCache.nodeKey);
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
            axios.post('/system/permission/tree/store', this.dataCreate).then(function (response) {
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
                        _this2.$refs.treeRole.append(data, _this2.nodeCache.node);
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
            axios.post('/system/permission/tree/update', this.dataUpdate).then(function (response) {
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
            axios.post('/system/permission/tree/destroy', {
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
                    _this4.$refs.treeRole.remove(_this4.nodeCache.node);
                    // 处理对外节点数据
                    _this4.nodeUpdateOrDelete(null);
                } else if (response.data.resp_msg.code == 44221) {
                    _this4.$message({
                        type: 'warning',
                        message: _this4.$t('messages.delete-agent-failed'),
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

/***/ 532:
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
                ref: "treeRole",
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
                                  _vm.hasParentPermission(data)
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
                                          _vm.$t(
                                            "system.permission.tree.create"
                                          )
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
            title: _vm.$t("system.permission.tree.dialog.create"),
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
                        label: _vm.$t("system.permission.tree.dialog.parent")
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
                        label: _vm.$t("system.permission.tree.dialog.name"),
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
            title: _vm.$t("system.permission.tree.dialog.update"),
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
                        label: _vm.$t("system.permission.tree.dialog.sort"),
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
                            "system.permission.tree.dialog.sortPlaceholder"
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
                        label: _vm.$t("system.permission.tree.dialog.name"),
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
            title: _vm.$t("system.permission.tree.dialog.destroy"),
            center: "",
            width: "340px",
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
    require("vue-hot-reload-api")      .rerender("data-v-b14ebc72", module.exports)
  }
}

/***/ }),

/***/ 533:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(534)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(536)
/* template */
var __vue_template__ = __webpack_require__(537)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-44d73b74"
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
Component.options.__file = "resources/js/pages/system/permission/User.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-44d73b74", Component.options)
  } else {
    hotAPI.reload("data-v-44d73b74", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 534:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(535);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("17a72170", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-44d73b74\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./User.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-44d73b74\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./User.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 535:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n[data-v-44d73b74] .el-dialog__body {\n  min-height: 440px;\n  padding-top: 15px;\n  padding-bottom: 15px;\n}\n", ""]);

// exports


/***/ }),

/***/ 536:
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

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "PermissionUser",
    props: ['tree'],
    data: function data() {
        return {
            userListStatus: 'dle',
            userList: [],
            userListCache: [],
            dialogVisible: false,
            loading: false,
            filterOption: {
                keyword: '',
                page: 1,
                page_size: 5
            },
            dataMeta: {
                total: 0
            },
            dataList: []
        };
    },

    watch: {
        tree: {
            deep: true,
            immediate: true,
            handler: function handler(n, o) {
                // 获取授权用户
                this.getUsers();
            }
        },
        dialogVisible: function dialogVisible(n, o) {
            if (n) {
                // 初始化选中数据
                this.userListCache = _.cloneDeep(this.userList);
            } else {
                // 初始化表格参数
                this.clearFilterOption();
            }
        }
    },
    methods: {
        // 获取授权用户
        getUsers: function getUsers() {
            var _this = this;

            this.userListStatus = 'loading';
            axios.get('/system/permission/tree-user/getUsers', {
                params: {
                    tree_id: this.tree.id
                }
            }).then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this.userList = response.data.resp_data;
                    _this.userListStatus = 'dle';
                } else {
                    _this.userListStatus = 'error';
                }
            });
        },
        clearFilterOption: function clearFilterOption() {
            this.filterOption.keyword = '';
            this.filterOption.page = 1;
            this.dataMeta.total = 0;
            this.dataList = [];
        },
        filterChange: function filterChange() {
            this.filterOption.page = 1;
            this.getDataList();
        },
        filterPageChange: function filterPageChange(page) {
            this.filterOption.page = page;
            this.getDataList();
        },
        getDataList: function getDataList() {
            var _this2 = this;

            this.loading = true;
            axios.get('/system/permission/tree-user/getUserList', {
                params: this.filterOption
            }).then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this2.dataMeta = response.data.resp_data.meta;
                    _this2.dataList = response.data.resp_data.data;
                    // 初始化选中表格
                    _this2.$nextTick(function () {
                        this.$refs.multipleTable.clearSelection();
                        // 初始化表格选中
                        for (var i in this.dataList) {
                            for (var j in this.userListCache) {
                                if (this.userListCache[j].id == this.dataList[i].id) {
                                    this.$refs.multipleTable.toggleRowSelection(this.dataList[i]);
                                    break;
                                }
                            }
                        }
                    });
                } else {
                    _this2.$message({
                        type: 'error',
                        message: _this2.$t('messages.failed', { status: _this2.$t('action.search') }),
                        showClose: true
                    });
                }
                _this2.loading = false;
            });
        },
        onSubmit: function onSubmit() {
            var _this3 = this;

            // loading 状态 start
            var loading = this.$loading();
            // 保存数据
            axios.post('/system/permission/tree-user/update', {
                tree_id: this.tree.id,
                users: _.map(this.userListCache, 'id')
            }).then(function (response) {
                // loading 状态 close
                loading.close();
                // 逻辑处理
                if (response.data.resp_msg.code == 200) {
                    _this3.$message({
                        type: 'success',
                        message: _this3.$t('messages.succeeded', { status: _this3.$t('action.save') }),
                        showClose: true
                    });
                    _this3.dialogVisible = false;
                    // 同步数据
                    _this3.userList = response.data.resp_data;
                } else {
                    _this3.$message({
                        type: 'error',
                        message: _this3.$t('messages.failed', { status: _this3.$t('action.save') }),
                        showClose: true
                    });
                }
            });
        },

        // 标签删除已选用户
        tagCheckedDelete: function tagCheckedDelete(data, index) {
            this.userListCache.splice(index, 1);
            for (var i in this.dataList) {
                if (this.dataList[i].id == data.id) {
                    this.$refs.multipleTable.toggleRowSelection(this.dataList[i]);
                    break;
                }
            }
        },

        // 表格选择或取消选中用户
        handleSelection: function handleSelection(selection, row) {
            for (var i in this.userListCache) {
                if (this.userListCache[i].id == row.id) {
                    this.userListCache.splice(i, 1);
                    return;
                }
            }
            this.userListCache.push(row);
        },

        // 表格选择或取消选中用户【全选】
        handleSelectionAll: function handleSelectionAll(selection) {
            if (_.isEmpty(selection)) {
                var _iteratorNormalCompletion = true;
                var _didIteratorError = false;
                var _iteratorError = undefined;

                try {
                    for (var _iterator = this.dataList[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
                        var item = _step.value;

                        for (var i in this.userListCache) {
                            if (this.userListCache[i].id == item.id) {
                                this.userListCache.splice(i, 1);
                                break;
                            }
                        }
                    }
                } catch (err) {
                    _didIteratorError = true;
                    _iteratorError = err;
                } finally {
                    try {
                        if (!_iteratorNormalCompletion && _iterator.return) {
                            _iterator.return();
                        }
                    } finally {
                        if (_didIteratorError) {
                            throw _iteratorError;
                        }
                    }
                }
            } else {
                var _iteratorNormalCompletion2 = true;
                var _didIteratorError2 = false;
                var _iteratorError2 = undefined;

                try {
                    for (var _iterator2 = selection[Symbol.iterator](), _step2; !(_iteratorNormalCompletion2 = (_step2 = _iterator2.next()).done); _iteratorNormalCompletion2 = true) {
                        var _item = _step2.value;

                        var isExists = false;
                        for (var _i in this.userListCache) {
                            if (this.userListCache[_i].id == _item.id) {
                                isExists = true;
                                break;
                            }
                        }
                        if (!isExists) {
                            this.userListCache.push(_item);
                        }
                    }
                } catch (err) {
                    _didIteratorError2 = true;
                    _iteratorError2 = err;
                } finally {
                    try {
                        if (!_iteratorNormalCompletion2 && _iterator2.return) {
                            _iterator2.return();
                        }
                    } finally {
                        if (_didIteratorError2) {
                            throw _iteratorError2;
                        }
                    }
                }
            }
        }
    }
});

/***/ }),

/***/ 537:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "row role-item" },
    [
      _c("div", { staticClass: "col-2" }, [
        _c("div", { staticClass: "item-title" }, [
          _vm._v(_vm._s(_vm.$t("system.permission.users.title")))
        ])
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "col-8" },
        [
          ["loading", "error"].indexOf(_vm.userListStatus) >= 0
            ? _c("component-page-loading", {
                attrs: { status: _vm.userListStatus },
                on: { reload: _vm.getUsers }
              })
            : !_vm.userList.length
            ? _c("component-page-loading", { attrs: { status: "nodata" } })
            : _c(
                "div",
                { staticClass: "nub-list" },
                _vm._l(_vm.userList, function(item, index) {
                  return _c("div", { key: index, staticClass: "nub-item" }, [
                    _c(
                      "div",
                      { staticClass: "nub-icon icon-user" },
                      [
                        _c("font-awesome-icon", { attrs: { icon: "user-tie" } })
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c("div", { staticClass: "nub-text" }, [
                      _vm._v(_vm._s(item.email))
                    ])
                  ])
                }),
                0
              )
        ],
        1
      ),
      _vm._v(" "),
      _c("div", { staticClass: "col-2 text-right" }, [
        _c(
          "a",
          {
            staticClass: "role-item-option",
            attrs: { href: "javascript:;" },
            on: {
              click: function($event) {
                _vm.dialogVisible = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("action.modify")))]
        )
      ]),
      _vm._v(" "),
      _c(
        "el-dialog",
        {
          attrs: {
            title: _vm.$t("system.permission.users.title"),
            width: "700px",
            visible: _vm.dialogVisible
          },
          on: {
            "update:visible": function($event) {
              _vm.dialogVisible = $event
            }
          }
        },
        [
          _c(
            "div",
            {
              directives: [
                {
                  name: "loading",
                  rawName: "v-loading",
                  value: _vm.loading,
                  expression: "loading"
                }
              ]
            },
            [
              _c("div", { staticClass: "pb-3" }, [
                !_vm.userListCache.length
                  ? _c(
                      "div",
                      [
                        _c("component-page-loading", {
                          attrs: { status: "nodata" }
                        })
                      ],
                      1
                    )
                  : _vm._e(),
                _vm._v(" "),
                _vm.userListCache.length
                  ? _c(
                      "div",
                      { staticClass: "nub-list" },
                      _vm._l(_vm.userListCache, function(item, index) {
                        return _c(
                          "div",
                          { key: index, staticClass: "nub-item" },
                          [
                            _c(
                              "div",
                              { staticClass: "nub-icon icon-user" },
                              [
                                _c("font-awesome-icon", {
                                  attrs: { icon: "user-tie" }
                                })
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c("div", { staticClass: "nub-text" }, [
                              _vm._v(_vm._s(item.email))
                            ]),
                            _vm._v(" "),
                            _c(
                              "div",
                              {
                                staticClass: "nub-close",
                                on: {
                                  click: function($event) {
                                    return _vm.tagCheckedDelete(item, index)
                                  }
                                }
                              },
                              [_c("i", { staticClass: "el-icon-close" })]
                            )
                          ]
                        )
                      }),
                      0
                    )
                  : _vm._e()
              ]),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "pb-3" },
                [
                  _c(
                    "el-input",
                    {
                      attrs: {
                        placeholder: _vm.$t(
                          "system.permission.users.dialog.email"
                        ),
                        clearable: ""
                      },
                      on: { change: _vm.filterChange },
                      model: {
                        value: _vm.filterOption.keyword,
                        callback: function($$v) {
                          _vm.$set(_vm.filterOption, "keyword", $$v)
                        },
                        expression: "filterOption.keyword"
                      }
                    },
                    [
                      _c("el-button", {
                        attrs: { slot: "append", icon: "el-icon-search" },
                        on: { click: _vm.filterChange },
                        slot: "append"
                      })
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "el-table",
                {
                  ref: "multipleTable",
                  staticStyle: { width: "100%" },
                  attrs: { data: _vm.dataList },
                  on: {
                    select: _vm.handleSelection,
                    "select-all": _vm.handleSelectionAll
                  }
                },
                [
                  _c("el-table-column", {
                    attrs: { width: "80", type: "selection" }
                  }),
                  _vm._v(" "),
                  _c("el-table-column", {
                    attrs: {
                      label: _vm.$t("system.permission.users.dialog.email"),
                      prop: "email"
                    },
                    scopedSlots: _vm._u([
                      {
                        key: "default",
                        fn: function(scope) {
                          return [
                            _c("span", {
                              domProps: {
                                innerHTML: _vm._s(
                                  _vm.$options.filters.hsFilterKeyword(
                                    scope.row.email,
                                    _vm.filterOption.keyword
                                  )
                                )
                              }
                            })
                          ]
                        }
                      }
                    ])
                  }),
                  _vm._v(" "),
                  _c("el-table-column", {
                    attrs: {
                      label: _vm.$t("system.permission.users.dialog.name"),
                      prop: "name"
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _vm.dataMeta.total
                ? _c(
                    "div",
                    { staticClass: "pt-2 text-right" },
                    [
                      _c("el-pagination", {
                        attrs: {
                          background: "",
                          layout: "prev, pager, next",
                          "current-page": _vm.filterOption.page,
                          "page-size": _vm.filterOption.page_size,
                          total: _vm.dataMeta.total
                        },
                        on: { "current-change": _vm.filterPageChange }
                      })
                    ],
                    1
                  )
                : _vm._e()
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "dialog-footer",
              attrs: { slot: "footer" },
              slot: "footer"
            },
            [
              _c(
                "el-button",
                {
                  on: {
                    click: function($event) {
                      _vm.dialogVisible = false
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.$t("action.cancel")))]
              ),
              _vm._v(" "),
              _c(
                "el-button",
                { attrs: { type: "primary" }, on: { click: _vm.onSubmit } },
                [_vm._v(_vm._s(_vm.$t("action.confirm")))]
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
    require("vue-hot-reload-api")      .rerender("data-v-44d73b74", module.exports)
  }
}

/***/ }),

/***/ 538:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(539)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(541)
/* template */
var __vue_template__ = __webpack_require__(542)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-30bc7b08"
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
Component.options.__file = "resources/js/pages/system/permission/Menu.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-30bc7b08", Component.options)
  } else {
    hotAPI.reload("data-v-30bc7b08", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 539:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(540);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("25aaccb4", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-30bc7b08\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Menu.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-30bc7b08\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Menu.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 540:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n[data-v-30bc7b08] .el-dialog__body {\n  padding-top: 15px;\n  padding-bottom: 15px;\n  height: 440px;\n}\n.menu-tree-container[data-v-30bc7b08] {\n  border: 1px solid #F2F6FC;\n  padding: 6px 0;\n}\n", ""]);

// exports


/***/ }),

/***/ 541:
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

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "PermissionMenu",
    props: ['tree'],
    data: function data() {
        return {
            menuListStatus: 'dle',
            menuList: [],
            menus: null,
            dialogVisible: false,
            dataListLoading: 'dle',
            dataList: []
        };
    },

    watch: {
        tree: {
            deep: true,
            immediate: true,
            handler: function handler(n, o) {
                // 获取授权页面
                this.getMenus();
            }
        },
        dialogVisible: function dialogVisible(n, o) {
            if (n) {
                // 获取菜单数据
                this.getDataList();
            } else {
                // 初始化菜单数据
                this.dataList = [];
                this.dataListLoading = 'dle';
            }
        }
    },
    methods: {
        // 制作节点名称
        makeTreeNodeName: function makeTreeNodeName(data, node) {
            return this.$t('menu.' + data.route_name);
        },

        // 获取授权页面
        getMenus: function getMenus() {
            var _this = this;

            this.menuListStatus = 'loading';
            axios.get('/system/permission/config/getItem', {
                params: {
                    tree_id: this.tree.id
                }
            }).then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this.menuList = response.data.resp_data.menuList;
                    _this.menus = response.data.resp_data.menus;
                    _this.menuListStatus = 'dle';
                } else {
                    _this.menuListStatus = 'error';
                }
            });
        },
        getDataList: function getDataList() {
            var _this2 = this;

            this.dataListLoading = 'loading';
            axios.get('/system/permission/config/getMenus', {
                params: {
                    tree_id: this.tree.isParentPermission ? this.tree.parent_id : this.tree.id
                }
            }).then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this2.dataList = response.data.resp_data;
                    _this2.dataListLoading = !_this2.dataList.length ? 'nodata' : 'dle';
                } else {
                    _this2.dataListLoading = 'error';
                }
            });
        },
        onSubmit: function onSubmit() {
            var _this3 = this;

            // loading 状态 start
            var loading = this.$loading();
            // 保存数据
            axios.post('/system/permission/config/updateMenus', {
                tree_id: this.tree.id,
                menus: {
                    route_names: _.concat(this.$refs.treeMenu.getCheckedKeys(), this.$refs.treeMenu.getHalfCheckedKeys()),
                    route_names_leaf: this.$refs.treeMenu.getCheckedKeys(true)
                }
            }).then(function (response) {
                // loading 状态 close
                loading.close();
                // 逻辑处理
                if (response.data.resp_msg.code == 200) {
                    _this3.$message({
                        type: 'success',
                        message: _this3.$t('messages.succeeded', { status: _this3.$t('action.save') }),
                        showClose: true
                    });
                    _this3.dialogVisible = false;
                    // 同步数据
                    _this3.menuList = response.data.resp_data.menuList;
                    _this3.menus = response.data.resp_data.menus;
                } else {
                    _this3.$message({
                        type: 'error',
                        message: _this3.$t('messages.failed', { status: _this3.$t('action.save') }),
                        showClose: true
                    });
                }
            });
        }
    }
});

/***/ }),

/***/ 542:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "row role-item" },
    [
      _c("div", { staticClass: "col-2" }, [
        _c("div", { staticClass: "item-title" }, [
          _vm._v(_vm._s(_vm.$t("system.permission.menus.title")))
        ])
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "col-8" },
        [
          ["loading", "error"].indexOf(_vm.menuListStatus) >= 0
            ? _c("component-page-loading", {
                attrs: { status: _vm.menuListStatus },
                on: { reload: _vm.getMenus }
              })
            : !_vm.menuList.length
            ? _c("component-page-loading", { attrs: { status: "nodata" } })
            : _c(
                "div",
                { staticClass: "menu-tree-container" },
                [
                  _c("el-tree", {
                    attrs: {
                      data: _vm.menuList,
                      props: {
                        children: "children",
                        label: _vm.makeTreeNodeName
                      }
                    }
                  })
                ],
                1
              )
        ],
        1
      ),
      _vm._v(" "),
      _c("div", { staticClass: "col-2 text-right" }, [
        _c(
          "a",
          {
            staticClass: "role-item-option",
            attrs: { href: "javascript:;" },
            on: {
              click: function($event) {
                _vm.dialogVisible = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("action.modify")))]
        )
      ]),
      _vm._v(" "),
      _c(
        "el-dialog",
        {
          attrs: {
            title: _vm.$t("system.permission.menus.title"),
            width: "700px",
            visible: _vm.dialogVisible
          },
          on: {
            "update:visible": function($event) {
              _vm.dialogVisible = $event
            }
          }
        },
        [
          !_vm.dataList.length || _vm.dataListLoading != "dle"
            ? _c(
                "div",
                { staticClass: "p-2" },
                [
                  _c("component-page-loading", {
                    attrs: { status: _vm.dataListLoading },
                    on: { reload: _vm.getDataList }
                  })
                ],
                1
              )
            : _vm.dialogVisible
            ? _c(
                "vue-simplebar",
                [
                  _c("el-tree", {
                    ref: "treeMenu",
                    attrs: {
                      data: _vm.dataList,
                      props: {
                        children: "children",
                        label: _vm.makeTreeNodeName
                      },
                      "show-checkbox": "",
                      "node-key": "route_name",
                      "default-checked-keys":
                        _vm.menus == null || _vm.menus.route_names_leaf == null
                          ? []
                          : _vm.menus.route_names_leaf
                    }
                  })
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "dialog-footer",
              attrs: { slot: "footer" },
              slot: "footer"
            },
            [
              _c(
                "el-button",
                {
                  on: {
                    click: function($event) {
                      _vm.dialogVisible = false
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.$t("action.cancel")))]
              ),
              _vm._v(" "),
              _c(
                "el-button",
                { attrs: { type: "primary" }, on: { click: _vm.onSubmit } },
                [_vm._v(_vm._s(_vm.$t("action.confirm")))]
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
    require("vue-hot-reload-api")      .rerender("data-v-30bc7b08", module.exports)
  }
}

/***/ }),

/***/ 543:
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(544)
}
var normalizeComponent = __webpack_require__(5)
/* script */
var __vue_script__ = __webpack_require__(546)
/* template */
var __vue_template__ = __webpack_require__(547)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-57c1becb"
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
Component.options.__file = "resources/js/pages/system/permission/Org.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-57c1becb", Component.options)
  } else {
    hotAPI.reload("data-v-57c1becb", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),

/***/ 544:
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(545);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(405)("6378fded", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-57c1becb\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Org.vue", function() {
     var newContent = require("!!../../../../../node_modules/css-loader/index.js!../../../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-57c1becb\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../node_modules/sass-loader/lib/loader.js!../../../../../node_modules/sass-resources-loader/lib/loader.js?{\"resources\":\"/Users/sa_1992/www/bl_admin_cluster/resources/sass/_variables.scss\"}!../../../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./Org.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),

/***/ 545:
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(54)(false);
// imports


// module
exports.push([module.i, "\n[data-v-57c1becb] .el-dialog__body {\n  padding-top: 15px;\n  padding-bottom: 15px;\n  height: 440px;\n}\n.org-tree-container[data-v-57c1becb] {\n  border: 1px solid #F2F6FC;\n  padding: 6px 0;\n}\n", ""]);

// exports


/***/ }),

/***/ 546:
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

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "PermissionOrg",
    props: ['tree'],
    data: function data() {
        return {
            orgListStatus: 'dle',
            orgList: [],
            orgs: null,
            dialogVisible: false,
            dataListLoading: 'dle',
            dataList: []
        };
    },

    watch: {
        tree: {
            deep: true,
            immediate: true,
            handler: function handler(n, o) {
                // 获取授权代理
                this.getOrgs();
            }
        },
        dialogVisible: function dialogVisible(n, o) {
            if (n) {
                // 获取菜单数据
                this.getDataList();
            } else {
                // 初始化菜单数据
                this.dataList = [];
                this.dataListLoading = 'dle';
            }
        }
    },
    methods: {
        // 获取授权代理
        getOrgs: function getOrgs() {
            var _this = this;

            this.orgListStatus = 'loading';
            axios.get('/system/permission/config/getItem', {
                params: {
                    tree_id: this.tree.id
                }
            }).then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this.orgList = response.data.resp_data.orgList;
                    _this.orgs = response.data.resp_data.orgs;
                    _this.orgListStatus = 'dle';
                } else {
                    _this.orgListStatus = 'error';
                }
            });
        },
        getDataList: function getDataList() {
            var _this2 = this;

            this.dataListLoading = 'loading';
            axios.get('/system/permission/config/getOrgs', {
                params: {
                    tree_id: this.tree.isParentPermission ? this.tree.parent_id : this.tree.id
                }
            }).then(function (response) {
                if (response.data.resp_msg.code == 200) {
                    _this2.dataList = response.data.resp_data;
                    _this2.dataListLoading = !_this2.dataList.length ? 'nodata' : 'dle';
                } else {
                    _this2.dataListLoading = 'error';
                }
            });
        },
        onSubmit: function onSubmit() {
            var _this3 = this;

            // loading 状态 start
            var loading = this.$loading();
            // 保存数据
            axios.post('/system/permission/config/updateOrgs', {
                tree_id: this.tree.id,
                orgs: {
                    ids: this.$refs.treeOrg.getCheckedKeys()
                }
            }).then(function (response) {
                // loading 状态 close
                loading.close();
                // 逻辑处理
                if (response.data.resp_msg.code == 200) {
                    _this3.$message({
                        type: 'success',
                        message: _this3.$t('messages.succeeded', { status: _this3.$t('action.save') }),
                        showClose: true
                    });
                    _this3.dialogVisible = false;
                    // 同步数据
                    _this3.orgList = response.data.resp_data.orgList;
                    _this3.orgs = response.data.resp_data.orgs;
                } else {
                    _this3.$message({
                        type: 'error',
                        message: _this3.$t('messages.failed', { status: _this3.$t('action.save') }),
                        showClose: true
                    });
                }
            });
        }
    }
});

/***/ }),

/***/ 547:
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "row role-item" },
    [
      _c("div", { staticClass: "col-2" }, [
        _c("div", { staticClass: "item-title" }, [
          _vm._v(_vm._s(_vm.$t("system.permission.orgs.title")))
        ])
      ]),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "col-8" },
        [
          ["loading", "error"].indexOf(_vm.orgListStatus) >= 0
            ? _c("component-page-loading", {
                attrs: { status: _vm.orgListStatus },
                on: { reload: _vm.getOrgs }
              })
            : !_vm.orgList.length
            ? _c("component-page-loading", { attrs: { status: "nodata" } })
            : _c(
                "div",
                { staticClass: "org-tree-container" },
                [
                  _c("el-tree", {
                    attrs: {
                      data: _vm.orgList,
                      props: { children: "children", label: "name" }
                    }
                  })
                ],
                1
              )
        ],
        1
      ),
      _vm._v(" "),
      _c("div", { staticClass: "col-2 text-right" }, [
        _c(
          "a",
          {
            staticClass: "role-item-option",
            attrs: { href: "javascript:;" },
            on: {
              click: function($event) {
                _vm.dialogVisible = true
              }
            }
          },
          [_vm._v(_vm._s(_vm.$t("action.modify")))]
        )
      ]),
      _vm._v(" "),
      _c(
        "el-dialog",
        {
          attrs: {
            title: _vm.$t("system.permission.orgs.title"),
            width: "700px",
            visible: _vm.dialogVisible
          },
          on: {
            "update:visible": function($event) {
              _vm.dialogVisible = $event
            }
          }
        },
        [
          !_vm.dataList.length || _vm.dataListLoading != "dle"
            ? _c(
                "div",
                { staticClass: "p-2" },
                [
                  _c("component-page-loading", {
                    attrs: { status: _vm.dataListLoading },
                    on: { reload: _vm.getDataList }
                  })
                ],
                1
              )
            : _vm.dialogVisible
            ? _c(
                "vue-simplebar",
                [
                  _c("el-alert", {
                    staticClass: "mb-2",
                    attrs: {
                      type: "warning",
                      title: _vm.$t("system.permission.orgs.warning")
                    }
                  }),
                  _vm._v(" "),
                  _c("el-tree", {
                    ref: "treeOrg",
                    attrs: {
                      data: _vm.dataList,
                      props: { children: "children", label: "name" },
                      "show-checkbox": "",
                      "check-strictly": "",
                      "node-key": "id",
                      "default-checked-keys":
                        _vm.orgs == null || _vm.orgs.ids == null
                          ? []
                          : _vm.orgs.ids
                    }
                  })
                ],
                1
              )
            : _vm._e(),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "dialog-footer",
              attrs: { slot: "footer" },
              slot: "footer"
            },
            [
              _c(
                "el-button",
                {
                  on: {
                    click: function($event) {
                      _vm.dialogVisible = false
                    }
                  }
                },
                [_vm._v(_vm._s(_vm.$t("action.cancel")))]
              ),
              _vm._v(" "),
              _c(
                "el-button",
                { attrs: { type: "primary" }, on: { click: _vm.onSubmit } },
                [_vm._v(_vm._s(_vm.$t("action.confirm")))]
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
    require("vue-hot-reload-api")      .rerender("data-v-57c1becb", module.exports)
  }
}

/***/ }),

/***/ 548:
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
            _vm._v(_vm._s(_vm.$t("system.permission.titleSide")))
          ]),
          _vm._v(" "),
          _vm.isSuperAdmin
            ? _c("div", { staticClass: "option" }, [
                _c(
                  "div",
                  {
                    staticClass: "icon",
                    attrs: {
                      title: _vm.$t("system.permission.tree.create-root")
                    },
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
            _vm._v(_vm._s(_vm.$t("system.permission.title")))
          ])
        ])
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "page-layout-body" }, [
        _c(
          "div",
          { staticClass: "body-aside" },
          [
            _c("permission-tree", {
              ref: "tree",
              on: { click: _vm.treeSelectNode }
            })
          ],
          1
        ),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "body-main" },
          [
            _c("vue-simplebar", [
              !_vm.tree.id
                ? _c(
                    "div",
                    { staticClass: "flex-center p-2" },
                    [
                      _c("component-page-loading", {
                        attrs: { status: "nodata" }
                      })
                    ],
                    1
                  )
                : _c("div", { staticClass: "container" }, [
                    _c(
                      "div",
                      { staticClass: "role-container" },
                      [
                        _c("div", { staticClass: "row role-item" }, [
                          _c("div", { staticClass: "col-2" }, [
                            _c("div", { staticClass: "item-title" }, [
                              _vm._v(_vm._s(_vm.$t("system.permission.name")))
                            ])
                          ]),
                          _vm._v(" "),
                          _c("div", { staticClass: "col-10" }, [
                            _c("div", { staticClass: "item-tree-name" }, [
                              _vm._v(_vm._s(_vm.tree.name))
                            ])
                          ])
                        ]),
                        _vm._v(" "),
                        _c("permission-user", { attrs: { tree: _vm.tree } }),
                        _vm._v(" "),
                        _c("permission-menu", { attrs: { tree: _vm.tree } }),
                        _vm._v(" "),
                        _c("permission-org", { attrs: { tree: _vm.tree } })
                      ],
                      1
                    )
                  ])
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
    require("vue-hot-reload-api")      .rerender("data-v-1451f9f9", module.exports)
  }
}

/***/ })

});