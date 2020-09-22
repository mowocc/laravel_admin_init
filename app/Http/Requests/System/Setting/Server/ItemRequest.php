<?php
namespace App\Http\Requests\System\Setting\Server;

use App\Http\Requests\Request;

class ItemRequest extends Request
{

    /**
     * 应用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'nullable|integer|min:1',
            'name' => 'required|string|between:2,32',
            'mysql_agency.host' => 'required|string|max:1024',
            'mysql_agency.port' => 'required|integer|between:1,65535',
            'mysql_agency.database' => 'required|string|max:64',
            'mysql_agency.username' => 'required|string|max:32',
            'mysql_agency.password' => 'nullable|string|max:128'
        ];
    }

    /**
     * 自定义验证规则的错误消息。
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * 自定义字段名称。
     *
     * @return array
     */
    public function attributes()
    {
        return trans('validation.requests.system.setting.server.store-update.attributes');
    }
}
