<?php
namespace App\Exceptions\Response;

use Exception;
use App\Core\Response\ResponseError;

/**
 * 自定义 Service 异常，及统一封装处理
 */
class ServiceException extends Exception
{

    /**
     * 业务逻辑错误
     */
    const SERVICE_ERROR = 44000;
    
    /* 其他错误 */
    /**
     * （参数错误）其他错误
     */
    const SERVICE_PARAM_INVALID = 44001;
    
    /**
     * （密码错误）其他错误
     */
    const SERVICE_PARAM_INVALID_PASSWORD = 44002;
    
    /**
     * （任务等待中）其他错误
     */
    const SERVICE_TASK_WAITING = 44003;
    
    /**
     * （时间范围错误）其他错误
     */
    const SERVICE_DATE_RANGE_ERROR = 44004;
    
    /**
     * （Elasticsearch最大返回数据条数限制）其他错误
     */
    const SERVICE_ELASTICSEARCH_MAX_RESULT_WINDOW = 44005;
    
    /* 不存在错误 */
    /**
     * （数据不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_DATA = 44100;
    
    /**
     * （用户不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_USER = 44101;
    
    /**
     * （邮箱不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_EMAIL = 44102;
    
    /**
     * （代理人不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_MEMBER = 44111;
    
    /**
     * （代理不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_ORG = 44112;
    
    /**
     * （代理抽佣比例不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_RATE = 44113;
    
    /**
     * （代理访问秘钥不存在）
     */
    const SERVICE_NOT_EXIST_ACCESS_KEY = 44114;
    
    /**
     * （游戏不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_GAME = 44121;
    
    /**
     * （玩家不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_PLAYER = 44122;
    
    /**
     * （数据库表不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_TABLE = 44131;
    
    /**
     * （Elasticsearch不存在）不存在错误
     */
    const SERVICE_NOT_EXIST_ELASTICSEARCH = 44132;
    
    /* 存在错误 */
    /**
     * （数据已存在）存在错误
     */
    const SERVICE_EXISTED_DATA = 44200;
    
    /**
     * （用户已存在）存在错误
     */
    const SERVICE_EXISTED_USER = 44201;
    
    /**
     * （邮箱已存在）存在错误
     */
    const SERVICE_EXISTED_EMAIL = 44202;
    
    /**
     * （代理人已存在）存在错误
     */
    const SERVICE_EXISTED_MEMBER = 44211;
    
    /**
     * （账号已被代理人关联）存在错误
     */
    const SERVICE_EXISTED_MEMBER_RELATION_USER = 44212;
    
    /**
     * （组织存在子节点）存在错误
     */
    const SERVICE_EXISTED_ORG_CHILDREN = 44221;
    
    /* 操作错误 */
    /**
     * （保存失败）操作错误
     */
    const SERVICE_ACTION_SAVE_ERROR = 44300;
    
    /**
     * （禁止操作）操作错误
     */
    const SERVICE_ACTION_DISABLE = 44301;
    
    /* 禁用错误 */
    /**
     * （数据被禁用）禁用错误
     */
    const SERVICE_DISABLE_DATA = 44400;
    
    /**
     * （用户被禁用）禁用错误
     */
    const SERVICE_DISABLE_USER = 44401;
    
    /**
     * （代理人被禁用）禁用错误
     */
    const SERVICE_DISABLE_MEMBER = 44411;
    
    /**
     * （代理访问秘钥被禁用）
     */
    const SERVICE_DISABLE_ACCESS_KEY = 44412;
    
    /* 权限错误 */
    /**
     * （数据未授权）权限错误
     */
    const SERVICE_NOT_AUTH_DATA = 44500;
    
    /**
     * （用户未授权）权限错误
     */
    const SERVICE_NOT_AUTH_USER = 44501;
    
    /* 超级管理员 */
    /**
     * （超级管理员）超级管理员
     */
    const SERVICE_SUPER_ADMIN = 44700;
    
    /**
     * （没有权限管理该账号）超级管理员
     */
    const SERVICE_SUPER_ADMIN_AUTH = 44701;
    
    /* 金币 */
    /**
     * （金币）金币
     */
    const SERVICE_COIN = 44800;
    
    /**
     * （金币数量为空）金币
     */
    const SERVICE_COIN_EMPTY = 44801;
    
    /**
     * （金币数量不足）金币
     */
    const SERVICE_COIN_LACK = 44802;
    
    /* 游戏服务错误 */
    /**
     * 游戏服务错误
     */
    const SERVICE_GAME_SERVICE = 44900;
    
    /**
     * 游戏服务token不存在
     */
    const SERVICE_GAME_SERVICE_NOT_EXIST_TOKEN = 44901;
    
    /**
     * 游戏服务内部错误
     */
    const SERVICE_GAME_SERVICE_SYSTEM_ERROR = 44902;
    
    /**
     * 游戏服务请求超时
     */
    const SERVICE_GAME_SERVICE_REQUEST_TIMEOUT = 44903;
    
    /* Api服务错误 */
    /**
     * Api服务错误
     */
    const SERVICE_API_SERVICE = 44920;
    
    /**
     * Api服务请求超时
     */
    const SERVICE_API_SERVICE_REQUEST_TIMEOUT = 44921;
    
    /**
     * 构造并初始化参数
     *
     * @param int $code            
     * @param string $message            
     */
    public function __construct(?int $code = self::SERVICE_ERROR, string $message = '')
    {
        $code = $code ?? self::SERVICE_ERROR;
        
        $message = $message ?: trans('exception.service.' . $code);
        
        parent::__construct($message, $code);
    }

    /**
     * 记录异常日志
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * 将异常渲染到 HTTP 响应中
     *
     * @param \Illuminate\Http\Request $request            
     * @return \App\Core\Response\ResponseError
     */
    public function render($request)
    {
        return new ResponseError($this->getCode(), $this->getMessage());
    }
}