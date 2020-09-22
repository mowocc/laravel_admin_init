<?php
namespace App\Http\Requests\Admin;

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
            'name' => 'required|string|between:2,32',
            'email' => 'required|string|email|max:128'
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
        return trans('validation.requests.admin.update.attributes');
    }
}
