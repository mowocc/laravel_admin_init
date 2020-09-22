# 简介
 使用 `Laravel 7` 搭建的管理后台，前端使用 `Vue`/`Element UI`，包括用户管理、权限管理、菜单管理、及日志管理等基本功能。欢迎大家吐槽，功能还在不断完善中...

# 预览体验
>* 地址：http://49.234.18.219:9000/admin
>* 账号：admin@admin.com (超级管理员)
>* 密码：aaaaaaaa

# 搭建目的
管理后台是Web开发中常见场景，但搭建一个管理后台，却需要做着大量无法回避的基础功能，比如：项目的基本架构、异常处理及Api接口统一格式返回、无限级权限管理及回收、前端UI统一规范等。

本人在工作中经历了多个管理后台，每次开发新管理后台时，都会做大量重复工作，于是这个项目应运而生，专注实现一些基础的功能，开发新项目时只需关注具体业务逻辑，或少许修改就可以开始工作。项目定位为 `laravel-admin-init`，而不是 `laravel-admin-demo`。

# 功能介绍
>* 用户管理
>* 权限管理
>* 菜单管理
>* 日志管理
>* ......

详细功能逻辑后续更新...

# 使用技术
PHP扩展：
>* `laravel/sanctum` Api接口验证
>* `spatie/laravel-permission` 角色权限
>* `spatie/laravel-activitylog` 操作日志
>* `torann/geoip` IP定位
>* `jenssegers/agent` 设备信息
>* `caouecs/laravel-lang` 语言包

Vue插件：
>* `fortawesome` 字体图标
>* `element-ui` UI框架
>* `vuex` 全局状态
>* `vue-router` 前端路由
>* `vue-i18n` 多语言
>* `vue-cookie` Cookie管理
>* `vue-simplebar` 滚动条
>* `vue-progressbar` 进度条
>* `vue-json-views` Json显示
>* `moment-timezone` 时间处理


# 安装体验

部署代码
```shell
# 克隆代码到本地
git clone git@github.com:mowocc/laravel_admin_init.git

# hosts本地回环
127.0.0.1 admin.init.com

# 本地虚拟域名
<VirtualHost *:80>
    DocumentRoot "项目路径/laravel_admin_init/public"
    ServerName admin.init.com
</VirtualHost>
```

配置 `.env` 文件 (.env.example 复制到 .env)
```shell
# laravel/sanctum Api接口验证，Cookie作用域
SANCTUM_STATEFUL_DOMAINS=admin.init.com

# 手动创建数据
数据库名：init_admin
字符集：utf8mb4
排序规则：utf8mb4_unicode_ci

# 配置连接信息
MYSQL_ADMIN_HOST=127.0.0.1
MYSQL_ADMIN_PORT=3306
MYSQL_ADMIN_DATABASE=init_admin
MYSQL_ADMIN_USERNAME=root
MYSQL_ADMIN_PASSWORD=
```

数据库迁移和数据填充
```shell
# 数据库迁移
php artisan migrate

# 数据填充
php artisan db:seed
```

第一次登录管理后台
```shell
地址：http://admin.init.com/admin
账号：admin@admin.com (超级管理员)
密码：aaaaaaaa
```


注意
```shell
因为中控子节点后台，及对应数据库没有创建，所以【代理】模块无法访问。
```