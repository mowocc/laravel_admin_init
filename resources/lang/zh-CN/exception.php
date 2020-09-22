<?php
use App\Exceptions\Response\HttpException;
use App\Exceptions\Response\ServiceException;
use App\Exceptions\Response\SystemException;
use App\Exceptions\Response\ValidationException;

return [
    'system' => [
        SystemException::SYSTEM_ERROR => '系统错误'
    ],
    'service' => [
        ServiceException::SERVICE_ERROR => '业务逻辑错误',
        
        /* 其他错误 */
        ServiceException::SERVICE_PARAM_INVALID => '参数错误',
        ServiceException::SERVICE_PARAM_INVALID_PASSWORD => '密码错误',
        ServiceException::SERVICE_TASK_WAITING => '任务等待中',
        ServiceException::SERVICE_DATE_RANGE_ERROR => '时间范围错误',
        ServiceException::SERVICE_ELASTICSEARCH_MAX_RESULT_WINDOW => 'Elasticsearch最大返回数据条数限制错误',
        
        /* 不存在错误 */
        ServiceException::SERVICE_NOT_EXIST_DATA => '数据不存在',
        ServiceException::SERVICE_NOT_EXIST_USER => '用户不存在',
        ServiceException::SERVICE_NOT_EXIST_EMAIL => '邮箱不存在',
        ServiceException::SERVICE_NOT_EXIST_MEMBER => '代理人不存在',
        ServiceException::SERVICE_NOT_EXIST_ORG => '代理不存在',
        ServiceException::SERVICE_NOT_EXIST_RATE => '代理抽佣比例不存在',
        ServiceException::SERVICE_NOT_EXIST_ACCESS_KEY => '代理访问秘钥不存在',
        ServiceException::SERVICE_NOT_EXIST_GAME => '游戏不存在',
        ServiceException::SERVICE_NOT_EXIST_PLAYER => '玩家不存在',
        ServiceException::SERVICE_NOT_EXIST_TABLE => '数据库表不存在',
        ServiceException::SERVICE_NOT_EXIST_ELASTICSEARCH => 'Elasticsearch不存在',
        
        /* 存在错误 */
        ServiceException::SERVICE_EXISTED_DATA => '数据已存在',
        ServiceException::SERVICE_EXISTED_USER => '用户已存在',
        ServiceException::SERVICE_EXISTED_EMAIL => '邮箱已存在',
        ServiceException::SERVICE_EXISTED_MEMBER => '代理人已存在',
        ServiceException::SERVICE_EXISTED_MEMBER_RELATION_USER => '账号已被代理人关联',
        ServiceException::SERVICE_EXISTED_ORG_CHILDREN => '组织存在子节点',
        
        /* 操作错误 */
        ServiceException::SERVICE_ACTION_SAVE_ERROR => '保存失败',
        ServiceException::SERVICE_ACTION_DISABLE => '禁止操作',
        
        /* 禁用错误 */
        ServiceException::SERVICE_DISABLE_DATA => '数据被禁用',
        ServiceException::SERVICE_DISABLE_USER => '用户被禁用',
        ServiceException::SERVICE_DISABLE_MEMBER => '代理人被禁用',
        ServiceException::SERVICE_DISABLE_ACCESS_KEY => '代理访问秘钥被禁用',
        
        /* 权限错误 */
        ServiceException::SERVICE_NOT_AUTH_DATA => '数据未授权',
        ServiceException::SERVICE_NOT_AUTH_USER => '用户未授权',
        
        /* 超级管理员 */
        ServiceException::SERVICE_SUPER_ADMIN => '超级管理员',
        ServiceException::SERVICE_SUPER_ADMIN_AUTH => '没有权限管理该账号',
        
        /* 金币 */
        ServiceException::SERVICE_COIN => '金币',
        ServiceException::SERVICE_COIN_EMPTY => '金币数量为空',
        ServiceException::SERVICE_COIN_LACK => '金币数量不足',
        
        /* 游戏服务错误 */
        ServiceException::SERVICE_GAME_SERVICE => '游戏服务错误',
        ServiceException::SERVICE_GAME_SERVICE_NOT_EXIST_TOKEN => '游戏服务 token 不存在',
        ServiceException::SERVICE_GAME_SERVICE_SYSTEM_ERROR => '游戏服务内部错误',
        ServiceException::SERVICE_GAME_SERVICE_REQUEST_TIMEOUT => '游戏服务请求超时',
        
        /* Api服务错误 */
        ServiceException::SERVICE_API_SERVICE => 'Api服务错误',
        ServiceException::SERVICE_API_SERVICE_REQUEST_TIMEOUT => 'Api服务请求超时'
    ],
    'validation' => [
        ValidationException::VALIDATION_ERROR => '表单验证错误'
    ],
    'http' => [
        HttpException::HTTP_BAD_REQUEST => '错误请求',
        HttpException::HTTP_UNAUTHORIZED => '未授权',
        HttpException::HTTP_PAYMENT_REQUIRED => '需要付款',
        HttpException::HTTP_FORBIDDEN => '禁止',
        HttpException::HTTP_NOT_FOUND => '未找到',
        HttpException::HTTP_METHOD_NOT_ALLOWED => '方法禁用',
        HttpException::HTTP_NOT_ACCEPTABLE => '不接受',
        HttpException::HTTP_PROXY_AUTHENTICATION_REQUIRED => '需要代理授权',
        HttpException::HTTP_REQUEST_TIMEOUT => '请求超时',
        HttpException::HTTP_CONFLICT => '冲突',
        HttpException::HTTP_GONE => '已删除',
        HttpException::HTTP_LENGTH_REQUIRED => '需要有效长度',
        HttpException::HTTP_PRECONDITION_FAILED => '未满足前提条件',
        HttpException::HTTP_REQUEST_ENTITY_TOO_LARGE => '请求实体过大',
        HttpException::HTTP_REQUEST_URI_TOO_LONG => '请求的 URI 过长',
        HttpException::HTTP_UNSUPPORTED_MEDIA_TYPE => '不支持的媒体类型',
        HttpException::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE => '请求范围不符合要求',
        HttpException::HTTP_EXPECTATION_FAILED => '未满足期望值',
        HttpException::HTTP_I_AM_A_TEAPOT => '拒绝冲泡咖啡，我是茶壶',
        HttpException::HTTP_MISDIRECTED_REQUEST => '连接数过多',
        HttpException::HTTP_UNPROCESSABLE_ENTITY => '指令无法处理',
        HttpException::HTTP_LOCKED => '当前资源被锁定',
        HttpException::HTTP_FAILED_DEPENDENCY => '之前请求错误导致当前请求失败',
        HttpException::HTTP_TOO_EARLY => '可能重放攻击',
        HttpException::HTTP_UPGRADE_REQUIRED => '需要升级协议',
        HttpException::HTTP_PRECONDITION_REQUIRED => '要求先决条件',
        HttpException::HTTP_TOO_MANY_REQUESTS => '太多请求',
        HttpException::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE => '请求头字段太大',
        HttpException::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS => '因法律原因不可用',
        HttpException::HTTP_INTERNAL_SERVER_ERROR => '服务器内部错误',
        HttpException::HTTP_NOT_IMPLEMENTED => '尚未实施',
        HttpException::HTTP_BAD_GATEWAY => '错误网关',
        HttpException::HTTP_SERVICE_UNAVAILABLE => '服务不可用',
        HttpException::HTTP_GATEWAY_TIMEOUT => '网关超时',
        HttpException::HTTP_VERSION_NOT_SUPPORTED => 'HTTP 版本不受支持',
        HttpException::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL => '服务器配置错误',
        HttpException::HTTP_INSUFFICIENT_STORAGE => '存储空间不足',
        HttpException::HTTP_LOOP_DETECTED => '存在无限循环',
        HttpException::HTTP_NOT_EXTENDED => '不支持的扩展',
        HttpException::HTTP_NETWORK_AUTHENTICATION_REQUIRED => '要求网络认证'
    ]
];
