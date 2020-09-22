<?php
namespace App\Http\Requests\System\Setting\MenuAction;

use App\Http\Requests\Request;

class ItemsRequest extends Request
{

    /**
     * 应用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'menu_id' => 'required|integer|min:1',
            'actions.system.*.name' => 'required|string|between:2,16',
            'actions.system.*.action' => 'required|string|between:2,64',
            'actions.custom.*.name' => 'required|string|between:2,16',
            'actions.custom.*.action' => 'required|string|between:2,64'
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
        return trans('validation.requests.system.setting.menu-action.store-update.attributes');
    }
}
