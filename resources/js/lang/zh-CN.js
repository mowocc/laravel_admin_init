export default {
    "sitename": "中控管理后台",

    // 顶部菜单
    "topMenu": {
        "language": "Switch To English",
        "setting": "设置",
        "logout": "退出",
    },

    // 服务器
    "server": {
        "server-1": "服务器分区一",
        "server-2": "服务器分区二",
        "server-3": "服务器分区三",
    },

    // 目录菜单
    "menu": {
        "index": "首页",
        "chart": "统计",
        "agency": {
            "index": "代理",
            "setting": "代理管理",
            "contacts": "成员管理",
        },
        "game": "游戏",
        "order": {
            "index": "订单",
            "game": "游戏订单",
            "wallet": "钱包订单",
        },
        "system": {
            "index": "系统",
            "setting": {
                "index": "系统配置",
                "menu": "目录菜单",
                "server": "分区配置",
                "language": "语言管理",
            },
            "user": "后台用户",
            "permission": "权限设置",
            "log": {
                "index": "活动日志",
                "activity": "操作日志",
                "login": "登录日志",
            },
            "task": {
                "index": "统计任务",
                "stats": "游戏统计",
                "retention": "玩家留存",
            },
            "testing": "游戏测试",
        },
    },
    // 页面功能
    "actions": {
        "system": {
            "show": "详情",
            "store": "新增",
            "update": "编辑",
            "status": "禁用/启用",
            "destroy": "删除",
            "export": "导出",
        },
        "custom": {

        },
    },

    // 表单
    "form": {
        "deletedList": {
            0: {
                "label": "已启用",
                "value": 0
            },
            1: {
                "label": "已禁用",
                "value": 1
            },
        },
        "booleanList": {
            0: {
                "label": "否",
                "value": 0
            },
            1: {
                "label": "是",
                "value": 1
            },
        },
    },

    // 动作
    "action": {
        "add": "添加",
        "new": "新增",
        "create": "创建",
        "update": "更新",
        "delete": "删除",
        "edit": "编辑",
        "save": "保存",
        "modify": "修改",
        "restart": "重启",
        "more": "更多",
        "search": "查询",
        "refresh": "刷新",
        "export": "导出",
        "switch": "切换",
        "details": "详情",

        "on": "启用",
        "off": "禁用",

        "confirm": "确定",
        "cancel": "取消",
        "clear": "清空",

        "check": "查看",
        "setting": "设置",
        "custom": "自定义",
    },

    // 确认提示
    "confirm": {
        "delete": "删除后将无法恢复！",
        "language": "确定 <strong class=\"text-danger-custom\">{status}</strong> 该语言吗？",
        "server": "确定 <strong class=\"text-danger-custom\">{status}</strong> 该分区配置吗？",
        "menu": "确定 <strong class=\"text-danger-custom\">{status}</strong> 该目录菜单吗？",
        "user": "确定 <strong class=\"text-danger-custom\">{status}</strong> 该用户吗？",
        "tree-delete": "确定 <strong class=\"text-danger-custom\">删除 {name}</strong> 节点吗？",
        "tree-delete-org": "将同时删除节点内所有成员账号。",
        "tree-children": "将同时 {status} 子孙节点。",
        "tree-parent": "将同时 {status} 父级节点。",
    },

    // 消息提示
    "messages": {
        "empty": "暂无数据",

        "loading": "数据加载中...",
        "loading-failed": "加载失败，",
        "loading-refresh": "点击刷新",

        "succeeded": "{status}成功",
        "failed": "{status}失败",

        "login-failed": "登录失败，请刷新页面重试",
        "page-expired": "页面过期，点击确定刷新页面",

        "export-failed": "导出失败，请重试",
        "export-failed-exceed": "单次导出数量不能超过10万条",
        "export-failed-exceed-es": "单次导出数量不能超过100万条",

        "delete-tree-failed": "存在子节点，请先删除所有子节点。",
    },

    // 日期范围
    "datePickerOptions": {
        "today": "今天",
        "yesterday": "昨天",
        "lastWeek": "最近7天",
        "lastMonth": "最近1个月",
        "lastThreeMonths": "最近3个月",
        "lastSixMonths": "最近6个月",
        "last30Days": "最近30天",
        "warning": "注意：查询7天前的数据，时间范围只能在同一天内。",
        "warningRangeDay": "最多查询%{number}天的数据",
    },

    // error
    "error": {
        "401": "页面未授权，或授权已过期",
        "403": "没有权限",
        "404": "页面不存在",
    },

    // 代理
    "agency": {
        "setting": {
            "titleSide": '代理平台',
            "title": '平台设置',
            "tree": {
                "create-root": "创建代理",
                "create": "创建子级",
                "dialog": {
                    "create": "新增节点",
                    "update": "编辑节点",
                    "destroy": "删除节点",
                    "parent": "父级",
                    "name": "名称",
                    "sort": "排序",
                    "sortPlaceholder": "数字越小排序越靠前",
                },
            },
            "info": {
                "title": "基本信息",
            },
            "other": {
                "title": "各种配置",
            },
        },
        "contacts": {
            "titleSide": '代理平台',
            "title": '成员管理',
            "user": {
                "org": "代理",
                "name": "姓名",
                "email": "邮箱",
                "roles": "角色",
                "deleted": "状态",
                "created": "创建时间",
                "updated": "更新时间",
                "operation": "操作",
                "drawer": {
                    "details": "用户详情",
                },
                "dialog": {
                    "deleted": "用户状态",
                    "create": "新增用户",
                    "update": "编辑用户",
                    "destroy": "删除用户",
                    "password": "密码",
                    "passwordPlaceholder": {
                        "create": "初始密码",
                        "update": "重置密码",
                    },
                },
            },
        },
    },

    // 系统
    "system": {
        "setting": {
            "language": {
                "name": "语言",
                "lang": "语言Code",
                "deleted": "状态",
                "created": "创建时间",
                "updated": "更新时间",
                "operation": "操作",
                "dialog": {
                    "deleted": "语言状态",
                    "create": "新增语言",
                    "update": "编辑语言",
                    "destroy": "删除语言",
                },
            },
            "server": {
                "name": "名称",
                "mysql_agency": {
                    "title": "[mysql_agency]连接配置",
                    "host": "主机",
                    "port": "端口",
                    "database": "数据库",
                    "username": "用户名",
                    "password": "密码",
                },
                "deleted": "状态",
                "created": "创建时间",
                "updated": "更新时间",
                "operation": "操作",
                "drawer": {
                    "details": "分区配置详情",
                },
                "dialog": {
                    "deleted": "分区配置状态",
                    "create": "新增分区配置",
                    "update": "编辑分区配置",
                    "destroy": "删除分区配置",
                },
            },
            "menu": {
                "sort": "排序",
                "parent": "父级",
                "name": "名称",
                "icon": "Font 图标",
                "route_path": "前端路由",
                "route_name": "路由名称",
                "deleted": "状态",
                "created": "创建时间",
                "updated": "更新时间",
                "operation": "操作",
                "drawer": {
                    "details": "目录菜单详情",
                    "actions": {
                        "title": "功能",
                        "system": "系统",
                        "custom": "自定义",
                        "name": "名称",
                        "action": "Action",
                    },
                },
                "dialog": {
                    "deleted": "目录菜单状态",
                    "create": "新增目录菜单",
                    "update": "编辑目录菜单",
                    "destroy": "删除目录菜单",
                    "sortPlaceholder": "数字越小排序越靠前",
                },
            },
        },
        "user": {
            "name": "姓名",
            "email": "邮箱",
            "roles": "角色",
            "deleted": "状态",
            "created": "创建时间",
            "updated": "更新时间",
            "operation": "操作",
            "drawer": {
                "details": "用户详情",
            },
            "dialog": {
                "deleted": "用户状态",
                "create": "新增用户",
                "update": "编辑用户",
                "destroy": "删除用户",
                "password": "密码",
                "passwordPlaceholder": {
                    "create": "初始密码",
                    "update": "重置密码",
                },
            },
        },
        "permission": {
            "titleSide": '分级授权',
            "title": '权限设置',
            "tree": {
                "create-root": "创建新分组",
                "create": "创建子级",
                "dialog": {
                    "create": "新增节点",
                    "update": "编辑节点",
                    "destroy": "删除节点",
                    "parent": "父级",
                    "name": "名称",
                    "sort": "排序",
                    "sortPlaceholder": "数字越小排序越靠前",
                },
            },
            "name": "授权名称",
            "users": {
                "title": "授权用户",
                "dialog": {
                    "name": "姓名",
                    "email": "邮箱",
                },
            },
            "menus": {
                "title": "授权页面",
            },
        },
        "log": {
            "activity": {
                "log_name": "名称",
                "description": "事件",
                "subject": "对象",
                "causer": "操作人",
                "changes": "变化",
                "created": "时间",
                "operation": "操作",
                "drawer": {
                    "details": "操作详情",
                    "causer": {
                        "name": "姓名",
                        "email": "邮箱",
                    },
                },
            },
            "login": {
                "user": "用户",
                "languages": "语言",
                "device": "设备",
                "platform": "平台",
                "browser": "浏览器",
                "robot": "机器人",
                "ip": "IP地址",
                "iso_code": "国家代码",
                "country": "国家",
                "city": "城市",
                "state": "省份代码",
                "state_name": "省份",
                "postal_code": "邮政编码",
                "location": "定位",
                "lat": "纬度",
                "lon": "经度",
                "timezone": "时区",
                "continent": "洲名",
                "currency": "货币",
                "default": "默认值",
                "created": "时间",
                "operation": "操作",
                "drawer": {
                    "details": "登录详情",
                    "user": {
                        "name": "姓名",
                        "email": "邮箱",
                    },
                },
            },
        },
    },

    // 个人中心
    "home": {
        "user-info": {
            "title": "个人资料",
            "name": "姓名",
            "email": "邮箱",
        },
        "update-password": {
            "title": "修改密码",
            "password_current": "当前密码",
            "password": "新密码",
            "password_confirmation": "确认新密码",
        },
    },
}
