export default {
    "sitename": "Central Control CMS",

    // 顶部菜单
    "topMenu": {
        "language": "切换至中文",
        "setting": "Setting",
        "logout": "Exit",
    },

    // 服务器
    "server": {
        "server-1": "Server Partition One",
        "server-2": "Server Partition Two",
        "server-3": "Server Partition Three",
    },

    // 目录菜单
    "menu": {
        "index": "Home",
        "chart": "Statistic",
        "agency": {
            "index": "Agency",
            "setting": "Agency Management",
            "contacts": "Member Management",
        },
        "game": "Game",
        "order": {
            "index": "Order",
            "game": "Game Order",
            "wallet": "Wallet Order",
        },
        "system": {
            "index": "System",
            "setting": {
                "index": "System Configuration",
                "menu": "Directory Menu",
                "server": "Partition Configuration",
                "language": "Language Management",
            },
            "user": "Backend User",
            "permission": "Permission Setting",
            "log": {
                "index": "Activity Log",
                "activity": "Operation Log",
                "login": "Login log",
            },
            "task": {
                "index": "Statistical Tasks",
                "stats": "Game Statistics",
                "retention": "Player Retention",
            },
            "testing": "Game Test",
        },
    },
    // 页面功能
    "actions": {
        "system": {
            "show": "Details",
            "store": "New",
            "update": "Edit",
            "status": "Disable/Enable",
            "destroy": "Delete",
            "export": "Export",
        },
        "custom": {

        },
    },

    // 表单
    "form": {
        "deletedList": {
            0: {
                "label": "Enabled",
                "value": 0
            },
            1: {
                "label": "Disabled",
                "value": 1
            },
        },
        "booleanList": {
            0: {
                "label": "No",
                "value": 0
            },
            1: {
                "label": "Yes",
                "value": 1
            },
        },
    },

    // 动作
    "action": {
        "add": "Add",
        "new": "New",
        "create": "Create",
        "update": "Update",
        "delete": "Delete",
        "edit": "Edit",
        "save": "Save",
        "modify": "Modify",
        "restart": "Restart",
        "more": "More",
        "search": "Search",
        "refresh": "Refresh",
        "export": "Export",
        "switch": "Switch",
        "details": "Details",

        "on": "Enable",
        "off": "Disable",

        "confirm": "Confirm",
        "cancel": "Cancel",
        "clear": "Clear",

        "check": "Check",
        "setting": "Setting",
        "custom": "Custom",
    },

    // 确认提示
    "confirm": {
        "delete": "It cannot be recovered after deletion!",
        "language": "Are you sure <strong class=\"text-danger-custom\">{status}</strong> the language?",
        "server": "Are you sure <strong class=\"text-danger-custom\">{status}</strong> the partition configuration?",
        "menu": "Are you sure <strong class=\"text-danger-custom\">{status}</strong> the directory menu?",
        "user": "Are you sure <strong class=\"text-danger-custom\">{status}</strong> the user?",
        "tree-delete": "Are you sure <strong class=\"text-danger-custom\">Delete {name}</strong> node?",
        "tree-delete-org": "All member accounts in the node will be deleted at the same time.",
        "tree-children": "Will be {status} descendants at the same time.",
        "tree-parent": "Will be {status} as the parent node at the same time.",
    },

    // 消息提示
    "messages": {
        "empty": "No data",

        "loading": "Loading...",
        "loading-failed": "Loading failed, ",
        "loading-refresh": "click refresh",

        "succeeded": "{status} succeeded",
        "failed": "{status} failed",

        "login-failed": "Login failed, please refresh the page",
        "page-expired": "The page expires, click confirm to refresh the page",

        "export-failed": "Export failed, please try again",
        "export-failed-exceed": "The number of single exports should not exceed 100,000",
        "export-failed-exceed-es": "The number of single exports should not exceed 1,000,000",

        "delete-tree-failed": "There are child nodes, please delete all child nodes first.",
    },

    // 日期范围
    "datePickerOptions": {
        "today": "Today",
        "yesterday": "Yesterday",
        "lastWeek": "Last week",
        "lastMonth": "Last month",
        "lastThreeMonths": "Last three months",
        "lastSixMonths": "Last six months",
        "last30Days": "Last 30 days",
        "warning": "Note: query the data 7 days ago, the time range can only be within the same day.",
        "warningRangeDay": "Query up to %{number} days of data",
    },

    // error
    "error": {
        "401": "Unauthorized",
        "403": "Forbidden",
        "404": "Page Not Found",
    },

    // 代理
    "agency": {
        "setting": {
            "titleSide": 'Agency Platform',
            "title": 'Platform Settings',
            "tree": {
                "create-root": "Create Agency",
                "create": "Create Child",
                "dialog": {
                    "create": "New Node",
                    "update": "Edit Node",
                    "destroy": "Delete Node",
                    "parent": "Parent",
                    "name": "Name",
                    "sort": "Sort",
                    "sortPlaceholder": "The lower the number, the higher the ranking",
                },
            },
            "info": {
                "title": "Basic Information",
            },
            "other": {
                "title": "Various Configurations",
            },
        },
        "contacts": {
            "titleSide": 'Agency Platform',
            "title": 'Member Management',
            "user": {
                "org": "Agency",
                "name": "Name",
                "email": "Email",
                "roles": "Roles",
                "deleted": "Status",
                "created": "Create Time",
                "updated": "Update Time",
                "operation": "Operation",
                "drawer": {
                    "details": "User Details",
                },
                "dialog": {
                    "deleted": "User Status",
                    "create": "New User",
                    "update": "Edit User",
                    "destroy": "Delete User",
                    "password": "Password",
                    "passwordPlaceholder": {
                        "create": "Initial Password",
                        "update": "Reset Password",
                    },
                },
            },
        },
    },

    // 系统
    "system": {
        "setting": {
            "language": {
                "name": "Language",
                "lang": "Lang Code",
                "deleted": "Status",
                "created": "Create Time",
                "updated": "Update Time",
                "operation": "Operation",
                "dialog": {
                    "deleted": "Language Status",
                    "create": "New Language",
                    "update": "Edit Language",
                    "destroy": "Delete Language",
                },
            },
            "server": {
                "name": "Name",
                "mysql_agency": {
                    "title": "[mysql_agency] Connection configuration",
                    "host": "Host",
                    "port": "Port",
                    "database": "Database",
                    "username": "Username",
                    "password": "Password",
                },
                "deleted": "Status",
                "created": "Create Time",
                "updated": "Update Time",
                "operation": "Operation",
                "drawer": {
                    "details": "Partition Configuration Details",
                },
                "dialog": {
                    "deleted": "Partition Configuration Status",
                    "create": "New Partition Configuration",
                    "update": "Edit Partition Configuration",
                    "destroy": "Delete Partition Configuration",
                },
            },
            "menu": {
                "sort": "Sort",
                "parent": "Parent",
                "name": "Name",
                "icon": "Font Icon",
                "route_path": "Route Path",
                "route_name": "Route Name",
                "deleted": "Status",
                "created": "Create Time",
                "updated": "Update Time",
                "operation": "Operation",
                "drawer": {
                    "details": "Directory Menu Details",
                    "actions": {
                        "title": "Functions",
                        "system": "System",
                        "custom": "Custom",
                        "name": "Name",
                        "action": "Action",
                    },
                },
                "dialog": {
                    "deleted": "Directory Menu Status",
                    "create": "New Directory Menu",
                    "update": "Edit Directory Menu",
                    "destroy": "Delete Directory Menu",
                    "sortPlaceholder": "The lower the number, the higher the ranking",
                },
            },
        },
        "user": {
            "name": "Name",
            "email": "Email",
            "roles": "Roles",
            "deleted": "Status",
            "created": "Create Time",
            "updated": "Update Time",
            "operation": "Operation",
            "drawer": {
                "details": "User Details",
            },
            "dialog": {
                "deleted": "User Status",
                "create": "New User",
                "update": "Edit User",
                "destroy": "Delete User",
                "password": "Password",
                "passwordPlaceholder": {
                    "create": "Initial Password",
                    "update": "Reset Password",
                },
            },
        },
        "permission": {
            "titleSide": 'Graded Authorization',
            "title": 'Permission Settings',
            "tree": {
                "create-root": "Create New Group",
                "create": "Create Child",
                "dialog": {
                    "create": "New Node",
                    "update": "Edit Node",
                    "destroy": "Delete Node",
                    "parent": "Parent",
                    "name": "Name",
                    "sort": "Sort",
                    "sortPlaceholder": "The lower the number, the higher the ranking",
                },
            },
            "name": "Authorized Name",
            "users": {
                "title": "Authorized Users",
                "dialog": {
                    "name": "Name",
                    "email": "Email",
                },
            },
            "menus": {
                "title": "Authorized Pages",
            },
        },
        "log": {
            "activity": {
                "log_name": "Name",
                "description": "Event",
                "subject": "Subject",
                "causer": "Causer",
                "changes": "Changes",
                "created": "Time",
                "operation": "Operation",
                "drawer": {
                    "details": "Operation Details",
                    "causer": {
                        "name": "Name",
                        "email": "Email",
                    },
                },
            },
            "login": {
                "user": "User",
                "languages": "Languages",
                "device": "Device",
                "platform": "Platform",
                "browser": "Browser",
                "robot": "Robot",
                "ip": "IP Address",
                "iso_code": "ISO Code",
                "country": "Country",
                "city": "City",
                "state": "State",
                "state_name": "State Name",
                "postal_code": "Postal Code",
                "location": "Location",
                "lat": "Latitude",
                "lon": "Longitude",
                "timezone": "Timezone",
                "continent": "Continent",
                "currency": "Currency",
                "default": "Default",
                "created": "Time",
                "operation": "Operation",
                "drawer": {
                    "details": "Login Details",
                    "user": {
                        "name": "Name",
                        "email": "Email",
                    },
                },
            },
        },
    },

    // 个人中心
    "home": {
        "user-info": {
            "title": "Personal Information",
            "name": "Name",
            "email": "Email",
        },
        "update-password": {
            "title": "Change Password",
            "password_current": "Current Password",
            "password": "New Password",
            "password_confirmation": "Confirm New Password",
        },
    },
}
