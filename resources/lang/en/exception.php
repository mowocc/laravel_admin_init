<?php
use App\Exceptions\Response\HttpException;
use App\Exceptions\Response\ServiceException;
use App\Exceptions\Response\SystemException;
use App\Exceptions\Response\ValidationException;

return [
    'system' => [
        SystemException::SYSTEM_ERROR => 'System error'
    ],
    'service' => [
        ServiceException::SERVICE_ERROR => 'Service error',
        
        /* 其他错误 */
        ServiceException::SERVICE_PARAM_INVALID => 'Parameter error',
        ServiceException::SERVICE_PARAM_INVALID_PASSWORD => 'Wrong password',
        ServiceException::SERVICE_TASK_WAITING => 'Task waiting',
        ServiceException::SERVICE_DATE_RANGE_ERROR => 'Wrong time range',
        ServiceException::SERVICE_ELASTICSEARCH_MAX_RESULT_WINDOW => 'Elasticsearch maximum return data limit error',
        
        /* 不存在错误 */
        ServiceException::SERVICE_NOT_EXIST_DATA => 'Data does not exist',
        ServiceException::SERVICE_NOT_EXIST_USER => 'User does not exist',
        ServiceException::SERVICE_NOT_EXIST_EMAIL => 'Mailbox does not exist',
        ServiceException::SERVICE_NOT_EXIST_MEMBER => 'Agent does not exist',
        ServiceException::SERVICE_NOT_EXIST_ORG => 'Agency does not exist',
        ServiceException::SERVICE_NOT_EXIST_RATE => 'The agent\'s commission rate does not exist',
        ServiceException::SERVICE_NOT_EXIST_ACCESS_KEY => 'Agent access key does not exist',
        ServiceException::SERVICE_NOT_EXIST_GAME => 'Game does not exist',
        ServiceException::SERVICE_NOT_EXIST_PLAYER => 'Player does not exist',
        ServiceException::SERVICE_NOT_EXIST_TABLE => 'Database table does not exist',
        ServiceException::SERVICE_NOT_EXIST_ELASTICSEARCH => 'Elasticsearch does not exist',
        
        /* 存在错误 */
        ServiceException::SERVICE_EXISTED_DATA => 'Data already exists',
        ServiceException::SERVICE_EXISTED_USER => 'User already exists',
        ServiceException::SERVICE_EXISTED_EMAIL => 'Mailbox already exists',
        ServiceException::SERVICE_EXISTED_MEMBER => 'Agent already exists',
        ServiceException::SERVICE_EXISTED_MEMBER_RELATION_USER => 'Account has been linked by an agent',
        ServiceException::SERVICE_EXISTED_ORG_CHILDREN => 'Organization has child nodes',
        
        /* 操作错误 */
        ServiceException::SERVICE_ACTION_SAVE_ERROR => 'Save failed',
        ServiceException::SERVICE_ACTION_DISABLE => 'Operation prohibited',
        
        /* 禁用错误 */
        ServiceException::SERVICE_DISABLE_DATA => 'Data is disabled',
        ServiceException::SERVICE_DISABLE_USER => 'User is disabled',
        ServiceException::SERVICE_DISABLE_MEMBER => 'Agent is disabled',
        ServiceException::SERVICE_DISABLE_ACCESS_KEY => 'Agent access key is disabled',
        
        /* 权限错误 */
        ServiceException::SERVICE_NOT_AUTH_DATA => 'Unauthorized data',
        ServiceException::SERVICE_NOT_AUTH_USER => 'User is not authorized',
        
        /* 超级管理员 */
        ServiceException::SERVICE_SUPER_ADMIN => 'Super Administrator',
        ServiceException::SERVICE_SUPER_ADMIN_AUTH => 'No permission to manage this account',
        
        /* 金币 */
        ServiceException::SERVICE_COIN => 'Gold',
        ServiceException::SERVICE_COIN_EMPTY => 'The number of gold coins is empty',
        ServiceException::SERVICE_COIN_LACK => 'Not enough gold coins',
        
        /* 游戏服务错误 */
        ServiceException::SERVICE_GAME_SERVICE => 'Game service error',
        ServiceException::SERVICE_GAME_SERVICE_NOT_EXIST_TOKEN => 'Game service token does not exist',
        ServiceException::SERVICE_GAME_SERVICE_SYSTEM_ERROR => 'Game service internal error',
        ServiceException::SERVICE_GAME_SERVICE_REQUEST_TIMEOUT => 'Game service request timeout',
        
        /* Api服务错误 */
        ServiceException::SERVICE_API_SERVICE => 'Api service error',
        ServiceException::SERVICE_API_SERVICE_REQUEST_TIMEOUT => 'Api service request timed out'
    ],
    'validation' => [
        ValidationException::VALIDATION_ERROR => 'Validation error'
    ],
    'http' => [
        HttpException::HTTP_BAD_REQUEST => 'Bad Request',
        HttpException::HTTP_UNAUTHORIZED => 'Unauthorized',
        HttpException::HTTP_PAYMENT_REQUIRED => 'Payment Required',
        HttpException::HTTP_FORBIDDEN => 'Forbidden',
        HttpException::HTTP_NOT_FOUND => 'Not Found',
        HttpException::HTTP_METHOD_NOT_ALLOWED => 'Method Not Allowed',
        HttpException::HTTP_NOT_ACCEPTABLE => 'Not Acceptable',
        HttpException::HTTP_PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
        HttpException::HTTP_REQUEST_TIMEOUT => 'Request Timeout',
        HttpException::HTTP_CONFLICT => 'Conflict',
        HttpException::HTTP_GONE => 'Gone',
        HttpException::HTTP_LENGTH_REQUIRED => 'Length Required',
        HttpException::HTTP_PRECONDITION_FAILED => 'Precondition Failed',
        HttpException::HTTP_REQUEST_ENTITY_TOO_LARGE => 'Payload Too Large',
        HttpException::HTTP_REQUEST_URI_TOO_LONG => 'URI Too Long',
        HttpException::HTTP_UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
        HttpException::HTTP_REQUESTED_RANGE_NOT_SATISFIABLE => 'Range Not Satisfiable',
        HttpException::HTTP_EXPECTATION_FAILED => 'Expectation Failed',
        HttpException::HTTP_I_AM_A_TEAPOT => 'I\'m a teapot',
        HttpException::HTTP_MISDIRECTED_REQUEST => 'Misdirected Request',
        HttpException::HTTP_UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
        HttpException::HTTP_LOCKED => 'Locked',
        HttpException::HTTP_FAILED_DEPENDENCY => 'Failed Dependency',
        HttpException::HTTP_TOO_EARLY => 'Too Early',
        HttpException::HTTP_UPGRADE_REQUIRED => 'Upgrade Required',
        HttpException::HTTP_PRECONDITION_REQUIRED => 'Precondition Required',
        HttpException::HTTP_TOO_MANY_REQUESTS => 'Too Many Requests',
        HttpException::HTTP_REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
        HttpException::HTTP_UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable For Legal Reasons',
        HttpException::HTTP_INTERNAL_SERVER_ERROR => 'Internal Server Error',
        HttpException::HTTP_NOT_IMPLEMENTED => 'Not Implemented',
        HttpException::HTTP_BAD_GATEWAY => 'Bad Gateway',
        HttpException::HTTP_SERVICE_UNAVAILABLE => 'Service Unavailable',
        HttpException::HTTP_GATEWAY_TIMEOUT => 'Gateway Timeout',
        HttpException::HTTP_VERSION_NOT_SUPPORTED => 'HTTP Version Not Supported',
        HttpException::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL => 'Variant Also Negotiates',
        HttpException::HTTP_INSUFFICIENT_STORAGE => 'Insufficient Storage',
        HttpException::HTTP_LOOP_DETECTED => 'Loop Detected',
        HttpException::HTTP_NOT_EXTENDED => 'Not Extended',
        HttpException::HTTP_NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required'
    ]
];
