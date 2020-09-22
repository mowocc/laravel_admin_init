<?php

namespace App\Models\Admin\Auth;

use App\Models\Admin\Model;
use App\Models\Admin\Config\Server;
use App\Models\Admin\Config\Language;

class UserConfig extends Model
{

    /**
     * 数据表名称
     *
     * @var string
     */
    protected $table = 'auth_user_configs';

    /**
     * 进行类型转换的字段
     *
     * @var array
     */
    protected $casts = [
        'created' => 'timestamp',
        'updated' => 'timestamp'
    ];

    /**
     * 可以批量赋值的字段
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'server_id',
        'language_lang',
    ];

    /**
     * 获得服务器配置
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id', 'id');
    }

    /**
     * 获得语言配置
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_lang', 'lang');
    }
}
