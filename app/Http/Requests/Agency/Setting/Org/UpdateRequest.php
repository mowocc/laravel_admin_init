<?php
namespace App\Http\Requests\Agency\Setting\Org;

use App\Http\Requests\Request;

class UpdateRequest extends Request
{

    /**
     * 应用于请求的验证规则。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer|min:1',
            'sort' => 'required|integer|min:1',
            'name' => 'required|string|between:2,16'
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
        return trans('validation.requests.agency.setting.org.update.attributes');
    }
}
