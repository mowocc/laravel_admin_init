<?php
namespace App\Http\Requests\System\Setting\Language;

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
            'lang' => 'required|string|between:2,16',
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
        return trans('validation.requests.system.setting.language.store-update.attributes');
    }
}
